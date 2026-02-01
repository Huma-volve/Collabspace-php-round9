<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\User;
use App\Http\Resources\ChatResource;
use App\Traits\MockAuth;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{
    use MockAuth, ApiResponse;
    /**
     * Get all chats for the authenticated user
     */
    public function index()
    {
        $chats = $this->getAuthUser()->chats()
            ->with([
                'users:id,full_name,image,experience,team_id',
                'users.team:id,name',
                'messages' => function ($query) {
                    $query->latest()->limit(1);
                }
            ])
            ->latest('updated_at')
            ->get();

        return $this->successResponse(
            ChatResource::collection($chats),
            'Chats retrieved successfully'
        );
    }

    /**
     * Create or find existing chat with another user
     */
    public function store(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id|different:' . $this->getAuthUserId()
        ]);

        $currentUser = $this->getAuthUser();
        $receiverId = $request->receiver_id;
        if($currentUser->id == $receiverId){
            return $this->errorResponse('You cannot chat with yourself', 400);
        }

        // Find existing chat between the 2 users
        $chat = $this->findExistingChat($currentUser->id, $receiverId);

        if ($chat) {
            return $this->successResponse(
                new ChatResource($chat), 
                'Chat already exists'
            );
        }

        // Create new chat if none exists
        $chat = $this->createNewChat($currentUser, $receiverId);

        return $this->successResponse(
            new ChatResource($chat),
            'Chat created successfully',
            201
        );
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
            ->with([
                'users:id,full_name,image,experience,team_id',
                'users.team:id,name',
                'messages' => function ($query) {
                    $query->latest()->limit(1);
                }
            ])
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

            $chat = Chat::create();

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
