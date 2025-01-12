<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Response;

class ConfirmPasswordController extends Controller
{
    public function create(): Response
    {
        return Inertia('Auth/ConfirmPassword');
    }

    public function store(Request $request): RedirectResponse
    {
        if (! Hash::check($request->password, $request->user()->password)) {
            return back()->withErrors([
                'password' => 'Wrong password',
            ]);
        }

        $request->session()->passwordConfirmed();

        return redirect()->intended();
    }
}
