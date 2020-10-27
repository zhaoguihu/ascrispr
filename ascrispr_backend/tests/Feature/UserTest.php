<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $response = $this->post('/api/login?email=871228582@qq.com&password=123456');
//        $response = $this->withSession(['email' => '871228582@qq.com','password' => '123456'])->post('/api/login');
        $response->assertStatus(200);
//        $this->assertTrue(true);
    }
}
