<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Accounts;
use Validator;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    //Inform users they are unauthorized
    public function checkAuth(){
        $response = [
            "message" => "You are unauthorized. Login or Register to continue"
        ];

        return response()->json($response, 401);
    }

    //Verify Account
    public function verifyAccount(Request $request){

        //Validation rules
        $rules = [
            'account_number' => 'required',
            'bank_code' =>'required'
        ];

        //Check Validation
        $validator = Validator::make($request->all(), $rules);

        //If validation fails, Send appropriate response
        if($validator->fails()){
            $response = [
                "message" => "Validation Failed",
                "errors" => $validator->errors()
            ];

            return response()->json($response, 400);
        }

        $accountNumber = $request->input('account_number');
        $bankCode = $request->input('bank_code');

        //Check resolve account
        $headers = [
            "Authorization: Bearer sk_test_a1874266fc96fa3a907746101da763ae2138e828"
        ];

        $ch = curl_init("https://api.paystack.co/bank/resolve?account_number=".$accountNumber."&bank_code=".$bankCode);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        // execute!
        $response = curl_exec($ch);
        $error = curl_error($ch);

        // close the connection, release resources used
        curl_close($ch);

        if($error){
            //Return error
            $jsonRes = [
                'status' => false,
                'message' => 'Account Number cannot be resolved at this time. Try later',
            ];

            return response()->json($jsonRes, 200);
        }

        $result = json_decode($response);
        $verify = $result->status;

        //return json response
        if($verify){
            $jsonRes = [
                'status' => "Success",
                'message' => $result->message,
                'data' => $result->data,
            ];

            return response()->json($jsonRes, 200);
        }

        return response()->json($result, 200);

    }

    //Create Transfer
    public function transfer(Request $request){
         //Validation rules
         $rules = [
            'account_number' => 'required',
            'bank_code' =>'required',
            'amount' => 'required',
            'reason' => 'required'
        ];

        //Check Validation
        $validator = Validator::make($request->all(), $rules);

        //If validation fails, Send appropriate response
        if($validator->fails()){
            $response = [
                "message" => "Validation Failed",
                "errors" => $validator->errors()
            ];

            return response()->json($response, 400);
        }

        $accountNumber = $request->input('account_number');
        $bankCode = $request->input('bank_code');
        $amount = $request->input('amount');
        $reason = $request->input('reason');

        //Confirm if acccount is valid before proceeding with transfer

        $headers = [
            "Authorization: Bearer sk_test_a1874266fc96fa3a907746101da763ae2138e828"
        ];

        $ch = curl_init("https://api.paystack.co/bank/resolve?account_number=".$accountNumber."&bank_code=".$bankCode);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        // execute!
        $response = curl_exec($ch);
        $error = curl_error($ch);

        // close the connection, release resources used
        curl_close($ch);

        if($error){
            //Return error
            $jsonRes = [
                'status' => false,
                'message' => 'Account Number cannot be verified at this time. Try later',
            ];

            return response()->json($jsonRes, 200);
        }

        $result = json_decode($response);
        $verify = $result->status;

        //If account number was successfully verified Carry Out transfer
        if($verify){

            $user = $request->user()->id;

            $recipient = Accounts::create([
                'user_id' => $user,
                'account_name' => $result->data->account_name,
                'account_number' => $result->data->account_number,
                'bank_code' => $bankCode,
                'currency' => 'NGN',
                'amount' => $amount,
                'reason' => $reason,
                'status' => 'Successful',
                'bank_name' => 'Nigerian Bank'
            ]);

            $response = [
                'status' => 'Success',
                'message' => "You Successfully transferred ".$amount." to ".$result->data->account_name." for ".$reason
            ];
            return response()->json($response, 200);
        }

        ////if there was an error resolving account number

        return response()->json($result, 200);
    }

    //Search and list transfer history
    public function history(Request $request){

        //Get user id
        $user = $request->user()->id;

        //Can search transfer history by account number. Check if accountNumber is present in query parameter
        if($request->query('accountNumber')){
            
            //Assign name from query parameter to variable
            $accountNumber = $request->query('accountNumber');

            //Search for user transaction in database  where account number is the number given
            $fields = ['user_id' => $user, 'account_number' => $accountNumber];
            $history = Accounts::where($fields)->get()->makehidden(['id', 'user_id', 'bank_code', 'bank_name', 'currency', 'updated_at']);

            //if no history was not found and send appropriate json response
            if ($history == '[]'){
                $jsonRes = array(
                    "status" => "success",
                    "message" => "No history found"
                );
                return response()->json($jsonRes, 200);
            }

            //if history was found, send json response
            $jsonRes = array(
                "status" => "success",
                "data" => $history
            );
            return response()->json($jsonRes, 200);
        }

        //List all user transfer history
        $transfers = Accounts::where("user_id", $user)->get()->makehidden(['id', 'user_id', 'bank_code', 'bank_name', 'currency', 'updated_at']);

        //if no history present, send appropriate json response
        if ($transfers == '[]'){
            $jsonRes = array(
                "status" => "success",
                "message" => "You haven't made a transaction yet."
            );
            return response()->json($jsonRes, 200);
        }

        //if transfer history found, send json response
        $jsonRes = array(
            "status" => "success",
            "data" => $transfers
        );
    return response()->json($jsonRes, 200);

    }
}