<?php

use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseMissing;

it('can view the profile page', function () {

    $user = User::Factory()->create();

    actingAs($user)
        ->get(route('profile.edit'))
        ->assertStatus(200);
});

it('can update name and show updated name', function () {

    $user = User::Factory()->create();

    actingAs($user)
        ->patch(route('profile.update'), [
            'name' => $name = fake()->name(),
            'email' => $user->email,
        ]);

    $user->refresh();

    expect($user->name)->toBe($name);

});

it('can update email and resets verified email', function() {

    $user = User::Factory()->create();

    actingAs($user)
        ->patch(route('profile.update'), [
            'name' => $user->name,
            'email' => fake()->email,
        ]);

    $user->refresh();

    expect($user->email_verified_at)->toBe(null);
});

it('can update name and show notification', function () {

    $user = User::Factory()->create();

    actingAs($user)
        ->patch(route('profile.update'), [
            'name' => $name = fake()->name(),
            'email' => $user->email,
        ]);

    expect(session('status'))->toBe('Profile updated.');

});

it('can update password and show notification', function () {

    $user = User::Factory()->create(['password' => bcrypt('password')]);

    actingAs($user)
        ->put(route('profile.updatePassword'), [
            'current_password' => 'password',
            'new_password' => $password = fake()->password(8),
            'new_password_confirmation' => $password,
        ]);

    expect(session('status'))->toBe('Password updated.');

});

it('can delete a user', function() {

    $user = User::Factory()->create(['password' => bcrypt('password')]);

    actingAs($user)
        ->delete(route('profile.destroy'), [
            'password' => 'password',
        ]);

    assertDatabaseMissing('users', ['id' => $user->uid]);

});
