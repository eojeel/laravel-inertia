<?php

use App\Models\User;

use function Pest\Laravel\actingAs;

it('can vew the confirm password page', function () {
    $user = User::factory()->create();

    $response = ActingAs($user)
        ->get(route('password.confirm'));

    $response->assertStatus(200);

});

it('can confirm a users password', function () {
    $password = fake()->password;

    $user = User::factory()->create(['password' => Hash::make($password)]);

    $response = ActingAs($user)
        ->post(route('password.confirm'), [
            'password' => $password,
        ]);

    $response->assertRedirect(route('home'));

});

it('fails password confirmation', function () {
    $user = User::factory()->create();

    $response = ActingAs($user)
        ->post(route('password.confirm'), [
            'password' => fake()->password,
        ]);

    $response->assertSessionHasErrors(['password']);

});
