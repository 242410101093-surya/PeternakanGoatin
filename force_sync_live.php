<?php

/**
 * Goatin Database & Supabase Storage Synchronizer (Live PostgreSQL Connection)
 * 
 * This script synchronizes the 'foto' paths in the live PostgreSQL database
 * with the actual filenames present in Supabase Storage.
 * 
 * Usage:
 *   php force_sync_live.php            -> Executes the sync and saves to the database
 *   php force_sync_live.php --dry-run  -> Previews the sync without modifying the database
 */

echo "=========================================================\n";
echo "    GOATIN DATABASE & SUPABASE STORAGE AUTO-SYNCER       \n";
echo "=========================================================\n\n";

// 1. Bootstrap Laravel Framework
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

// Determine if dry-run mode is enabled
$dryRun = in_array('--dry-run', $argv);
if ($dryRun) {
    echo "⚠️  RUNNING IN DRY-RUN MODE: No changes will be written to the database.\n\n";
} else {
    echo "🚀 RUNNING IN WRITE MODE: Changes will be saved directly to the database.\n\n";
}

// 2. Validate Live PostgreSQL Credentials
$liveHost = env('LIVE_DB_HOST');
$liveDb   = env('LIVE_DB_DATABASE');
$liveUser = env('LIVE_DB_USERNAME');
$livePass = env('LIVE_DB_PASSWORD');
$livePort = env('LIVE_DB_PORT', '5432');

if (empty($liveHost) || empty($liveDb) || empty($liveUser)) {
    echo "❌ Missing live PostgreSQL database configuration in your .env file!\n";
    echo "   Please define the following variables in your local .env file first:\n\n";
    echo "   LIVE_DB_HOST=...\n";
    echo "   LIVE_DB_PORT=5432\n";
    echo "   LIVE_DB_DATABASE=...\n";
    echo "   LIVE_DB_USERNAME=...\n";
    echo "   LIVE_DB_PASSWORD=...\n\n";
    exit(1);
}

// 3. Dynamic Registration of Live Connection
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

try {
    echo "Checking Live Database Connection (PostgreSQL)... ";
    $livePdo = DB::connection('live')->getPdo();
    echo "CONNECTED.\n\n";
} catch (\Exception $e) {
    echo "FAILED.\n❌ Live DB connection error: " . $e->getMessage() . "\n";
    exit(1);
}

// -----------------------------------------------------------------------------
// SINKRONISASI 1: FOTO PRODUK
// -----------------------------------------------------------------------------
echo "=========================================================\n";
echo " 1. SINKRONISASI TABEL PRODUK (produk_fotos)\n";
echo "=========================================================\n";

try {
    // A. Ambil semua file produk dari Supabase Storage
    echo "Reading file list from Supabase Storage 'produk_fotos/' ... ";
    $supabaseProductFiles = Storage::disk('supabase')->files('produk_fotos');
    echo "FOUND " . count($supabaseProductFiles) . " files.\n";

    // B. Persiapkan pencocokan dari Live DB
    $stmt = $livePdo->query("SELECT id, nama_produk, foto FROM produks ORDER BY id ASC");
    $products = $stmt->fetchAll(\PDO::FETCH_OBJ);
    echo "Total products in database: " . count($products) . "\n\n";

    // Map file Supabase berdasarkan basename-nya untuk pencocokan cepat
    $supabaseProductBasenames = [];
    foreach ($supabaseProductFiles as $file) {
        $basename = basename($file);
        $supabaseProductBasenames[$basename] = $file;
    }

    $matchedProducts = [];
    $unmatchedProducts = [];
    $matchedFiles = [];

    // C. Fase 1: Pencocokan Berdasarkan Nama File (Basename Match)
    $updateProductStmt = $livePdo->prepare("UPDATE produks SET foto = :foto WHERE id = :id");
    foreach ($products as $product) {
        if (!$product->foto) {
            $unmatchedProducts[] = $product;
            continue;
        }

        $dbBasename = basename($product->foto);
        
        if (isset($supabaseProductBasenames[$dbBasename])) {
            $matchedFile = $supabaseProductBasenames[$dbBasename];
            $matchedProducts[$product->id] = $matchedFile;
            $matchedFiles[$matchedFile] = true;
            
            // Update jika path tidak persis sama (misalnya butuh prefiks)
            if ($product->foto !== $matchedFile) {
                echo "   ✅ Match found for Product ID {$product->id} ('{$product->nama_produk}'):\n";
                echo "      Old DB path: '{$product->foto}'\n";
                echo "      New DB path: '{$matchedFile}'\n";
                
                if (!$dryRun) {
                    $updateProductStmt->execute([':foto' => $matchedFile, ':id' => $product->id]);
                }
            } else {
                echo "   ✨ Product ID {$product->id} ('{$product->nama_produk}') is already correct: '{$product->foto}'\n";
            }
        } else {
            $unmatchedProducts[] = $product;
        }
    }

    // Identifikasi file Supabase yang belum terpakai oleh match di atas
    $unusedSupabaseProductFiles = [];
    foreach ($supabaseProductFiles as $file) {
        if (!isset($matchedFiles[$file])) {
            $unusedSupabaseProductFiles[] = $file;
        }
    }

    echo "\nSummary of Phase 1 (Product Match):\n";
    echo " - Matches resolved: " . count($matchedProducts) . "\n";
    echo " - Products remaining to match: " . count($unmatchedProducts) . "\n";
    echo " - Unused Supabase files remaining: " . count($unusedSupabaseProductFiles) . "\n\n";

    // D. Fase 2: Pemetaan Sekuensial untuk Sisa Record
    if (count($unmatchedProducts) > 0) {
        echo "Phase 2: Mapping remaining products sequentially...\n";
        foreach ($unmatchedProducts as $index => $product) {
            if (isset($unusedSupabaseProductFiles[$index])) {
                $assignedFile = $unusedSupabaseProductFiles[$index];
                echo "   🔗 Map unmatched Product ID {$product->id} ('{$product->nama_produk}'):\n";
                echo "      Old DB path: '{$product->foto}'\n";
                echo "      New DB path: '{$assignedFile}' (Sequential Fallback)\n";
                
                if (!$dryRun) {
                    $updateProductStmt->execute([':foto' => $assignedFile, ':id' => $product->id]);
                }
            } else {
                echo "   ⚠️  No unused Supabase product files left for Product ID {$product->id} ('{$product->nama_produk}')\n";
            }
        }
    }
} catch (\Exception $e) {
    echo "❌ Error during Product sync: " . $e->getMessage() . "\n";
}

echo "\n";

// -----------------------------------------------------------------------------
// SINKRONISASI 2: FOTO ARTIKEL
// -----------------------------------------------------------------------------
echo "=========================================================\n";
echo " 2. SINKRONISASI TABEL ARTIKEL (artikel_fotos)\n";
echo "=========================================================\n";

try {
    // A. Ambil semua file artikel dari Supabase Storage
    echo "Reading file list from Supabase Storage 'artikel_fotos/' ... ";
    $supabaseArtikelFiles = Storage::disk('supabase')->files('artikel_fotos');
    echo "FOUND " . count($supabaseArtikelFiles) . " files.\n";

    // B. Persiapkan pencocokan dari Live DB
    $stmt = $livePdo->query("SELECT id, judul, foto FROM artikels ORDER BY id ASC");
    $artikels = $stmt->fetchAll(\PDO::FETCH_OBJ);
    echo "Total articles in database: " . count($artikels) . "\n\n";

    // Map file Supabase berdasarkan basename-nya untuk pencocokan cepat
    $supabaseArtikelBasenames = [];
    foreach ($supabaseArtikelFiles as $file) {
        $basename = basename($file);
        $supabaseArtikelBasenames[$basename] = $file;
    }

    $matchedArtikels = [];
    $unmatchedArtikels = [];
    $matchedArtikelFiles = [];

    // C. Fase 1: Pencocokan Berdasarkan Nama File (Basename Match)
    $updateArtikelStmt = $livePdo->prepare("UPDATE artikels SET foto = :foto WHERE id = :id");
    foreach ($artikels as $artikel) {
        if (!$artikel->foto) {
            $unmatchedArtikels[] = $artikel;
            continue;
        }

        $dbBasename = basename($artikel->foto);
        
        if (isset($supabaseArtikelBasenames[$dbBasename])) {
            $matchedFile = $supabaseArtikelBasenames[$dbBasename];
            $matchedArtikels[$artikel->id] = $matchedFile;
            $matchedArtikelFiles[$matchedFile] = true;
            
            // Update jika path tidak persis sama (misalnya butuh prefiks)
            if ($artikel->foto !== $matchedFile) {
                echo "   ✅ Match found for Article ID {$artikel->id} ('{$artikel->judul}'):\n";
                echo "      Old DB path: '{$artikel->foto}'\n";
                echo "      New DB path: '{$matchedFile}'\n";
                
                if (!$dryRun) {
                    $updateArtikelStmt->execute([':foto' => $matchedFile, ':id' => $artikel->id]);
                }
            } else {
                echo "   ✨ Article ID {$artikel->id} ('{$artikel->judul}') is already correct: '{$artikel->foto}'\n";
            }
        } else {
            $unmatchedArtikels[] = $artikel;
        }
    }

    // Identifikasi file Supabase yang belum terpakai oleh match di atas
    $unusedSupabaseArtikelFiles = [];
    foreach ($supabaseArtikelFiles as $file) {
        if (!isset($matchedArtikelFiles[$file])) {
            $unusedSupabaseArtikelFiles[] = $file;
        }
    }

    echo "\nSummary of Phase 1 (Article Match):\n";
    echo " - Matches resolved: " . count($matchedArtikels) . "\n";
    echo " - Articles remaining to match: " . count($unmatchedArtikels) . "\n";
    echo " - Unused Supabase files remaining: " . count($unusedSupabaseArtikelFiles) . "\n\n";

    // D. Fase 2: Pemetaan Sekuensial untuk Sisa Record
    if (count($unmatchedArtikels) > 0) {
        echo "Phase 2: Mapping remaining articles sequentially...\n";
        foreach ($unmatchedArtikels as $index => $artikel) {
            if (isset($unusedSupabaseArtikelFiles[$index])) {
                $assignedFile = $unusedSupabaseArtikelFiles[$index];
                echo "   🔗 Map unmatched Article ID {$artikel->id} ('{$artikel->judul}'):\n";
                echo "      Old DB path: '{$artikel->foto}'\n";
                echo "      New DB path: '{$assignedFile}' (Sequential Fallback)\n";
                
                if (!$dryRun) {
                    $updateArtikelStmt->execute([':foto' => $assignedFile, ':id' => $artikel->id]);
                }
            } else {
                echo "   ⚠️  No unused Supabase article files left for Article ID {$artikel->id} ('{$artikel->judul}')\n";
            }
        }
    }
} catch (\Exception $e) {
    echo "❌ Error during Article sync: " . $e->getMessage() . "\n";
}

echo "\n";
echo "=========================================================\n";
echo "🏁  SINKRONISASI SELESAI\n";
echo "=========================================================\n";
