<?php

/**
 * Goatin Live Database Truncator
 * 
 * This script empties key tables in the live PostgreSQL database (Railway/Supabase)
 * to prepare for a fresh storage push.
 */

echo "=========================================================\n";
echo "       GOATIN LIVE DATABASE TABLES TRUNCATOR             \n";
echo "=========================================================\n\n";

// Bootstrap Laravel Kernel
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Hardcoded Live PostgreSQL Credentials
$liveHost = "aws-1-ap-northeast-1.pooler.supabase.com";
$liveDb   = "postgres";
$liveUser = "postgres.yzvshrhziexfcjhamrfk";
$livePass = "Suray231-ok.";
$livePort = "5432";

try {
    $dsn = "pgsql:host=$liveHost;port=$livePort;dbname=$liveDb;";
    $livePdo = new PDO($dsn, $liveUser, $livePass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
    echo "📡 Connected to Live Database (PostgreSQL)... CONNECTED.\n\n";
} catch (PDOException $e) {
    echo "❌ Live DB connection error: " . $e->getMessage() . "\n";
    exit(1);
}

try {
    echo "🧹 Truncating 'artikels' table ... ";
    $livePdo->exec("TRUNCATE TABLE artikels RESTART IDENTITY CASCADE;");
    echo "TRUNCATED.\n";

    echo "🧹 Truncating 'produks' table ... ";
    $livePdo->exec("TRUNCATE TABLE produks RESTART IDENTITY CASCADE;");
    echo "TRUNCATED.\n";

    echo "\n✅ Live database cleanup completed successfully!\n";
} catch (PDOException $e) {
    echo "❌ Error executing truncate queries: " . $e->getMessage() . "\n";
    exit(1);
}

echo "=========================================================\n";
