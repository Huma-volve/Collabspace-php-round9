<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Project;
use Faker\Factory as Faker;

class ProjectSeeder extends Seeder
{
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
    }
}
