<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Listing>
 */
final class ListingFactory extends Factory
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
            'title' => fake()->sentence(),
            'description' => fake()->paragraph(),
            'tags' => fake()->word(),
            'image' => 'https://picsum.photos/seed/picsum/300/300',
            'approved' => 1,
        ];
    }
}
