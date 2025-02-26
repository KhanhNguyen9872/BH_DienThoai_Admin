<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;

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
        // Share notifications with the header partial (resources/views/partials/header.blade.php)
        View::composer('partials.header', function ($view) {
            // Retrieve notifications from the 'notifications' table, ordered by time descending
            $notifications = DB::table('notifications')
                ->orderBy('time', 'desc')
                ->get();

            $view->with('notifications', $notifications);
        });
    }
}
