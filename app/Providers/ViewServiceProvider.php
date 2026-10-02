<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\NotificationLog;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {

            $notificationCount = 0;
            $latestNotifications = collect();

            if (Auth::check()) {

                $notificationCount = NotificationLog::where('user_id', Auth::id())
                    ->where('is_read', false)
                    ->count();

                $latestNotifications = NotificationLog::where('user_id', Auth::id())
                    ->latest()
                    ->take(5)
                    ->get();
            }

            $view->with([
                'notificationCount' => $notificationCount,
                'latestNotifications' => $latestNotifications,
            ]);
        });
    }
}