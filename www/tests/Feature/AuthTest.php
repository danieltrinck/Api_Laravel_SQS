<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Hash;
use DB;
use App\Models\User;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_login_and_receive_token()
    {

        // Create a user
        $user = User::factory()->create([
            'email'    => 'test@example.com',
            'password' => Hash::make($password = 'password123'),
        ]);

        // Make a POST request to the login route
        $response = $this->postJson('/api/getToken', [
            'email'    => 'test@example.com',
            'password' => $password,
        ]);

        // Check if the response status is OK
        $response->assertStatus(200);

        // Check if the response has a token
        $response->assertJsonStructure([
            'access_token',
        ]);

        // Optionally, you can decode the response and check the token format
        $token = $response->json('access_token');
        $this->assertTrue(is_string($token) && strlen($token) > 0);

    }

}
