<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Listing;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
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
            'listings' => $user->listing()
                ->filter(request(['search', 'approved']))
                ->latest()
                ->paginate(10)
                ->withQueryString(),
            'status' => session('status'),
        ]);
    }

    public function role(Request $request, User $user): RedirectResponse
    {
        Gate::authorize('modifyRole', $user);

        $attributes = $request->validate(['role' => 'string|required']);

        $user->update(['role' => $attributes['role']]);

        return redirect()
            ->route('admin.index')
            ->with('status', "User role changed to {$attributes['role']} successfully.");
    }

    public function toggleApproval(Listing $listing): RedirectResponse
    {
        Gate::authorize('approve', $listing);

        $listing->update(['approved' => ! $listing->approved]);

        return back()
            ->with('status', 'Listing status changed successfully.');
    }
}
