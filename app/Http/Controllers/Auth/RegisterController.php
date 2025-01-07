<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Inertia\Response;

class RegisterController extends Controller
{
    public function show(): Response
    {
        return Inertia('Auth/Register');
    }

    public function store(RegisterRequest $request): RedirectResponse
    {
        $attributes = $request->validated();

        $user = User::create($attributes);

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('home');

    }
}
