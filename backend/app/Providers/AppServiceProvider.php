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
        // Force HTTPS for all environments (Railway requires this)
        \Illuminate\Support\Facades\URL::forceScheme('https');

        // Trust all proxies (for Railway and other cloud platforms)
        $this->app['request']->server->set('HTTPS', 'on');

        // Force root URL to HTTPS
        if (config('app.url')) {
            $appUrl = config('app.url');
            if (strpos($appUrl, 'http://') === 0) {
                config(['app.url' => str_replace('http://', 'https://', $appUrl)]);
            }
        }

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
