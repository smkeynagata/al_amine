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
        if (config('app.env') === 'production') {
            \Illuminate\Support\Facades\URL::forceScheme('https');

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
