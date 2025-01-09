<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class EmailVerificationController extends Controller
{
    public function notice(): Response
    {
        return Inertia::render('Auth/VerifyEmail', [
            'message' => session('message'),
        ]);
    }

    public function handler(EmailVerificationRequest $request): RedirectResponse
    {
        $request->fulfill();

        return redirect('home');
    }

    public function resend(Request $request): RedirectResponse
    {
        $request->user()->sendEmailVerificationNotification();

        return back()->with('message', 'Verification link sent!');
    }
}
