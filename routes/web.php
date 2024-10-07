<?php

use Livewire\Volt\Volt;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AvatarController;

// Volt::route('/reset', 'pages.auth.reset');

Route::redirect('/', '/dashboard', 301);
Route::get('assets/uploads/avatars/{userId}/{filename}', [AvatarController::class, 'show'])->middleware('auth')->name('assets.avatar');

Route::middleware(['auth', 'verified'])->group(function () {
    Volt::route('/dashboard', 'pages.dashboard')->name('dashboard');
    Volt::route('/setting', 'pages.setting')
        ->name('setting');
    Volt::route('profile', 'pages.profile')
        ->name('profile');
});

Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Volt::route('/admin-manager', 'user-manager.manage-admins')->name('admin-manager');
});

Route::middleware(['auth', 'verified', 'role:admin|staff'])->group(function () {
    Volt::route('/users-overview', 'user-manager.users-overview')->name('users-overview');
    Volt::route('/user-manager', 'user-manager.manage-users')->name('user-manager');
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

require __DIR__ . '/auth.php';
