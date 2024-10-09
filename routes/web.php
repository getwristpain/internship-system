<?php

use Livewire\Volt\Volt;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AvatarController;

// Volt::route('/reset', 'pages.auth.reset');

Route::redirect('/', '/dashboard', 301);
Route::get('assets/uploads/avatars/{userId}/{filename}', [AvatarController::class, 'show'])->middleware('auth')->name('assets.avatar');

Route::middleware(['auth'])->group(function () {
    Volt::route('/dashboard', 'pages.dashboard')->name('dashboard');
    Volt::route('/setting', 'pages.setting')
        ->name('setting');
    Volt::route('profile', 'pages.profile')
        ->name('profile');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Volt::route('/admin-manager', 'user-manager.manage-admins')->name('admin-manager');
});

Route::middleware(['auth', 'role:admin|staff'])->group(function () {
    Volt::route('/users-overview', 'user-manager.users-overview')->name('users-overview');
    Volt::route('/user-manager', 'user-manager.manage-users')->name('user-manager');
    Volt::route('/supervisor-manager', 'user-manager.manage-supervisors')->name('supervisor-manager');
    Volt::route('/student-manager', 'user-manager.manage-students')->name('student-manager');
    Volt::route('/teacher-manager', 'user-manager.manage-teachers')->name('teacher-manager');
});

Route::middleware(['auth', 'role:student'])->group(function () {
    Volt::route('/attendance', 'pages.internships.attendance')
        ->name('student.attendance');
});

Route::middleware(['auth', 'role:teacher'])->group(function () {
    //
});

Route::middleware(['auth', 'role:supervisor'])->group(function () {
    //
});

require __DIR__ . '/auth.php';
