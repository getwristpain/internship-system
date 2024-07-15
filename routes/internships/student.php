<?php

use Livewire\Volt\Volt;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->prefix('/s')->group(function () {
    Volt::route('/registration', 'internships.registration')
        ->name('registration');
});
