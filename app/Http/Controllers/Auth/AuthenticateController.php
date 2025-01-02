<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Response;

class AuthenticateController extends Controller
{
    public function show(): Response
    {
        return Inertia('Auth/Login');
    }

    public function store(Request $request)
    {

    }
}
