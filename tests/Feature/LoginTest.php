<?php

use App\Models\User;

use function Pest\Laravel\post;

it('a user can login', function () {

    // 1. Create a user using a factory
    $user = User::factory()->create([
        'password' => bcrypt('password'),
    ]);

    // 2. Visit the login page and submit the form
    $response = post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $response->assertRedirect(route('dashboard'));
});
