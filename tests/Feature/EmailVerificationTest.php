<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;

use function Pest\Laravel\actingAs;

it('can view email vierification page', function () {

    $user = User::factory()->create(['email_verified_at' => null]);

    $response = actingAs($user)
        ->get(route('verification.notice'));

    $response->assertStatus(200);

});

it('email verification link can be resent', function () {
    Notification::fake();

    $user = User::factory()->create();

    $response = actingAs($user)
        ->post('/email/verification-notification');

    $response->assertRedirect();
    $response->assertSessionHas('message', 'Verification link sent!');
    Notification::assertSentTo($user, VerifyEmail::class);
});

it('email verification can be handled', function () {

    $user = User::factory()->create(['email_verified_at' => null]);

    $verificationUrl = URL::temporarySignedRoute(
        'verification.verify',
        now()->addMinutes(60),
        ['id' => $user->id, 'hash' => sha1($user->email)]
    );

    actingAs($user)->get($verificationUrl);

    expect($user->fresh()->hasVerifiedEmail())->toBeTrue();
});
