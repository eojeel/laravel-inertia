<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Response;

class RegisterController extends Controller
{
    public function show(): Response
    {
        return Inertia('Auth/Register');
    }

    public function store(Request $request): RedirectResponse
    {
        $attributes = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
            'password_confirmation' => 'required|same:password',
        ]);

        $user = User::create($attributes);

        Auth::login($user);

        return redirect()->route('home');

    }
}
