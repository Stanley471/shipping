<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Shipment;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

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
        Gate::policy(Shipment::class, \App\Policies\ShipmentPolicy::class);
        
        // Rate limiter for web tracking form (existing)
        RateLimiter::for('tracking', function (Request $request) {
            return Limit::perMinute(10)->by($request->ip());
        });
        
        // Rate limiter for public API tracking (more strict)
        // Limits to 30 requests per minute per IP
        RateLimiter::for('public-tracking', function (Request $request) {
            return Limit::perMinute(30)->by($request->ip());
        });
    }
}
