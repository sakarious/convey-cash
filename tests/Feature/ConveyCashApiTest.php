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




}