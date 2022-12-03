<?php

namespace App\Providers;

use Auth;
use \App\Http\Controllers\Controller;
use Illuminate\Support\ServiceProvider;

class ViewComposerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // Here we are registering a View Composer:
        // Alternative: view()->composer('partials.nav', '<ProcessingClass>');

        view()->composer('partials.nav', function($view)
        {
            // Is there a user logged in? Load a variable which will be universally
            // available to all views.
            $loggedInUser = Auth::user();
            $view->with('loggedInUser', $loggedInUser);
        });

        view()->composer('*', function($view){
            // Work out the current menu option by examining the current view name
            $menuOption = Controller::getActiveMenuOption($view->getName());
            view()->share('menuOption', $menuOption);
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
