<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $start_date=fake()->dateTimeBetween('now','+1 month');
        $end_date=fake()->dateTimeBetween($start_date,$start_date->format('Y-m-d') . ' +6 months');
        return [
            'name'=>fake()->name(),
            'description'=>fake()->sentence(6),
            'type'=>fake()->sentence(1),
            'start_date'=>$start_date->format('Y-m-d'),
            'end_date'=>$end_date->format('Y-m-d'),
            'priority'=>fake()->randomElement(['high','medium','low']),
            'status'=>fake()->boolean()
        ];
    }
}
