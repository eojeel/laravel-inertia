<?php

use App\Models\User;
use function Pest\Laravel\{patch, put};

it('can update name and show name', function() {

    $user = User::Factory()->create();

    $this->actingAs($user);

    patch(route('profile.update'), [
        'name' => $name = fake()->name(),
        'email' => $user->email,
    ]);

    $user->refresh();

    expect($user->name)->toBe($name);

});

it('can update name and show notification', function() {

    $user = User::Factory()->create();

    $this->actingAs($user);

    patch(route('profile.update'), [
        'name' => $name = fake()->name(),
        'email' => $user->email,
    ]);

    $user->refresh();

    expect(session('status'))->toBe('Profile updated.');

});

it('can update password and show notification', function() {

    $user = User::Factory()->create(['password' => bcrypt('password')]);

    $this->actingAs($user);

    $res = put(route('profile.updatePassword'), [
        'current_password' => 'password',
        'new_password' => $password = fake()->password,
        'new_password_confirmation' => $password,
    ]);

    expect(session('status'))->toBe('Password updated.');

});
