<?php

namespace Database\Factories;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'full_name'        => $this->faker->name,
            'email'            => $this->faker->unique()->safeEmail,
            'email_verified_at'=> now(),
            'password'         => Hash::make('password'), // مهم
            'phone'            => $this->faker->optional()->phoneNumber,
            'image'            => 'https://static.vecteezy.com/system/resources/thumbnails/009/292/244/small/default-avatar-icon-of-social-media-user-vector.jpg',
            'job_title'        => $this->faker->optional()->jobTitle,
            'role'             => 'employee', // default
            'status'           => 1,
            'availability'     => 1,
            'about'            => $this->faker->optional()->paragraph,
            'experience'       => $this->faker->randomElement(['junior', 'mid', 'senior']), // ❗ NOT NULL
            'experience_year'  => $this->faker->optional()->numberBetween(1, 10),
            'team_id'          => null, // بيتحدد في tests لو محتاج
            'remember_token'   => Str::random(10),
            'created_at'       => now(),
            'updated_at'       => now(),
        ];
    }
}
