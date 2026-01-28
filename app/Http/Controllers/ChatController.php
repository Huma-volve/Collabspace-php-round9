<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\User;
use App\Http\Resources\ChatResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{
    /**
     * Get all chats for the authenticated user
     */
    public function index()
    {
        $chats = auth()->user()->chats()
            ->with([
                'users:id,name,email',
                'messages' => function ($query) {
                    $query->latest()->limit(1);
                }
            ])
            ->latest('updated_at')
            ->get();

        return ChatResource::collection($chats);
    }

    /**
     * Create or find existing chat with another user
     */
    public function store(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id|different:' . auth()->id()
        ]);

        $currentUser = auth()->user();
        $receiverId = $request->receiver_id;

        // Find existing chat between the 2 users
        $chat = $this->findExistingChat($currentUser->id, $receiverId);

        if ($chat) {
            return new ChatResource($chat->load('users'));
        }

        // Create new chat if none exists
        $chat = $this->createNewChat($currentUser, $receiverId);

        return new ChatResource($chat);
    }

    /**
     * Find existing chat between two users
     */
    protected function findExistingChat(int $userId1, int $userId2): ?Chat
    {
        return Chat::whereHas('users', function ($query) use ($userId1) {
                $query->where('user_id', $userId1);
            })
            ->whereHas('users', function ($query) use ($userId2) {
                $query->where('user_id', $userId2);
            })
            ->first();
    }

    /**
     * Create new chat between two users
     */
    protected function createNewChat(User $currentUser, int $receiverId): Chat
    {
        DB::beginTransaction();

        try {
            $receiver = User::findOrFail($receiverId);

            $chat = Chat::create([
                'user_name_at_chat' => $receiver->name
            ]);

            // Attach both users to the chat
            $chat->users()->attach([$currentUser->id, $receiverId]);

            DB::commit();

            return $chat->load('users');

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
