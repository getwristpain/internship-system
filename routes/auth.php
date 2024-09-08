<?php

use Livewire\Volt\Volt;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\VerifyEmailController;


Route::middleware('guest')->group(function () {
    // Register
    Volt::route('register', 'pages.auth.register')
        ->name('register');

    // Login
    Volt::route('login', 'pages.auth.login')
        ->name('login');

    // Company Login
    Volt::route('company/login', 'pages.auth.c-login')
        ->name('login.company');

    // Forgot Password
    Volt::route('forgot-password', 'pages.auth.forgot-password')
        ->name('password.request');

    // Reset Password
    Volt::route('reset-password/{token}', 'pages.auth.reset-password')
        ->name('password.reset');
});

Route::middleware('auth')->group(function () {
    // Register Multi-steps
    Route::prefix('/register/user/step')->group(function () {
        Volt::route('two', 'pages.auth.register.step-account')
            ->name('register.steptwo');
    });

    Volt::route('verify-email', 'pages.auth.verify-email')
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Volt::route('confirm-password', 'pages.auth.confirm-password')
        ->name('password.confirm');
});
