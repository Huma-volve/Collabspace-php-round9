<?php

namespace Database\Seeders;

use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Seeder;

class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get users
        $ahmed = User::where('email', 'ahmed@example.com')->first();
        $sara = User::where('email', 'sara@example.com')->first();

        // Team 1: Backend Team (Ahmed is leader)
        Team::create([
            'name' => 'Backend Team',
            'leader_id' => $ahmed->id,
        ]);

        // Team 2: Frontend Team (Sara is leader)
        Team::create([
            'name' => 'Frontend Team',
            'leader_id' => $sara->id,
        ]);
    }
}
