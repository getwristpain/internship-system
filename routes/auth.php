<?php

use Livewire\Volt\Volt;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\VerifyEmailController;

Route::middleware('guest')->group(function () {
    // Register
    Volt::route('register', 'pages.auth.register')
        ->name('register');

    // Register Multi-steps
    Route::prefix('/auth/u/step')->group(function () {
        Volt::route('two', 'pages.auth.register.step-account')
            ->name('register.account');
    });

    // Login
    Volt::route('login', 'pages.auth.login')
        ->name('login');

    // Company Login
    Volt::route('c/login', 'pages.auth.c-login')
        ->name('login.company');

    // Forgot Password
    Volt::route('forgot-password', 'pages.auth.forgot-password')
        ->name('password.request');

    // Reset Password
    Volt::route('reset-password/{token}', 'pages.auth.reset-password')
        ->name('password.reset');
});

Route::middleware('auth')->group(function () {
    Volt::route('auth/u/step/three', 'pages.auth.register.step-profile')
        ->name('register.profile');
    Volt::route('verify-email', 'pages.auth.verify-email')
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Volt::route('confirm-password', 'pages.auth.confirm-password')
        ->name('password.confirm');
});
