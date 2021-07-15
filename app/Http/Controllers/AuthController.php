<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use Validator;

class AuthController extends Controller
{
    //Register User
    public function register (Request $request){

        //Validation rules
        $rules = [
            'name' => 'required|max:55',
            'email' =>'email|unique:users|required',
            'password' => 'required|confirmed'
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

        //Create user in database if validation passes
        $name = $request->input('name');
        $email = $request->input('email');
        $pass = bcrypt($request->input('password'));

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => $pass
        ])->makehidden(['updated_at', 'created_at', 'email_verified_at']);

        //Create User access token
        $accessToken = $user->createToken('authToken')->accessToken;

        $response = [
            "message" => "Account Created Successfully",
            "data" => [
                "user" => $user,
                "accessToken" => $accessToken
            ]
            ];

            return response()->json($response, 201);

    }

    //Login
    public function login(Request $request){

         //Validation rules
         $rules = [
            'email' =>'email|required',
            'password' => 'required'
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

        //Login user
        $email = $request->input('email');
        $pass = $request->input('password');

        $loginData = [
            'email' => $email,
            'password' => $pass
        ];

        //If login failed
        if(!auth()->attempt($loginData)){
            $response = [
                'status' => 'Failed',
                'message' => 'Username or Password Incorrect'
            ];

            return response()->json($response, 200);
        }

        //If login is successful, generate access token for user
        $user = auth()->user()->makehidden(['updated_at', 'created_at', 'email_verified_at']);

        $accessToken = $user->createToken('authToken')->accessToken;

        $response = [
            'status' => 'Success',
            'data' => [
                'user' => $user,
                'accessToken' => $accessToken
            ]
            ];

            return response()->json($response, 200);

    }
}