<?php

namespace App\Providers;

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
     * NOTE: added Bootstrap any application services.
     */
    // public function boot(): void
    // {
    //     // NOTE: added this. 
    //     // if ($this->app->environment('production')) {
    //     //     URL::forceScheme('https');
    //     // }
    //     // NOTE: added this too
    //     Route::bind('todo', function ($value) {
    //         return auth()->user()->todos()->findOrFail($value);
    //     });
    // }
}
