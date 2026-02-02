<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Task;
use App\Models\Project;
use App\Models\User;
use Carbon\Carbon;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        $project = Project::first();
        $users   = User::pluck('id'); // نجيب كل اليوزرز

        // لو مفيش project أو users نخرج
        if (! $project || $users->isEmpty()) {
            return;
        }

        $tasks = [
            [
                'name' => 'Design Homepage',
                'description' => 'Create homepage UI design',
                'start_date' => Carbon::today()->subDays(2),
                'end_date' => Carbon::today(), // Today
                'priority' => 'high',
                'status' => 'progress',
            ],
            [
                'name' => 'User Research',
                'description' => 'Conduct user interviews',
                'start_date' => Carbon::today()->subDays(5),
                'end_date' => Carbon::today()->addDays(2),
                'priority' => 'medium',
                'status' => 'todo',
            ],
            [
                'name' => 'API Integration',
                'description' => 'Integrate backend APIs',
                'start_date' => Carbon::today()->subDays(7),
                'end_date' => Carbon::today()->addDays(5),
                'priority' => 'high',
                'status' => 'review',
            ],
            [
                'name' => 'Fix Bugs',
                'description' => 'Fix reported bugs',
                'start_date' => Carbon::today()->subDays(10),
                'end_date' => Carbon::today()->subDays(1), // Overdue
                'priority' => 'low',
                'status' => 'progress',
            ],
            [
                'name' => 'Deploy Project',
                'description' => 'Deploy project to production',
                'start_date' => Carbon::today()->subDays(15),
                'end_date' => Carbon::today()->subDays(3),
                'priority' => 'high',
                'status' => 'completed',
            ],
        ];

        foreach ($tasks as $task) {
            Task::create(array_merge($task, [
                'project_id' => $project->id,
                // نوزّع التاسكات عشوائي على اليوزرز
                'user_id' => $users->random(),
            ]));
        }
    }
}
