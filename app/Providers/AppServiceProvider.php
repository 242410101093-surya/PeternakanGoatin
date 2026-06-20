<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Http;

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
        // 1. PENGATURAN LIVE SERVER (RAILWAY)
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }

        // 2. PENGATURAN LINGKUNGAN PENGEMBANGAN (LOKAL LAPTOP)
        if (config('app.env') === 'local') {
            // Memaksa PHP Environment mengabaikan verifikasi peer SSL cURL
            putenv('AZURE_SSL_NO_VERIFY=1');
            putenv('CURLOPT_SSL_VERIFYPEER=0');
            
            // Bypass SSL untuk HTTP Client bawaan Laravel
            Http::globalOptions([
                'verify' => false,
            ]);
            
            // Bypass SSL untuk stream context PHP standar (Flysystem/Supabase package)
            stream_context_set_default([
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                ],
            ]);
        }
    }
}