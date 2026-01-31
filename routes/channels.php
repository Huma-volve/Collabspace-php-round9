<?php

use App\Models\Chat;
use Illuminate\Support\Facades\Broadcast;

// User model channel (default)
// Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
//     return (int) $user->id === (int) $id;
// });


Broadcast::channel('chat.{chatId}', function ($user, $chatId) {
    return Chat::find($chatId)?->users->contains($user->id) ?? false;
});
