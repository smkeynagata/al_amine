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
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Force HTTPS in production (Railway, etc.)
        if (config('app.env') === 'production') {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        // Trust all proxies (for Railway and other cloud platforms)
        $this->app['request']->server->set('HTTPS', 'on');

        // Configure trusted proxies for cloud platforms
        if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        if (config('app.env') === 'production') {
            // Configure Vite for production
            \Illuminate\Support\Facades\Vite::useScriptTagAttributes([
                'defer' => true,
            ]);

            // Add preloading for critical assets
            \Illuminate\Support\Facades\Vite::usePreloadTag();

            // Add Content Security Policy nonce
            \Illuminate\Support\Facades\Vite::useCspNonce();

            // Make sure asset URLs are absolute
            \Illuminate\Support\Facades\Vite::useAbsoluteAssetPaths();
        }
    }
}
