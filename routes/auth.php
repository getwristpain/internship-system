<?php

use Livewire\Volt\Volt;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\VerifyEmailController;


Route::middleware('guest')->group(function () {
    // Register
    Volt::route('register', 'auth.register.register')
        ->name('register');

    // Login
    Volt::route('login', 'auth.login.login')
        ->name('login');

    // Company Login
    Volt::route('company/login', 'auth.login.c-login')
        ->name('login.company');

    // Forgot Password
    Volt::route('forgot-password', 'auth.recovery.forgot-password')
        ->name('password.request');

    // Reset Password
    Volt::route('reset-password/{token}', 'auth.recovery.reset-password')
        ->name('password.reset');
});

Route::middleware('auth')->group(function () {
    // Register Multi-steps
    Route::prefix('/register/user/step')->group(function () {
        Volt::route('two', 'auth.register.step-account')
            ->name('register.steptwo');
    });

    Volt::route('verify-email', 'auth.verify.verify-email')
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Volt::route('confirm-password', 'auth.verify.confirm-password')
        ->name('password.confirm');
});
