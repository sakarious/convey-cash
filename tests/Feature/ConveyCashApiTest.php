<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Accounts;
use App\Models\User;

class ConveyCashApiTest extends TestCase
{

    use RefreshDatabase;

    //Get passport to work
    public function setUp(): void 
    {
        parent::setUp();
        $this->artisan('passport:install');
    }

     /**
     * User Cannot register if validation fails.
     *
     * 
     */
    public function test_will_not_register_user_if_validation_fails()
    {
        $newUser = [
            'name' => 'Sakarious',
            'email' => 'sakarious@yahoo.com',
            'password' => 'secret'
        ];
        
        $response = $this->json('POST', '/api/v1/register', $newUser, ['Accept' => 'application/json']);

        $response->assertStatus(400);
        $response->assertJsonStructure([
            "message",
            "errors"
            ]);
    }

    /**
     * User can register.
     *
     * 
     */
    public function test_will_register_user()
    {
        $newUser = [
            'name' => 'Sakarious',
            'email' => 'sakarious@yahoo.com',
            'password' => 'secret',
            'password_confirmation' => 'secret'
        ];
        
        $response = $this->json('POST', '/api/v1/register', $newUser, ['Accept' => 'application/json']);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            "message",
            "data"
            ]);
    }

    /**
     * User cannot login if validation fails.
     *
     * 
     */
    public function test_will_not_login_user_if_validation_fails()
    {
        $newUser = [
            'email' => 'sakarious@yahoo.com'
        ];
        
        $response = $this->json('POST', '/api/v1/login', $newUser, ['Accept' => 'application/json']);

        $response->assertStatus(400);
        $response->assertJsonStructure([
            "message",
            "errors"
            ]);
    }

    /**
     * User cannot login if login details are invalid.
     *
     * 
     */
    public function test_will_not_login_user_if_details_are_incorrect()
    {
        $newUser = [
            'email' => 'sakarious@yahoo.com',
            'password' => 'secrets'
        ];
        
        $response = $this->json('POST', '/api/v1/login', $newUser, ['Accept' => 'application/json']);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            "status",
            "message"
            ]);
    }

    /**
     * User cannot login if login details are invalid.
     *
     * 
     */
    public function test_will_login_user()
    {
        $newUser = [
            'email' => 'sakarious@yahoo.com',
            'password' => 'secret',
        ];
        
        $this->withoutExceptionHandling();
        
        $response = $this->json('POST', '/api/v1/login', $newUser, ['Accept' => 'application/json']);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            "status"
            ]);
    }

    /**
     * User cannot verify recipient account details without authentication.
     *
     * 
     */
    public function test_will_not_verify_recipient_details_if_authentication_fails()
    {
        $user = User::create([
            'name' => 'Oluwashegs',
            'email' => 'o@gmail.com',
            'password' => '12345'
        ]);


        $header = [];
        $header['Accept'] = 'application/json';

        $account = [
            'account_numbers' => 'sakarious@yahoo.com',
            'bank_code' => 'secret',
        ];
        
        $response = $this->json('POST', '/api/v1/verify', $account, $header);

        $response->assertStatus(401);
    }


    /**
     * User cannot verify account details if validation fails
     *
     * 
     */
    public function test_will_not_verify_recipient_account_details_if_validation_fails()
    {
        $user = User::create([
            'name' => 'Oluwashegs',
            'email' => 'o@gmail.com',
            'password' => '12345'
        ]);

        $token = $user->createToken('TestToken')->accessToken;

        $header = [];
        $header['Accept'] = 'application/json';
        $header['Authorization'] = 'Bearer '.$token;

        $account = [
            'account_numbers' => '2111333996',
            'bank_code' => '057',
        ];
        
        $response = $this->json('POST', '/api/v1/verify', $account, $header);

        $response->assertStatus(400);
        $response->assertJsonStructure([
            "message",
            "errors"
            ]);
    }


    /**
     * User cannot verify recipient account details if an invalid account number is given
     *
     * 
     */
    public function test_will_not_verify_recipient_account_details_if_account_number_is_invalid()
    {
        $user = User::create([
            'name' => 'Oluwashegs',
            'email' => 'o@gmail.com',
            'password' => '12345'
        ]);

        $token = $user->createToken('TestToken')->accessToken;

        $header = [];
        $header['Accept'] = 'application/json';
        $header['Authorization'] = 'Bearer '.$token;

        $account = [
            'account_number' => '211133345789',
            'bank_code' => '057',
        ];
        
        $response = $this->json('POST', '/api/v1/verify', $account, $header);

        $response->assertStatus(200);
        $response->assertJson([
            "status" => false,
            "message" => "Could not resolve account name. Check parameters or try again."
            ]);
    }

    /**
     * User cannot verify recipient account details if an invalid bank code is given
     *
     * 
     */
    public function test_will_not_verify_recipient_account_details_if_bank_code_is_invalid()
    {
        $user = User::create([
            'name' => 'Oluwashegs',
            'email' => 'o@gmail.com',
            'password' => '12345'
        ]);

        $token = $user->createToken('TestToken')->accessToken;

        $header = [];
        $header['Accept'] = 'application/json';
        $header['Authorization'] = 'Bearer '.$token;

        $bankCode = '066';

        $account = [
            'account_number' => '2111333996',
            'bank_code' => $bankCode,
        ];
        
        $response = $this->json('POST', '/api/v1/verify', $account, $header);

        $response->assertStatus(200);
        $response->assertJson([
            "status" => false,
            "message" => "Unknown bank code: ".$bankCode
            ]);
    }





}