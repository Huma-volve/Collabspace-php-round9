<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Events\UserTyping;
use App\Models\Chat;
use App\Models\User;
use App\Http\Resources\MessageResource;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    use ApiResponse;
    /**
     * Get all messages from a specific chat
     */
    public function index(Chat $chat)
    {
        // Verify user is member of the chat
        if (!$chat->users->contains(auth()->id())) {
            return $this->forbiddenResponse('You are not a member of this chat.');
        }

        $messages = $chat->messages()
            ->with('user:id,full_name')
            ->orderBy('created_at', 'desc')
            ->cursorPaginate(10);

        return $this->successResponse(
            MessageResource::collection($messages), // منا عندى Resource لازم يستخدمه
            'Messages retrieved successfully'
        );
    }

    /**
     * Send a new message in a chat
     */
    public function store(Request $request, Chat $chat)
    {
        // Verify user is member of the chat
        if (!$chat->users->contains(auth()->id())) {
            return $this->forbiddenResponse('You are not a member of this chat.');
        }

        $request->validate([
            'body' => 'required|string|min:1|max:2000'
        ]);

        $message = $chat->messages()->create([
            'body' => $request->body,
        ]);

        $message->user_id = auth()->id();
        $message->created_at = now();
        $message->save();

        // Load relationships for the event
        $message->load('user:id,full_name,image');

        broadcast(new MessageSent($message))->toOthers();

        return $this->successResponse(
            new MessageResource($message),
            'Message sent successfully',
            201
        );
    }

    /**
     * Broadcast typing indicator
     */
    public function typing(Request $request, Chat $chat)
    {
        $request->validate([
            'is_typing' => 'required|boolean'
        ]);

        $user = User::find(auth()->id());

        broadcast(new UserTyping(
            $chat->id,
            $user,
            $request->is_typing
        ))->toOthers();

        return $this->successResponse(
            null,
            'Typing status broadcasted'
        );
    }
}
