<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Journal;
use App\Observers\UserObserver;
use App\Observers\JournalObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        User::observe(UserObserver::class);
        Journal::observe(JournalObserver::class);
    }
}
