<?php

use App\Http\Controllers\Auth\AuthenticateController;
use App\Http\Controllers\Auth\ConfirmPasswordController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'show'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);

    Route::get('/login', [AuthenticateController::class, 'show'])->name('login');
    Route::post('/login', [AuthenticateController::class, 'store']);

    Route::get('/forgot-password', [ResetPasswordController::class, 'show'])->name('password.request');
    Route::post('/forgot-password', [ResetPasswordController::class, 'email'])->name('password.email');
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'reset'])->name('password.reset');
    Route::Post('/reset-password', [ResetPasswordController::class, 'update'])->name('password.update');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['verified'])->name('dashboard');

    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'edit')->name('profile.edit');
        Route::patch('/profile', 'update')->name('profile.update');
        route::put('/profile', 'updatePassword')->name('profile.updatePassword');
    });

    Route::controller(EmailVerificationController::class)->group(function () {
        Route::get('/email/verify', 'notice')->name('verification.notice');
        Route::get('/email/verify/{id}/{hash}', 'handler')->middleware(['signed'])->name('verification.verify');
        Route::post('/email/verification-notification', 'resend')->middleware(['throttle:6,1'])->name('verification.send');
    });

    Route::controller(ConfirmPasswordController::class)->group(function () {
        Route::get('/confirm-password', 'create')->name('password.confirm');
        Route::post('confirm-password', 'store')->middleware(['throttle:6,1'])->name('password.confirm');
    });

    Route::get('/logout', [AuthenticateController::class, 'logout'])->name('logout');

});

Route::inertia('/', 'Home')->name('home');
