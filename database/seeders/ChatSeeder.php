<?php

namespace Database\Seeders;

use App\Models\Chat;
use App\Models\User;
use Illuminate\Database\Seeder;

class ChatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the users
        $ahmed = User::where('email', 'ahmed@example.com')->first();
        $sara = User::where('email', 'sara@example.com')->first();
        $mohamed = User::where('email', 'mohamed@example.com')->first();

        // Chat 1: Between Ahmed and Sara
        $chat1 = Chat::create([
        ]);
        
        // Attach both users to chat1 using the pivot table
        $chat1->users()->attach([$ahmed->id, $sara->id]);

        // Chat 2: Between Ahmed and Mohamed
        $chat2 = Chat::create([
        ]);
        
        // Attach both users to chat2 using the pivot table
        $chat2->users()->attach([$ahmed->id, $mohamed->id]);
    }
}
