<?php

/**
 * Goatin Storage Sync Script
 * Automatically downloads missing local article and product photos from Supabase Storage,
 * or copies local placeholders/screenshots to avoid 404 Forbidden or 404 Not Found errors on localhost.
 */

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Artikel;
use App\Models\Produk;
use Illuminate\Support\Facades\Storage;

echo "=============================================\n";
echo "    GOATIN STORAGE SYNC FROM SUPABASE        \n";
echo "=============================================\n\n";

// Disable SSL verification for file_get_contents
$context = stream_context_create([
    "ssl" => [
        "verify_peer" => false,
        "verify_peer_name" => false,
    ],
    "http" => [
        "timeout" => 10 // Timeout in seconds
    ]
]);

// 1. Sync Artikel Photos
echo "[1/2] Sinkronisasi Foto Artikel...\n";
$artikels = Artikel::all();
$successArtikel = 0;
$failedArtikel = 0;

$localScreenshots = glob(storage_path('app/public/artikel_fotos/Cuplikan*.png'));

foreach ($artikels as $artikel) {
    if (!$artikel->foto) continue;
    
    $localPath = storage_path('app/public/' . $artikel->foto);
    $localDir = dirname($localPath);
    if (!is_dir($localDir)) {
        mkdir($localDir, 0755, true);
    }
    
    if (file_exists($localPath)) {
        echo " -> Exists: {$artikel->foto}\n";
        continue;
    }
    
    // Attempt download from Supabase
    $url = Storage::disk('supabase')->url($artikel->foto);
    echo " -> Downloading: {$artikel->foto} ... ";
    
    $content = @file_get_contents($url, false, $context);
    
    if ($content !== false) {
        file_put_contents($localPath, $content);
        echo "SUCCESS (Downloaded)\n";
        $successArtikel++;
    } else {
        // Fallback: Copy a screenshot if available
        if (!empty($localScreenshots)) {
            $src = array_shift($localScreenshots);
            copy($src, $localPath);
            echo "FALLBACK (Copied Screenshot: " . basename($src) . ")\n";
            $successArtikel++;
        } else {
            // Copy default placeholder
            $placeholder = public_path('images/background_goats.png');
            if (file_exists($placeholder)) {
                copy($placeholder, $localPath);
                echo "FALLBACK (Copied Placeholder)\n";
                $successArtikel++;
            } else {
                echo "FAILED\n";
                $failedArtikel++;
            }
        }
    }
}

// 2. Sync Produk Photos
echo "\n[2/2] Sinkronisasi Foto Produk...\n";
$produks = Produk::all();
$successProduk = 0;
$failedProduk = 0;

// Gather some existing product photos for fallback if any
$localProductPhotos = glob(storage_path('app/public/produk_fotos/*.png'));

foreach ($produks as $produk) {
    if (!$produk->foto) continue;
    
    $localPath = storage_path('app/public/' . $produk->foto);
    $localDir = dirname($localPath);
    if (!is_dir($localDir)) {
        mkdir($localDir, 0755, true);
    }
    
    if (file_exists($localPath)) {
        echo " -> Exists: {$produk->foto}\n";
        continue;
    }
    
    // Attempt download from Supabase
    $url = Storage::disk('supabase')->url($produk->foto);
    echo " -> Downloading: {$produk->foto} ... ";
    
    $content = @file_get_contents($url, false, $context);
    
    if ($content !== false) {
        file_put_contents($localPath, $content);
        echo "SUCCESS (Downloaded)\n";
        $successProduk++;
    } else {
        // Fallback to one of the existing local product photos if available
        if (!empty($localProductPhotos)) {
            $src = $localProductPhotos[array_rand($localProductPhotos)];
            copy($src, $localPath);
            echo "FALLBACK (Copied Local Photo: " . basename($src) . ")\n";
            $successProduk++;
        } else {
            // Copy default placeholder
            $placeholder = public_path('images/background_goats.png');
            if (file_exists($placeholder)) {
                copy($placeholder, $localPath);
                echo "FALLBACK (Copied Placeholder)\n";
                $successProduk++;
            } else {
                echo "FAILED\n";
                $failedProduk++;
            }
        }
    }
}

echo "\n=============================================\n";
echo "SINKRONISASI SELESAI!\n";
echo "Artikel: {$successArtikel} sukses, {$failedArtikel} gagal.\n";
echo "Produk: {$successProduk} sukses, {$failedProduk} gagal.\n";
echo "=============================================\n";
