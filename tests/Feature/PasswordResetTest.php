<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

use function Pest\Laravel\get;
use function Pest\Laravel\post;

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

    Event::fake([PasswordReset::class]);

    $user = User::factory()->create();

    $token = Password::broker()->createToken($user);

    $response = post(route('password.update'), [
        'token' => $token,
        'email' => $user->email,
        'password' => $password = fake()->password(8),
        'password_confirmation' => $password,
    ]);

    $response->assertRedirect(route('login'))
        ->assertSessionHas('status', __(Password::PASSWORD_RESET));

    // Assert that the PasswordReset event was dispatched for the correct user.
    Event::assertDispatched(PasswordReset::class, static function ($event) use ($user) {
        return $event->user->id === $user->id;
    });

    // Assert the user's password was actually updated
    $this->assertDatabaseHas('users', [
        'email' => $user->email,
    ]);
    $this->assertTrue(Hash::check($password, $user->fresh()->password));
});
