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




}