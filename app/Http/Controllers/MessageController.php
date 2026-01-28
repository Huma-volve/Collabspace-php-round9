<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Http\Resources\MessageResource;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    /**
     * Get all messages from a specific chat
     */
    public function index(Chat $chat)
    {
        // Verify user is member of the chat
        if (!$chat->users->contains(auth()->id())) {
            return response()->json([
                'message' => 'Unauthorized. You are not a member of this chat.'
            ], 403);
        }

        $messages = $chat->messages()
            ->with('user:id,name,email')
            ->orderBy('created_at', 'asc')
            ->get();

        return MessageResource::collection($messages);
    }

    /**
     * Send a new message in a chat
     */
    public function store(Request $request, Chat $chat)
    {
        // Verify user is member of the chat
        if (!$chat->users->contains(auth()->id())) {
            return response()->json([
                'message' => 'Unauthorized. You are not a member of this chat.'
            ], 403);
        }

        $request->validate([
            'body' => 'required|string|max:2000'
        ]);

        $message = $chat->messages()->create([
            'body' => $request->body,
            'user_id' => auth()->id(),
            'created_at' => now()
        ]);

        return new MessageResource($message->load('user'));
    }
}
