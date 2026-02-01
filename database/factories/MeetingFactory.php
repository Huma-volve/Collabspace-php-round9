<?php

namespace Database\Factories;

use App\Models\Meeting;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Meeting>
 */
class MeetingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model=Meeting::class;
    public function definition(): array
    {
        return [
                   'subject' => $this->faker->sentence,

        'date' => $this->faker->date(),
        'note' => $this->faker->paragraph(),
        'start_time' => $this->faker->time(),
        'end_time' => $this->faker->time(),
       //  'users' => User::factory()->count(1)->create()->pluck('id')->toArray(),
        // Add other fields as necessary
        
        ];
    }
}
