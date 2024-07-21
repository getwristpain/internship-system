<?php

use Livewire\Volt\Volt;
use Illuminate\Support\Facades\Route;

// Volt::route('/reset', 'pages.auth.reset');

Route::redirect('/', '/dashboard', 301);

Route::middleware(['auth', 'verified', 'role:owner,admin,department-staff,teacher,student,supervisor'])->group(function () {
    Route::view('/dashboard', 'dashboard')->name('dashboard');
});

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__ . '/auth.php';
require __DIR__ . '/internships/student.php';
