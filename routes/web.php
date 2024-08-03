<?php

use App\Http\Controllers\AvatarController;
use Livewire\Volt\Volt;
use Illuminate\Support\Facades\Route;

// Volt::route('/reset', 'pages.auth.reset');

Route::redirect('/', '/login', 301);
Route::get('assets/uploads/avatars/{userId}/{filename}', [AvatarController::class, 'show'])->middleware('auth')->name('assets.avatar');

require __DIR__ . '/auth.php';
require __DIR__ . '/app.php';
