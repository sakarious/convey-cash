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


    /**
     * User can verify account details
     *
     * 
     */
    public function test_will_verify_recipient_account_details()
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

        $bankCode = '057';

        $account = [
            'account_number' => '2111333996',
            'bank_code' => $bankCode,
        ];
        
        $response = $this->json('POST', '/api/v1/verify', $account, $header);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            "status",
            "message",
            "data" => [
                "account_number",
                "account_name",
                "bank_id"
            ]
            ]);
    }


    /**
     * User cannot make a transfer without authentication.
     *
     * 
     */
    public function test_will_not_make_transfers_if_authentication_fails()
    {
        $user = User::create([
            'name' => 'Oluwashegs',
            'email' => 'o@gmail.com',
            'password' => '12345'
        ]);


        $header = [];
        $header['Accept'] = 'application/json';

        $account = [
            'account_number' => '2111333996',
            'bank_code' => '057',
            'amount' => '50000000',
            'reason' => 'Laulau'
        ];
        
        $response = $this->json('POST', '/api/v1/transfer', $account, $header);

        $response->assertStatus(401);
    }


    /**
     * User cannot make transfers if validation fails
     *
     * 
     */
    public function test_will_not_make_transfer_if_validation_fails()
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
            'account_number' => '2111333996',
            'bank_code' => '057',
        ];
        
        $response = $this->json('POST', '/api/v1/transfer', $account, $header);

        $response->assertStatus(400);
        $response->assertJsonStructure([
            "message",
            "errors"
            ]);
    }


        /**
     * User cannot make transfer if account number is invalid
     *
     * 
     */
    public function test_will_not_make_transfer_if_account_number_is_invalid()
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
            'account_number' => '21113339967',
            'bank_code' => '057',
            'amount' => '50000000',
            'reason' => 'Laulau'
        ];
        
        $response = $this->json('POST', '/api/v1/transfer', $account, $header);

        $response->assertStatus(200);
        $response->assertJson([
            "status" => false,
            "message" => "Could not resolve account name. Check parameters or try again."
            ]);
    }


    /**
     * User cannot make transfer if bank code is invalid
     *
     * 
     */
    public function test_will_not_make_transfer_if_bank_code_is_invalid()
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
            'account_number' => '2111333996',
            'bank_code' => '066',
            'amount' => '50000000',
            'reason' => 'Laulau'
        ];
        
        
        $response = $this->json('POST', '/api/v1/transfer', $account, $header);

        $response->assertStatus(200);
        $response->assertJson([
            "status" => false,
            "message" => "Unknown bank code: 066"
            ]);
    }


     /**
     * User can list transaction history
     *
     * 
     */
    public function test_will_get_empty_transaction_history()
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
        
        $response = $this->json('GET', '/api/v1/history', [], $header);

         $response->assertStatus(200);
        $response->assertJson([
            "status" => "success",
            "message" => "You haven't made a transaction yet."
            ]);
    }


    /**
     * User can search transaction history
     *
     * 
     */
    public function test_will_search_empty_transaction_history()
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
        
        $response = $this->json('GET', '/api/v1/history?accountNumber=2111333996', [], $header);

         $response->assertStatus(200);
        $response->assertJson([
            "status" => "success",
            "message" => "No history found"
            ]);
    }


    /**
     * User can make transfer
     *
     * 
     */
    public function test_will_make_transfer()
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
            'account_number' => '2111333996',
            'bank_code' => '057',
            'amount' => '50000000',
            'reason' => 'Laulau'
        ];
        
        $response = $this->json('POST', '/api/v1/transfer', $account, $header);

        $response->assertStatus(200);

        $response->assertJson([
            "status" => "Success",
            "message" => "You Successfully transferred 50000000 to OLUWASEGUN MOSES AJAYI for Laulau"
            ]);
    }

    /**
     * User can list transaction history
     *
     * 
     */
    public function test_will_list_transaction_history()
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

        //Create Dummy transaction history
        $history = Accounts::create([
            'user_id' => 1,
            'account_name' => 'Sakarious Da Genius',
            'account_number' => '1020304050',
            'bank_code' => '000',
            'bank_name' => 'Bank Sakarious',
            'currency' => 'NGN',
            'amount' => '3000000000000000',
            'reason' => 'Transport Fare',
            'status' => 'Successful'
        ]);
        
        $response = $this->json('GET', '/api/v1/history', [], $header);

         $response->assertStatus(200);
         $response->assertJsonStructure([
                    "status",
                    "data"
                ]);
    }


    /**
     * User can search transaction history
     *
     * 
     */
    public function test_will_search_transaction_history()
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

        //Create Dummy transaction history
        $history = Accounts::create([
            'user_id' => 1,
            'account_name' => 'Sakarious Da Genius',
            'account_number' => '1020304050',
            'bank_code' => '000',
            'bank_name' => 'Bank Sakarious',
            'currency' => 'NGN',
            'amount' => '3000000000000000',
            'reason' => 'Transport Fare',
            'status' => 'Successful'
        ]);
        
        $response = $this->json('GET', '/api/v1/history?accountNumber=1020304050', [], $header);

         $response->assertStatus(200);
         $response->assertJsonStructure([
            "status",
            "data"
        ]);
    }


}