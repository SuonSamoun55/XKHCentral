<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserApiAndLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_api_creates_user_and_allows_login()
    {
        $payload = [
            'name' => 'Sam User',
            'email' => 'sam@example.test',
            'password' => 'secret123',
            'role' => 'customer',
        ];

        // Create user via API
        $response = $this->postJson('/api/users', $payload);
        $response->assertStatus(201);

        // Ensure user exists in database
        $this->assertDatabaseHas('users', [
            'email' => $payload['email'],
            'name' => $payload['name'],
        ]);

        // Attempt web login (AuthController uses Auth::attempt)
        $loginResponse = $this->post('/login', [
            'email' => $payload['email'],
            'password' => $payload['password'],
        ]);

        $loginResponse->assertRedirect(route('pos.index'));
        $this->assertAuthenticated();
    }
}
