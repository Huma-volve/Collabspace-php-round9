<?php

namespace Database\Seeders;


use App\Models\Task;
use App\Models\User;
use App\Models\Comment;
use App\Models\Project;


use Illuminate\Database\Seeder;
use Database\Seeders\ProjectSeeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;



class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
       Project::factory(10)->create();


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
