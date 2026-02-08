<?php

namespace Database\Factories;

use App\Models\Meeting;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class MeetingFactory extends Factory
{
    protected $model = Meeting::class;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Meeting>
 */
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
         'subject' => $this->faker->sentence,

        'date' => $this->faker->date(),
        'note' => $this->faker->paragraph(),
        'start_time' => $this->faker->time(),

        'duration' => $this->faker->numberBetween(30,50),
        'zoom_meeting_id' => null,
        'join_url' => null,
       
        //'end_time' => $this->faker->time(),
       //  'users' => User::factory()->count(1)->create()->pluck('id')->toArray(),
        // Add other fields as necessary
        
        ];
    }
}
