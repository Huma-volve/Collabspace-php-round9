<?php

namespace Database\Seeders;

use App\Models\Chat;
use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class MessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get users
        $ahmed = User::where('email', 'ahmed@example.com')->first();
        $sara = User::where('email', 'sara@example.com')->first();
        $mohamed = User::where('email', 'mohamed@example.com')->first();

        // Get chats
        $chat1 = Chat::whereHas('users', fn($q) => $q->where('user_id', $ahmed->id))
                    ->whereHas('users', fn($q) => $q->where('user_id', $sara->id))
                    ->first();
        
        $chat2 = Chat::whereHas('users', fn($q) => $q->where('user_id', $ahmed->id))
                    ->whereHas('users', fn($q) => $q->where('user_id', $mohamed->id))
                    ->first();

        // Chat 1: Conversation between Ahmed and Sara (Backend & Frontend collaboration)
        $baseTime = Carbon::now()->subHours(3);
        
        Message::create([
            'body' => 'Hi Sara! How is the new UI coming along?',
            'chat_id' => $chat1->id,
            'user_id' => $ahmed->id,
            'created_at' => $baseTime->copy(),
        ]);

        Message::create([
            'body' => 'Hey Ahmed! It\'s going well. I\'m almost done with the dashboard redesign.',
            'chat_id' => $chat1->id,
            'user_id' => $sara->id,
            'created_at' => $baseTime->copy()->addMinutes(2),
        ]);

        Message::create([
            'body' => 'Great! Do you need any API endpoints for the new features?',
            'chat_id' => $chat1->id,
            'user_id' => $ahmed->id,
            'created_at' => $baseTime->copy()->addMinutes(5),
        ]);

        Message::create([
            'body' => 'Yes! I need a couple of endpoints for the analytics section. Can we discuss this tomorrow?',
            'chat_id' => $chat1->id,
            'user_id' => $sara->id,
            'created_at' => $baseTime->copy()->addMinutes(7),
        ]);

        Message::create([
            'body' => 'Sure! Let\'s meet at 10 AM. I\'ll prepare the documentation.',
            'chat_id' => $chat1->id,
            'user_id' => $ahmed->id,
            'created_at' => $baseTime->copy()->addMinutes(10),
        ]);

        // Chat 2: Conversation between Ahmed and Mohamed (Backend & Design collaboration)
        $baseTime2 = Carbon::now()->subHours(1);

        Message::create([
            'body' => 'Mohamed, can you review the user profile page design?',
            'chat_id' => $chat2->id,
            'user_id' => $ahmed->id,
            'created_at' => $baseTime2->copy(),
        ]);

        Message::create([
            'body' => 'Of course! Send me the link and I\'ll take a look.',
            'chat_id' => $chat2->id,
            'user_id' => $mohamed->id,
            'created_at' => $baseTime2->copy()->addMinutes(3),
        ]);

        Message::create([
            'body' => 'Here you go: https://figma.com/design/profile',
            'chat_id' => $chat2->id,
            'user_id' => $ahmed->id,
            'created_at' => $baseTime2->copy()->addMinutes(5),
        ]);

        Message::create([
            'body' => 'Looks good! I have a few suggestions for the layout. Can I share them with you?',
            'chat_id' => $chat2->id,
            'user_id' => $mohamed->id,
            'created_at' => $baseTime2->copy()->addMinutes(15),
        ]);

        Message::create([
            'body' => 'Absolutely! I\'m open to any improvements.',
            'chat_id' => $chat2->id,
            'user_id' => $ahmed->id,
            'created_at' => $baseTime2->copy()->addMinutes(17),
        ]);

        Message::create([
            'body' => 'Great! I\'ll create a new version and send it to you by end of day.',
            'chat_id' => $chat2->id,
            'user_id' => $mohamed->id,
            'created_at' => $baseTime2->copy()->addMinutes(20),
        ]);
    }
}
