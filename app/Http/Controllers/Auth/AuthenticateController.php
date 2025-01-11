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
        return Inertia('Auth/Login', [
            'status' => session('status'),
        ]);
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $attributes = $request->validated();

        if (Auth::attempt($attributes, $request->remember ?? false)) {
            $request->session()->regenerate();

            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(): RedirectResponse
    {
        Auth::logout();

        return redirect()->route('home');
    }
}
