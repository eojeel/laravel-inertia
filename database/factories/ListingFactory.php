<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Listing>
 */
class ListingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        return [
            'user_id' => User::factory()->create(),
            'title' => fake()->word(),
            'description' => fake()->paragraph(),
            'tags' => fake()->word(),
            'image' => fake()->imageUrl(),
            'approved' => 1,
        ];
    }
}
