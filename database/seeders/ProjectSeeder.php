<?php

namespace Database\Seeders;

use App\Models\Project;
use Faker\Factory as Faker;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {


        $faker = Faker::create();

        for ($i = 0; $i < 10; $i++) {
            $startDate = $faker->dateTimeBetween('-1 month', 'now');
            $endDate   = $faker->dateTimeBetween($startDate, '+2 months');

            Project::create([
                'name'        => $faker->sentence(3),
                'description' => $faker->paragraph(3),
                'type'        => $faker->randomElement(['design', 'development', 'research']),
                'start_date'  => $startDate->format('Y-m-d'),
                'end_date'    => $endDate->format('Y-m-d'),
                'priority'    => $faker->randomElement(['high', 'medium', 'low']),
                'status'      => $faker->boolean(), // 0 or 1
            ]);
        }

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
