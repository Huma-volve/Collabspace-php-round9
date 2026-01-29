<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Run seeders in correct order:
        // 1. Create users first
        $this->call(UserSeeder::class);
        
        // 2. Create teams (needs users as leaders)
        $this->call(TeamSeeder::class);
        
        // 3. Create chats
        $this->call(ChatSeeder::class);
        
        // 4. Create messages
        $this->call(MessageSeeder::class);
    }
}
