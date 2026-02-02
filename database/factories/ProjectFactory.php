<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class ProjectFactory extends Factory
{
    protected $model = Project::class;

    public function definition(): array
    {
        $startDate = Carbon::now()->subDays(rand(1, 10));
        $endDate   = (clone $startDate)->addDays(rand(5, 20));

        return [
            'name'        => $this->faker->sentence(3),
            'description' => $this->faker->paragraph,
            'type'        => $this->faker->randomElement([
                'web',
                'mobile',
                'desktop',
                'api'
            ]),
            'start_date'  => $startDate->toDateString(),
            'end_date'    => $endDate->toDateString(),
            'priority'    => $this->faker->randomElement([
                'high',
                'medium',
                'low'
            ]),
            'status'      => 1,
            'created_at'  => now(),
            'updated_at'  => now(),
        ];
    }
}
