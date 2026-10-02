<?php

namespace App\Providers;

use App\Models\NotificationLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
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
        // Share the notification bell badge + dropdown feed with EVERY page
        // that uses layouts.app (not just the dashboard). Without this,
        // $layoutNotificationCount / $layoutLatestNotifications are undefined
        // on /tasks, /tasks/create, /notifications, etc. and the page errors out.
        View::composer('layouts.app', function ($view) {
            $userId = Auth::id();

            if (!$userId) {
                $view->with('layoutNotificationCount', 0);
                $view->with('layoutLatestNotifications', collect());
                return;
            }

            $view->with(
                'layoutNotificationCount',
                NotificationLog::where('user_id', $userId)
                    ->where('is_read', false)
                    ->count()
            );

            $view->with(
                'layoutLatestNotifications',
                NotificationLog::where('user_id', $userId)
                    ->latest()
                    ->take(5)
                    ->get()
            );
        });
    }
}
