<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProjectTeamSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('project_team')->insert([
            [
                'project_id' => 1,
                'team_id' => 1, // Design Team
            ],
            [
                'project_id' => 1,
                'team_id' => 2, // Dev Team
            ],
            [
                'project_id' => 2,
                'team_id' => 2, // Dev Team
            ],
            [
                'project_id' => 3,
                'team_id' => 3, // QA Team
            ],
        ]);
    }
}
