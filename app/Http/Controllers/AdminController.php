<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

final class AdminController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/AdminDashboard', [
            'users' => User::with('listing')
                ->filter(request(['search', 'role']))
                ->paginate(10)
                ->withQueryString(),
        ]);
    }

    public function show(User $user): Response
    {
        return Inertia::render('Admin/User', [
            'user' => $user,
            'listings' => $user->listing()->latest()->paginate(10),
        ]);
    }

    public function role(Request $request, User $user): RedirectResponse
    {
        $attributes = $request->validate(['role' => 'string|required']);

        $user->update(['role' => $attributes['role']]);

        return redirect()
            ->route('admin.index')
            ->with('status', "User role changed to {$attributes['role']} successfully.");
    }
}
