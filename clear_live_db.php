<?php

/**
 * Goatin - Live Database Cleaner
 * 
 * Skrip ini berfungsi untuk MENGOSONGKAN (TRUNCATE) tabel artikels dan produks 
 * di database PostgreSQL Live (Supabase). Ini sangat berguna sebelum 
 * melakukan sinkronisasi ulang secara penuh.
 * 
 * Penggunaan: php clear_live_db.php
 */

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=========================================================\n";
echo "       GOATIN LIVE DATABASE CLEANER (DANGER ZONE)        \n";
echo "=========================================================\n\n";

$liveHost = env('LIVE_DB_HOST');
$liveDb   = env('LIVE_DB_DATABASE');
$liveUser = env('LIVE_DB_USERNAME');
$livePass = env('LIVE_DB_PASSWORD');
$livePort = env('LIVE_DB_PORT', '5432');

if (empty($liveHost) || empty($liveDb) || empty($liveUser)) {
    echo "❌ Missing live PostgreSQL database configuration in your .env file!\n";
    exit(1);
}

config(['database.connections.live' => [
    'driver' => 'pgsql',
    'host' => $liveHost,
    'port' => $livePort,
    'database' => $liveDb,
    'username' => $liveUser,
    'password' => $livePass,
    'charset' => 'utf8',
    'prefix' => '',
    'schema' => 'public',
    'sslmode' => 'prefer',
]]);

echo "Mengkoneksikan ke Live Database PostgreSQL...\n";
try {
    $livePdo = DB::connection('live')->getPdo();
    echo "✅ KONEKSI BERHASIL.\n\n";
} catch (\Exception $e) {
    echo "❌ KONEKSI GAGAL: " . $e->getMessage() . "\n";
    exit(1);
}

echo "⚠️ PERINGATAN: Skrip ini akan MENGHAPUS SEMUA DATA di tabel 'artikels' dan 'produks' secara LIVE!\n";
echo "Ketik 'Y' untuk melanjutkan, atau apa saja untuk membatalkan: ";
$handle = fopen("php://stdin", "r");
$line = fgets($handle);
if (trim(strtoupper($line)) != 'Y') {
    echo "Proses dibatalkan.\n";
    exit(0);
}

try {
    echo "\nSedang mengeksekusi TRUNCATE pada tabel artikels dan produks...\n";
    $livePdo->exec("TRUNCATE TABLE artikels, produks RESTART IDENTITY CASCADE;");
    echo "✅ BERHASIL: Tabel artikels dan produks telah dikosongkan secara permanen.\n";
    echo "   Sequence ID (auto-increment) telah di-reset ke angka 1.\n";
    echo "   Anda kini siap untuk menjalankan push_to_live.php untuk fresh sync.\n";
} catch (\Exception $e) {
    echo "❌ GAGAL MENGOSONGKAN TABEL: " . $e->getMessage() . "\n";
    exit(1);
}

echo "=========================================================\n";
