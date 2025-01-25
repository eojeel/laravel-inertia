<?php

use Illuminate\Auth\Events\Registered;
use function Pest\Laravel\get;
use function Pest\Laravel\post;

it('can view the register form', function () {

    $response = get(route('register'));

    $response->assertStatus(200);

});

it('can create a user', function () {

    Event::fake([Registered::class]);

    $userData = [
        'name' => fake()->name,
        'email' => fake()->safeEmail,  // Use safeEmail for valid email format
        'password' => 'password123', // Use a specific password for confirmation
        'password_confirmation' => 'password123', // Add password_confirmation
    ];

    $response = Post(route('register'), $userData);

    $response->assertRedirect(route('home'));

    // 2. The user is created in the database
    $this->assertDatabaseHas('users', [
        'name' => $userData['name'],
        'email' => $userData['email'],
    ]);

    // 4. The Registered event is dispatched
    Event::assertDispatched(Registered::class, function ($event) use ($userData) {
        return $event->user->email === $userData['email'];
    });

    expect(Auth::check())->toBeTrue();
    expect(Auth::user()->name)->toBe($userData['name']);



});
