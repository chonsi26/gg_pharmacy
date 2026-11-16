<?php

namespace App\Providers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    
    /**
     * Register any application services.
     */
    public function register(): void
    {

    }
    
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('send-sms', fn (User $user, Order $order) => $user->is_staff);
    }
}
