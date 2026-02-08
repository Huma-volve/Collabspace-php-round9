<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Team;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_user_can_register_with_valid_data()
    {
        $response = $this->postJson('/api/auth/register', [
            'full_name' => 'Test User',
            'email' => 'newuser@example.com',
            'password' => 'password123',
            'remeber_me' => true
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'User registered successfully'
            ])
            ->assertJsonStructure([
                'data' => [
                    'user' => [
                        'id',
                        'full_name',
                        'email',
                    ],
                    'token'
                ]
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'newuser@example.com',
            'full_name' => 'Test User'
        ]);
    }


    public function test_user_can_login_with_correct_credentials()
    {
        // Using seeded user from UserSeeder
        $response = $this->postJson('/api/auth/login', [
            'email' => 'ahmed@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Login successful'
            ])
            ->assertJsonStructure([
                'data' => [
                    'user' => [
                        'id',
                        'full_name',
                        'email',
                    ],
                    'token'
                ]
            ]);
    }

    public function test_authenticated_user_can_get_their_profile()
    {
        // Login first to get token
        $loginResponse = $this->postJson('/api/auth/login', [
            'email' => 'ahmed@example.com',
            'password' => 'password123',
        ]);

        $token = $loginResponse->json('data.token');

        // Get user profile with token
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/auth/user');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'User retrieved successfully'
            ])
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'full_name',
                    'email',
                    'team',
                    'chats',
                ]
            ]);
    }

    public function test_unauthenticated_user_cannot_get_profile()
    {
        $response = $this->getJson('/api/auth/user');

        $response->assertStatus(401);
    }

    public function test_authenticated_user_can_logout()
    {
        // Login first to get token
        $loginResponse = $this->postJson('/api/auth/login', [
            'email' => 'ahmed@example.com',
            'password' => 'password123',
        ]);

        $token = $loginResponse->json('data.token');

        // Logout with token
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/auth/logout');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Logout successful'
            ]);
    }

    public function test_token_is_invalidated_after_logout()
    {
        // Login first to get token
        $loginResponse = $this->postJson('/api/auth/login', [
            'email' => 'ahmed@example.com',
            'password' => 'password123',
        ]);

        $token = $loginResponse->json('data.token');
        $userId = $loginResponse->json('data.user.id');

        // Verify token exists in database
        $this->assertDatabaseHas('personal_access_tokens', [
            'tokenable_id' => $userId,
            'tokenable_type' => 'App\Models\User',
        ]);

        // Logout
        $logoutResponse = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/auth/logout');

        $logoutResponse->assertStatus(200);

        // Verify token is deleted from database
        $this->assertDatabaseMissing('personal_access_tokens', [
            'tokenable_id' => $userId,
            'tokenable_type' => 'App\Models\User',
        ]);
    }

    public function test_unauthenticated_user_cannot_logout()
    {
        $response = $this->postJson('/api/auth/logout');

        $response->assertStatus(401);
    }
}
