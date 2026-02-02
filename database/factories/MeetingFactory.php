<?php

namespace Database\Factories;

use App\Models\Meeting;
use Illuminate\Database\Eloquent\Factories\Factory;

class MeetingFactory extends Factory
{
    protected $model = Meeting::class;

    public function definition(): array
    {
        return [
            'subject' => fake()->sentence(3),
            'note' => fake()->paragraph(),
            'date' => fake()->date(),
            'start_time' => fake()->time('H:i'),
            'end_time' => fake()->time('H:i'),
        ];
    }
}
