<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ResetRequest;
use Inertia\Inertia;
use Inertia\Response;
use Password;

class ResetPasswordController extends Controller
{
    public function show(): Response
    {
        return Inertia::render('Auth/ResetPassword', [
            'status' => session('status'),
        ]);
    }

    public function email(ResetRequest $request): Response
    {
        $request->validated();

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::ResetLinkSent
            ? back()->with(['status' => __($status)])
            : back()->withErrors(['email' => __($status)]);
    }

    public function reset(string $token): Response
    {
        return Inertia('auth.reset-password', ['token' => $token]);
    }
}
