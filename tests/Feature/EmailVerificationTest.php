<?php

use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use function Pest\Laravel\{get, post, actingAs};

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
    Notification::fake();

    $user = User::factory()->create();

    // Simulate sending the verification email
    $notification = new VerifyEmail();
    $notification->createUrlUsing(function ($notifiable) {
        return 'http://example.com/verify?id='. $notifiable->getKey(). '&hash='. sha1($notifiable->getEmailForVerification());
    });
    $user->notify($notification);

    // Get the verification URL from the notification
    $verificationUrl = $user->notifications->first()->data['actionUrl'];

    // Visit the verification URL
    $response = get($verificationUrl);

    $response->assertRedirect('home');
    $this->assertTrue($user->fresh()->hasVerifiedEmail());
});
