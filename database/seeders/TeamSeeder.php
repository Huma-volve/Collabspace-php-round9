<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Team;
use App\Models\User;

class TeamSeeder extends Seeder
{
    public function run(): void
    {
        // نفترض إن عندك users متعملين قبل كده
        $admin = User::where('role', 'admin')->first();
        $employees = User::where('role', 'employee')->take(2)->get();

        Team::create([
            'name' => 'Design Team',
            'leader_id' => $admin?->id,
        ]);

        Team::create([
            'name' => 'Development Team',
            'leader_id' => $employees->get(0)?->id,
        ]);

        Team::create([
            'name' => 'QA Team',
            'leader_id' => $employees->get(1)?->id,
        ]);
    }
}
