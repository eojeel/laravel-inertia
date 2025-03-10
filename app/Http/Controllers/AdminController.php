<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;

final class AdminController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/AdminDashboard', [
            'users' => User::with('listing')->paginate(10),
        ]);
    }
}
