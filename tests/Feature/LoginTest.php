<?php

use App\Models\User;

use function Pest\Laravel\{actingAs, get, post};

it('can view the loging page', function() {

    get(route('login'))->assertStatus(200);
});

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

it('fails on incorrect email login attempt', function() {

    $response = post('/login', [
        'email' => fake()->email,
        'password' => fake()->password(8)
    ]);

    $response->assertSessionHasErrors('email');
});

it('can log a user out', function () {

    $user = User::factory()->create();

    $response = actingAs($user)
    ->get(route('logout'));
    $response->assertRedirect(route('home'));

    expect(Auth::user())->toBeNull();
});
