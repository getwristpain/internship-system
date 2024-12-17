<?php

use Livewire\Volt\Volt;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\VerifyEmailController;


Route::middleware('guest')->group(function () {
    // Login
    Volt::route('login', 'pages.auth.login.login')
        ->name('login');

    // Register
    Volt::route('register', 'pages.auth.register.register')
        ->name('register');

    // Company Login
    Volt::route('c/login', 'pages.auth.login.c-login')
        ->name('company.login');

    // Forgot Password
    Volt::route('forgot-password', 'pages.auth.recovery.forgot-password')
        ->name('password.request');

    // Reset Password
    Volt::route('reset-password/{token}', 'pages.auth.recovery.reset-password')
        ->name('password.reset');
});

Route::middleware('auth')->group(function () {
    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Volt::route('confirm-password', 'pages.auth.verify.confirm-password')
        ->name('password.confirm');
});
