<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;

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
            'image' => Storage::disk('s3')->url('images/default.jpg'),
            'approved' => 1,
        ];
    }
}
