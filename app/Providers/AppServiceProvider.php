<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\CardObjectMain;
use App\Observers\CardObjectMainObserver;

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
        CardObjectMain::observe(CardObjectMainObserver::class);
    }
}
