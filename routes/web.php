<?php

use Livewire\Volt\Volt;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AvatarController;

// Volt::route('/reset', 'pages.auth.reset');

Route::redirect('/', '/dashboard', 301);

Route::middleware(['auth'])->group(function () {
    Volt::route('/dashboard', 'pages.dashboard')->name('dashboard');
    Volt::route('/setting', 'pages.setting')
        ->name('setting');
    Volt::route('/profile', 'pages.users.profile')
        ->name('profile');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Volt::route('/admin-manager', 'pages.user-managements.manage-admins')->name('admin-manager');
});

Route::middleware(['auth', 'role:admin|staff'])->group(function () {
    Volt::route('/users-overview', 'pages.user-managements.users-overview')->name('users-overview');
    Volt::route('/user-manager', 'pages.user-managements.manage-users')->name('user-manager');
    Volt::route('/supervisor-manager', 'pages.user-managements.manage-supervisors')->name('supervisor-manager');
    Volt::route('/student-manager', 'pages.user-managements.manage-students')->name('student-manager');
    Volt::route('/teacher-manager', 'pages.user-managements.manage-teachers')->name('teacher-manager');
});

Route::middleware(['auth', 'role:student'])->group(function () {
    Volt::route('/journals', 'pages.students.manage-journal')
        ->name('student-journal');
    Volt::route('/mentorships', 'pages.students.manage-mentorship')
        ->name('student-mentorship');
    Volt::route('/assignments', 'pages.students.manage-assignment')
        ->name('student-assignment');
});

Route::middleware(['auth', 'role:teacher'])->group(function () {
    //
});

Route::middleware(['auth', 'role:supervisor'])->group(function () {
    //
});

require __DIR__ . '/auth.php';
