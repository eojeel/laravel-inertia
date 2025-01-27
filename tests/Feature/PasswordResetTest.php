<?php

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\withoutExceptionHandling;

it('can view the password reset page', function () {

get(route('password.request'))->assertStatus(200);

    });

it('can send the rest password email', function () {

    $user = User::factory()->create();

    $response = post(route('password.email'), [
        'email' => $user->email,
    ]);

    $response->assertRedirect()
        ->assertSessionHas('status', __(Password::RESET_LINK_SENT));
});

it('cannot send the rest password email', function () {

    $response = post(route('password.email'), [
        'email' => fake()->email,
    ]);

    $response->assertRedirect()
        ->assertSessionHasErrors('email');
});

it('can reset view reset page with token', function () {

    $user = User::factory()->create();

    $token = Password::broker()->createToken($user);

    $response = get(route('password.reset', ['token' => $token]));

    $response->assertStatus(200);

});

it('can reset a password', function () {

    withoutExceptionHandling();

    Event::fake([PasswordReset::class]);

    $user = User::factory()->create();

    $token = Password::broker()->createToken($user);

    $response = ActingAs($user)
        ->post(route('password.update', [
            'token' => $token,
            'email' => $user->email,
            'password' => $password = fake()->password,
        ]));

    // Assert that the PasswordReset event was dispatched for the correct user.
    Event::assertDispatched(PasswordReset::class, function ($event) use ($user) {
        return $event->user->id === $user->id;
    });

    $response->assertRedirect(route('login'))
        ->assertSessionHas('status', __(Password::PASSWORD_RESET));

    // Assert the user's password was actually updated
    $this->assertDatabaseHas('users', [
        'email' => $user->email,
    ]);
    $this->assertTrue(Hash::check($password, $user->fresh()->password));
});
