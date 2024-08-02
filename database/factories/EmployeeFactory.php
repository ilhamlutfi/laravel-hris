<?php

namespace Database\Factories;

use App\Models\Role;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'team_id' => Team::all()->random()->id,
            'role_id' => Role::all()->random()->id,
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'gender' => fake()->randomElement(['male', 'female']),
            'age' => fake()->numberBetween(18, 60),
            'phone' => fake()->phoneNumber(),
            'photo' => fake()->imageUrl(640, 480),
            'is_verified' => fake()->boolean(),
            'verified_at' => now(),
        ];
    }
}
