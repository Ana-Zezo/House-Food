<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Notification;

class ViewServiceProvider extends ServiceProvider
{
    public function boot()
    {

        View::composer('*', function ($view) {

            $notifications = Notification::orderBy('created_at', 'desc')->limit(5)->get();


            $unreadCount = Notification::where('is_read', false)->count();


            $view->with([
                'headerNotifications' => $notifications,
                'unreadCount' => $unreadCount,
            ]);
        });
    }

    public function register()
    {
        //
    }
}
