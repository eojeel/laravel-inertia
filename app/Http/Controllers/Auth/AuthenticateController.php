<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Inertia\Response;

class AuthenticateController extends Controller
{
    public function show(): Response
    {
        return Inertia('Auth/Login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $attributes = $request->validated();

        if (Auth::attempt($attributes, $request->remember)) {
            $request->session()->regenerate();

            return redirect()->intended();
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])
            ->onlyInput('email');
    }
}
