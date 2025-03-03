<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Inertia\Response;

final class DashboardController extends Controller
{
    public function index(): Response
    {
        return Inertia('Dashboard', []);
    }
}
