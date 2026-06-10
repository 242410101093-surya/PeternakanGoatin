<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL; // <-- Namespace URL yang benar ditaruh di sini

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
        // Memaksa semua URL menggunakan HTTPS jika di-deploy di live server (Railway)
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }
    }
}

