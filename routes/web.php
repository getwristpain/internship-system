<?php

use Livewire\Volt\Volt;
use Illuminate\Support\Facades\Route;

// Volt::route('/reset', 'pages.auth.reset');

Route::redirect('/', '/login', 301);

require __DIR__ . '/auth.php';
require __DIR__ . '/app.php';