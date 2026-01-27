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
        $employee1 = User::where('role', 'employee')->first();
        $employee2 = User::where('role', 'employee')->skip(1)->first();

        Team::insert([
            [
                'name' => 'Design Team',
                'leader_id' => $admin?->id,
            ],
            [
                'name' => 'Development Team',
                'leader_id' => $employee1?->id,
            ],
            [
                'name' => 'QA Team',
                'leader_id' => $employee2?->id,
            ],
        ]);
    }
}
