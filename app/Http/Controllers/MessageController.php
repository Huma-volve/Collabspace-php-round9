<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Chat;
use App\Http\Resources\MessageResource;
use App\Traits\MockAuth;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    use MockAuth, ApiResponse;
    /**
     * Get all messages from a specific chat
     */
    public function index(Chat $chat)
    {
        // Verify user is member of the chat
        if (!$chat->users->contains($this->getAuthUserId())) {
            return $this->forbiddenResponse('You are not a member of this chat.');
        }

        $messages = $chat->messages()
            ->with('user:id,full_name')
            ->orderBy('created_at', 'asc')
            ->get();

        return $this->successResponse(
            MessageResource::collection($messages),
            'Messages retrieved successfully'
        );
    }

    /**
     * Send a new message in a chat
     */
    public function store(Request $request, Chat $chat)
    {
        // Verify user is member of the chat
        if (!$chat->users->contains($this->getAuthUserId())) {
            return $this->forbiddenResponse('You are not a member of this chat.');
        }

        $request->validate([
            'body' => 'required|string|max:2000'
        ]);

        $message = $chat->messages()->create([
            'body' => $request->body,
            'user_id' => $this->getAuthUserId(),
            'created_at' => now()
        ]);

        // Load relationships for the event
        $message->load('user:id,full_name,image');

        // 🚀 Broadcast the event to other users
        broadcast(new MessageSent($message))->toOthers(); // toOthers مش بتشتغل مع public channel

        return $this->successResponse(
            new MessageResource($message),
            'Message sent successfully',
            201
        );
    }
}
