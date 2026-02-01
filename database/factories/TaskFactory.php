<?php

namespace Database\Factories;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition(): array
    {
        $start = Carbon::now()->subDays(rand(1, 5));
        $end   = (clone $start)->addDays(rand(1, 10));

        return [
            'project_id'  => Project::factory(), // ✅ إجباري
            'name'        => $this->faker->sentence(3),
            'description' => $this->faker->paragraph, 
            'status'      => 'todo',
            'start_date'  => $start->toDateString(),
            'end_date'    => $end->toDateString(),
            'created_at'  => now(),
            'updated_at'  => now(),
        ];
    }
}
