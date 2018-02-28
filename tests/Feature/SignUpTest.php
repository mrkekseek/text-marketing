<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use App\User;

class SignUpTest extends TestCase
{
    use RefreshDatabase;

    public $user;

    public function setUp()
    {
        parent::setUp();

        Artisan::call('migrate');
        
        $this->user = factory(\App\User::class)
            ->create([
                'email' => 'domanskyidenys@gmail.com',
            ]);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testUserEmail()
    {
        $this->assertEquals($this->user->email, 'domanskyidenys@gmail.com');
    }

    /* public function testSignUp()
    {
        $this->visit('/signup/home-advisor')
            ->type('Denys', 'name')
            ->type('domanskyidenys@gmail.com', 'email')
            ->type('secret', 'password')
            ->press('Sign Up')
            ->seeInDatabase('users', ['email' => 'someone@outlook.com']);
    } */
}
