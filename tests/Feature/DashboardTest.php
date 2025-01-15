<?php

use App\Models\User;

it('verified user can view dashboard', function () {

    $user = User::Factory()->create();

    $this->actingAs($user);

    $response = $this->get(route('dashboard'));

    $response->assertStatus(200);
});

it('unverified user cannot view dashboard', function () {

    $user = User::Factory()->create(['email_verified_at' => null]);

    $this->actingAs($user);

    $response = $this->get(route('dashboard'));

    $response->assertRedirect(route('login'));
});
