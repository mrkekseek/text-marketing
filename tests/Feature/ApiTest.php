<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Libraries\Api;

class ApiTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testMaxLenghtOfCompanyName()
    {
        $response = Api::company('First Testovich Company First Testovich Company First Testovich Company First Testovich Company First Testovich Company');
        $this->assertFalse($response['data']);
    }
    
    /* public function testVerifiedCompany()
    {
        $response = Api::company('First Testovich Company');
		$this->assertEquals($response['data'], 'verified');
    }

    public function testDeniedCompany()
    {
        $response = Api::company('Second Testovich Company');
		$this->assertEquals($response['data'], 'denied');
    }

    public function testCreatingNewCompany()
    {
        $response = Api::company('New company');
        $this->assertEquals($response['data']['data']['status_code'], 'MPCE4001');
        $this->assertEquals($response['data']['data']['request_id'], '1234561234567asdf123');
    } */
}
