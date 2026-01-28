<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Project;
use App\Models\Task;
use App\Models\Comment;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 5 Users
        User::factory(5)->create();

        // 3 Projects
        Project::factory(3)->create();

        // 10 Tasks
        Task::factory(10)->create();

        // 20 Comments
        Comment::factory(20)->create();
    }
}
