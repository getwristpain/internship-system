<?php

use Livewire\Volt\Volt;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AvatarController;

// Volt::route('/reset', 'pages.auth.reset');

Route::redirect('/', '/dashboard', 301);
Route::get('assets/uploads/avatars/{userId}/{filename}', [AvatarController::class, 'show'])->middleware('auth')->name('assets.avatar');

require __DIR__ . '/api.php';
require __DIR__ . '/auth.php';
require __DIR__ . '/app.php';
