<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

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
        try {
            // Check database connection
            DB::connection()->getPdo();
        } catch (\Exception $e) {
            // Show maintenance view if database is down
            abort(503, 'Service Unavailable');
        }

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
