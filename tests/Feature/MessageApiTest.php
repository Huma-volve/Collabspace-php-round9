<?php

namespace Tests\Feature;

use App\Models\Chat;
use App\Models\User;
use App\Models\Message;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MessageApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_get_all_messages_from_a_chat()
    {
        // Chat 1 exists between Ahmed (id=1) and Sara (id=2)
        // Authenticated user is Sara (id=2) from MockAuth
        $response = $this->getJson('/api/chats/1/messages');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Messages retrieved successfully'
            ]);
    }

    public function test_prevents_non_member_from_viewing_chat_messages()
    {
        // Chat 1 exists between Ahmed (id=1) and Sara (id=2)
        // Authenticated user is Sara (id=2), but trying to access Chat 2 (Ahmed & Mohamed)
        $response = $this->getJson('/api/chats/2/messages');

        $response->assertStatus(403)
            ->assertJson([
                'success' => false,
                'message' => 'You are not a member of this chat.'
            ]);
    }

    public function test_it_fails_when_chat_does_not_exist()
    {
        $response = $this->getJson('/api/chats/999/messages');

        $response->assertNotFound();
    }

    public function test_send_message_to_chat()
    {
        // Chat 1 exists between Ahmed (id=1) and Sara (id=2)
        // Authenticated user is Sara (id=2) from MockAuth
        $response = $this->postJson('/api/chats/1/messages', [
            'body' => 'Hello Ahmed, this is a test message!'
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Message sent successfully'
            ]);

        // Verify message was saved in database
        $this->assertDatabaseHas('messages', [
            'chat_id' => 1,
            'user_id' => 2, // Sara
            'body' => 'Hello Ahmed, this is a test message!'
        ]);
    }

    public function test_prevents_non_member_from_sending_message_to_chat()
    {
        // Chat 2 exists between Ahmed (id=1) and Mohamed (id=3)
        // Authenticated user is Sara (id=2), not a member of Chat 2
        $response = $this->postJson('/api/chats/2/messages', [
            'body' => 'I should not be able to send this!'
        ]);

        $response->assertStatus(403)
            ->assertJson([
                'success' => false,
                'message' => 'You are not a member of this chat.'
            ]);
    }

    // Unauthorized Access Tests
    public function test_unauthenticated_user_cannot_get_messages()
    {
        // Make request without authentication
        $response = $this->getJson('/api/chats/1/messages', [
            'Accept' => 'application/json'
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'message' => 'Unauthenticated.'
            ]);
    }

    public function test_unauthenticated_user_cannot_send_message()
    {
        // Make request without authentication
        $response = $this->postJson('/api/chats/1/messages', [
            'body' => 'This should not be sent!'
        ], [
            'Accept' => 'application/json'
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'message' => 'Unauthenticated.'
            ]);
    }

    public function test_unauthenticated_user_cannot_send_typing_indicator()
    {
        // Make request without authentication
        $response = $this->postJson('/api/chats/1/typing', [], [
            'Accept' => 'application/json'
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'message' => 'Unauthenticated.'
            ]);
    }

}
