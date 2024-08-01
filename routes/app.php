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

Route::middleware(['auth', 'verified', 'role:Owner|Admin|Staff'])->group(function () {
    //
});

Route::middleware(['auth', 'verified', 'role:Student'])->group(function () {
    Volt::route('/registration', 'pages.internships.registration')
        ->name('registration');
});

Route::middleware(['auth', 'verified', 'role:Teacher'])->group(function () {
    //
});

Route::middleware(['auth', 'verified', 'role:Supervisor'])->group(function () {
    //
});
