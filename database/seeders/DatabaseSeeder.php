<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Listing;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

final class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'John Doe',
            'email' => 'test@email.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        User::factory(6)->create();
        Listing::factory(50)->create();
    }
}
