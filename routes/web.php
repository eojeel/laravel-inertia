<?php

use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'Home')->name('home');

Route::get('/register', [RegisterController::class, 'create'])->name('register');
