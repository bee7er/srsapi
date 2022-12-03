<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Something that gets called AFTER ALL the service providers have been registered
        // Once we are completely ready to go THEN call the boot method on all these providers.

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Here we bind things into Laravel's IOC (Inversion of Control) container
        // "Every time I ask for this specific class I want Laravel to build it for
        // me in this specific fashion..."


    }
}
