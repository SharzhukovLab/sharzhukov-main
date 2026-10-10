<?php

namespace App\Providers;

use App\Models\UserProject;
use App\Observers\UserProjectObserver;
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
        UserProject::observe(UserProjectObserver::class);
        //
    }
}
