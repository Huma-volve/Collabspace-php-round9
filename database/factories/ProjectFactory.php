<?php

namespace Database\Factories;

<<<<<<< HEAD
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
=======
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
>>>>>>> eb1bad058b5eb4d8b6aa8f300c223845fdeff7a2
        ];
    }
}
