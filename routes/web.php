<?php

use Livewire\Volt\Volt;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AvatarController;

// Volt::route('/reset', 'pages.auth.reset');

/**
 * ================================================
 * Global Routes
 * ================================================
 * - Berisi rute yang berlaku untuk semua pengguna
 *   (tanpa atau sebelum proses autentikasi).
 */
Route::redirect('/', '/dashboard', 301);

/**
 * ================================================
 * Authenticated User Routes
 * ================================================
 * - Rute yang memerlukan autentikasi pengguna.
 * - Tersedia untuk semua pengguna terautentikasi.
 */
Route::middleware(['auth'])->group(function () {
    Volt::route('/dashboard', 'pages.dashboard')->name('dashboard');
    Volt::route('/setting', 'pages.setting')->name('setting');
    Volt::route('/profile', 'pages.users.profile')->name('profile');
});

/**
 * ================================================
 * Admin-Specific Routes
 * ================================================
 * - Rute yang hanya dapat diakses oleh pengguna dengan
 *   peran admin.
 */
Route::middleware(['auth', 'role:admin'])->group(function () {
    Volt::route('/admins', 'pages.user-managements.manage-admins')->name('admin-manager');
});

/**
 * ================================================
 * Admin and Staff Shared Routes
 * ================================================
 * - Rute yang dapat diakses oleh pengguna dengan peran
 *   admin atau staff.
 */
Route::middleware(['auth', 'role:admin|staff'])->group(function () {
    // Internship Management
    Route::prefix('/internships')->group(function () {
        Volt::route('/programs', 'pages.internships.manage-program')->name('internship-program');
    });

    // User Management
    Route::prefix('/users')->group(function () {
        Volt::route('/overview', 'pages.user-managements.users-overview')->name('users-overview');
        Volt::route('/all', 'pages.user-managements.manage-users')->name('user-manager');
        Volt::route('/supervisors', 'pages.user-managements.manage-supervisors')->name('supervisor-manager');
        Volt::route('/students', 'pages.user-managements.manage-students')->name('student-manager');
        Volt::route('/teachers', 'pages.user-managements.manage-teachers')->name('teacher-manager');
    });
});

/**
 * ================================================
 * Student-Specific Routes
 * ================================================
 * - Rute yang hanya dapat diakses oleh pengguna dengan
 *   peran student.
 */
Route::middleware(['auth', 'role:student'])->group(function () {
    Route::prefix('/students')->group(function () {
        Volt::route('/journals', 'pages.students.manage-journal')->name('student-journal');
        Volt::route('/mentorships', 'pages.students.manage-mentorship')->name('student-mentorship');
        Volt::route('/assignments', 'pages.students.manage-assignment')->name('student-assignment');
    });
});

/**
 * ================================================
 * Teacher-Specific Routes
 * ================================================
 * - Rute yang hanya dapat diakses oleh pengguna dengan
 *   peran teacher.
 */
Route::middleware(['auth', 'role:teacher'])->group(function () {
    // Tambahkan rute untuk teacher di sini.
});

/**
 * ================================================
 * Supervisor-Specific Routes
 * ================================================
 * - Rute yang hanya dapat diakses oleh pengguna dengan
 *   peran supervisor.
 */
Route::middleware(['auth', 'role:supervisor'])->group(function () {
    // Tambahkan rute untuk supervisor di sini.
});

/**
 * ================================================
 * Authentication Routes
 * ================================================
 * - Rute yang menangani proses autentikasi pengguna.
 */
require __DIR__ . '/auth.php';
