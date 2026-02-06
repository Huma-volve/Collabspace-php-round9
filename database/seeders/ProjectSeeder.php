<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */



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
                'end_date' => Carbon::now()->addMonths(4),
                'priority' => 'low',
                'status' => 1,
            ],
        ]);
    }
}
