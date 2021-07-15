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


}