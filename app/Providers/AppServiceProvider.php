<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL; // Importante agregar esta línea

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
        // Si el entorno es producción (como en Render), forzamos HTTPS
        if (config('app.env') === 'production' || config('app.url') !== 'http://localhost') {
            URL::forceScheme('https');
        }
    }
}