<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Project;
use Carbon\Carbon;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        Project::insert([
            [
                'name' => 'Website Redesign',
                'description' => 'Improve UI/UX',
                'type' => 'UI/UX',
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addMonth(),
                'priority' => 'high',
                'status' => 1,
            ],
            [
                'name' => 'Mobile App',
                'description' => 'Build Android & iOS app',
                'type' => 'Mobile',
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addMonths(2),
                'priority' => 'medium',
                'status' => 1,
            ],
            [
                'name' => 'API Development',
                'description' => 'Backend APIs',
                'type' => 'Backend',
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addMonth(),
                'priority' => 'low',
                'status' => 1,
            ],
        ]);
    }
}
