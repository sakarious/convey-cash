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




}