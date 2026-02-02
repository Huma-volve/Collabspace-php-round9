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
    /**
     * Seed the application's database.
     */
    public function run(): void
{
    $this->call([
        UserSeeder::class,
        TeamSeeder::class,
        ProjectSeeder::class,
        TaskSeeder::class,
        ProjectTeamSeeder::class,
        MeetingSeeder::class,
        FileSeeder::class,
    ]);
}

}
