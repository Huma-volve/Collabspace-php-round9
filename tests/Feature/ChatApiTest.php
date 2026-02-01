<?php

namespace Tests\Feature;

use App\Models\Chat;
use App\Models\User;
use App\Models\Team;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChatApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_get_all_chats_for_authenticated_user()
    {
        $response = $this->getJson('/api/chats');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Chats retrieved successfully'
            ]);
    }

    public function test_create_new_chat_with_another_user()
    {
        $response = $this->postJson('/api/chats', [
            'receiver_id' => 3
        ]);

        $response->assertStatus(201)
        ->assertJson([
            'success' => true,
            'message' => 'Chat created successfully'
        ]);
    }

    public function test_returns_existing_chat_if_already_exists()
    {

        $response = $this->postJson('/api/chats', [
            'receiver_id' => 1
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Chat already exists'
            ]);

    }

    public function test_prevents_user_from_creating_chat_with_themselves()
    {
        $response = $this->postJson('/api/chats', [
            'receiver_id' => 2 // Same as authenticated user (from MockAuth)
        ]);

        $response->assertStatus(400)
            ->assertJson([
                'success' => false,
                'message' => 'You cannot chat with yourself'
            ]);
    }

    public function test_prevents_user_from_creating_chat_with_non_existing_user()
    {
        $response = $this->postJson('/api/chats', [
            'receiver_id' => 100 // Non existing user
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'message' => 'The selected receiver id is invalid.',
                'errors' => 
                    array (
                        'receiver_id' => 
                        array (
                        0 => 'The selected receiver id is invalid.',
                        ),
                    ),
            ]);
    }
}
