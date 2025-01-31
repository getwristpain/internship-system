<?php

use Livewire\Volt\Volt;
use Illuminate\Support\Facades\Route;

/**
 * ================================================
 * Global Routes
 * ================================================
 * - Berisi rute yang berlaku untuk semua pengguna
 *   (tanpa atau sebelum proses autentikasi).
 */

// Volt::route('/reset', 'pages.auth.reset');
Route::middleware(['is_installed'])->get('/', function () {
    return redirect(route('dashboard'));
});

Route::middleware(['is_installed'])->prefix('/install')->group(function () {
    Volt::route('/', 'pages.installations.install-start')->name('install');
    Volt::route('/next/1', 'pages.installations.configure-school')->name('install.step1');
    Volt::route('/next/2', 'pages.installations.configure-departments')->name('install.step2');
    Volt::route('/next/3', 'pages.installations.configure-admin')->name('install.step3');
    Volt::route('/finish', 'pages.installations.install-finish')->name('install.finish');
});

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
        Volt::route('/overview', 'pages.internships.overview')->name('internship-overview');
        Volt::route('/programs', 'pages.internships.programs')->name('internship-programs');
    });

    // User Management
    Route::prefix('/users')->group(function () {
        Volt::route('/overview', 'pages.user-managements.overview')->name('users-overview');
        Volt::route('/all', 'pages.user-managements.users')->name('user-manager');
        Volt::route('/supervisors', 'pages.user-managements.supervisors')->name('supervisor-manager');
        Volt::route('/students', 'pages.user-managements.students')->name('student-manager');
        Volt::route('/teachers', 'pages.user-managements.teachers')->name('teacher-manager');
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
