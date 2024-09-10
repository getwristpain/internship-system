<?php

use Livewire\Volt\Volt;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Volt::route('/dashboard', 'pages.dashboard')->name('dashboard');
    Volt::route('/setting', 'pages.setting')
        ->name('setting');
    Volt::route('profile', 'pages.profile')
        ->name('profile');
});

Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Volt::route('/user-manager', 'user-manager.manage-user')->name('user-manager');
});

Route::middleware(['auth', 'verified', 'role:student'])->group(function () {
    Volt::route('/registration', 'pages.internships.registration')
        ->name('registration');
});

Route::middleware(['auth', 'verified', 'role:teacher'])->group(function () {
    //
});

Route::middleware(['auth', 'verified', 'role:supervisor'])->group(function () {
    //
});
