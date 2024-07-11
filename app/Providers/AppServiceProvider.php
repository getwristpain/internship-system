<?php

namespace App\Providers;

use App\Models\Journal;
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
        Journal::observe(JournalObserver::class);
    }
}
