<?php

/**
 * Goatin Local to Live Product, Inventaris, and Rekam Medis Pusher
 * 
 * This script synchronizes new inventaris records, products (katalog), and rekam medis,
 * along with product photos from the local MySQL database to the live 
 * PostgreSQL database (Railway) and Supabase Storage.
 * 
 * Usage:
 *   php push_to_live.php            -> Executes the push and uploads/inserts
 *   php push_to_live.php --dry-run  -> Previews the push without making any changes
 */

echo "=========================================================\n";
echo "       GOATIN LOCAL TO LIVE DATA AUTO-SYNCER             \n";
echo "=========================================================\n\n";

// 1. Bootstrap Laravel Framework
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

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

// Determine dry-run mode
$dryRun = in_array('--dry-run', $argv);
if ($dryRun) {
    echo "⚠️  RUNNING IN DRY-RUN MODE: No writes or uploads will be performed.\n\n";
} else {
    echo "🚀 RUNNING IN WRITE MODE: Syncing live PostgreSQL and Supabase Storage...\n\n";
}

// 4. Test Connections
try {
    echo "Checking Local Database Connection (MySQL)... ";
    DB::connection('mysql')->getPdo();
    echo "CONNECTED.\n";
} catch (\Exception $e) {
    echo "FAILED.\n❌ Local DB connection error: " . $e->getMessage() . "\n";
    exit(1);
}

try {
    echo "Checking Live Database Connection (PostgreSQL)... ";
    DB::connection('live')->getPdo();
    echo "CONNECTED.\n\n";
} catch (\Exception $e) {
    echo "FAILED.\n❌ Live DB connection error: " . $e->getMessage() . "\n";
    exit(1);
}

// Global counters
$inventarisCopied = 0;
$productsPushed = 0;
$rekamMedisCopied = 0;

// -----------------------------------------------------------------------------
// FASE 1: SINKRONISASI TABEL INVENTARIS
// -----------------------------------------------------------------------------
echo "=========================================================\n";
echo " FASE 1: SINKRONISASI INVENTARIS\n";
echo "=========================================================\n";

try {
    $localInventarisList = DB::connection('mysql')->table('inventaris')->orderBy('id', 'asc')->get();
    echo "Local database has " . $localInventarisList->count() . " inventaris records.\n";

    $liveInventarisIds = DB::connection('live')->table('inventaris')->pluck('id')->toArray();
    echo "Live database has " . count($liveInventarisIds) . " inventaris records.\n\n";

    $newInventaris = [];
    foreach ($localInventarisList as $item) {
        if (!in_array($item->id, $liveInventarisIds)) {
            $newInventaris[] = $item;
        }
    }

    if (count($newInventaris) === 0) {
        echo "✅ All inventaris records are already synchronized.\n";
    } else {
        echo "Found " . count($newInventaris) . " new inventaris record(s) to push.\n";
        foreach ($newInventaris as $item) {
            echo "🐾 Syncing Inventaris ID {$item->id}: {$item->jenis} ({$item->ras}, {$item->gender})\n";
            if (!$dryRun) {
                DB::connection('live')->table('inventaris')->insert((array)$item);
            }
            $inventarisCopied++;
        }
        echo "   -> Completed Fase 1.\n";
    }
} catch (\Exception $e) {
    echo "❌ Error during Inventaris sync: " . $e->getMessage() . "\n";
}

echo "\n";

// -----------------------------------------------------------------------------
// FASE 2: SINKRONISASI TABEL PRODUK
// -----------------------------------------------------------------------------
echo "=========================================================\n";
echo " FASE 2: SINKRONISASI PRODUK (KATALOG)\n";
echo "=========================================================\n";

try {
    $localProducts = DB::connection('mysql')->table('produks')->orderBy('id', 'asc')->get();
    echo "Local database has " . $localProducts->count() . " products.\n";

    $liveProductIds = DB::connection('live')->table('produks')->pluck('id')->toArray();
    $liveProductNames = DB::connection('live')->table('produks')->pluck('nama_produk')->toArray();
    
    // Refresh live inventaris IDs in case we just inserted new ones
    $liveInventarisIds = DB::connection('live')->table('inventaris')->pluck('id')->toArray();
    
    echo "Live database has " . count($liveProductIds) . " products.\n\n";

    $newProducts = [];
    foreach ($localProducts as $product) {
        // A product is new if:
        // 1. Its ID is missing on live
        // 2. OR, its Name is missing on live
        // 3. OR, its related inventaris_id is missing on live
        $isNew = !in_array($product->id, $liveProductIds) ||
                 !in_array($product->nama_produk, $liveProductNames) ||
                 ($product->inventaris_id !== null && !in_array($product->inventaris_id, $liveInventarisIds));

        if ($isNew) {
            $newProducts[] = $product;
        }
    }

    if (count($newProducts) === 0) {
        echo "✅ All products are already synchronized.\n";
    } else {
        echo "Found " . count($newProducts) . " new product(s) to push.\n";
        foreach ($newProducts as $product) {
            echo "📦 Processing Product ID {$product->id}: '{$product->nama_produk}'\n";

            // Safety check for foreign key reference
            if ($product->inventaris_id !== null && !in_array($product->inventaris_id, $liveInventarisIds)) {
                echo "   ⚠️ Warning: Inventaris ID {$product->inventaris_id} does not exist on live! Skipping product.\n";
                continue;
            }

            // Image Upload
            if ($product->foto) {
                $localFilePath = storage_path('app/public/' . $product->foto);
                if (file_exists($localFilePath)) {
                    echo "   -> Uploading photo '{$product->foto}' to Supabase ... ";
                    if (!$dryRun) {
                        try {
                            $fileContents = file_get_contents($localFilePath);
                            Storage::disk('supabase')->put($product->foto, $fileContents);
                            echo "SUCCESS.\n";
                        } catch (\Exception $uploadEx) {
                            echo "FAILED (" . $uploadEx->getMessage() . ").\n";
                        }
                    } else {
                        echo "SIMULATED (dry-run).\n";
                    }
                } else {
                    echo "   -> ⚠️ Image file not found locally at: {$localFilePath}\n";
                }
            }

            // DB Insert
            echo "   -> Inserting product record to live PostgreSQL ... ";
            if (!$dryRun) {
                // Ensure we don't cause duplicate ID errors if ID already exists but other criteria matched.
                // If ID exists on live, delete the old one or skip. In this case, if the ID exists, we delete it to overwrite.
                if (in_array($product->id, $liveProductIds)) {
                    DB::connection('live')->table('produks')->where('id', $product->id)->delete();
                }
                DB::connection('live')->table('produks')->insert((array)$product);
            }
            echo "SUCCESS.\n";
            $productsPushed++;
        }
        echo "   -> Completed Fase 2.\n";
    }
} catch (\Exception $e) {
    echo "❌ Error during Product sync: " . $e->getMessage() . "\n";
}

echo "\n";

// -----------------------------------------------------------------------------
// FASE 3: SINKRONISASI TABEL REKAM MEDIS
// -----------------------------------------------------------------------------
echo "=========================================================\n";
echo " FASE 3: SINKRONISASI REKAM MEDIS\n";
echo "=========================================================\n";

try {
    $localRekamMedis = DB::connection('mysql')->table('rekam_medis')->orderBy('id', 'asc')->get();
    echo "Local database has " . $localRekamMedis->count() . " medical records.\n";

    $liveRekamMedisIds = DB::connection('live')->table('rekam_medis')->pluck('id')->toArray();
    $liveInventarisIds = DB::connection('live')->table('inventaris')->pluck('id')->toArray();
    echo "Live database has " . count($liveRekamMedisIds) . " medical records.\n\n";

    $newRekamMedis = [];
    foreach ($localRekamMedis as $rm) {
        if (!in_array($rm->id, $liveRekamMedisIds)) {
            $newRekamMedis[] = $rm;
        }
    }

    if (count($newRekamMedis) === 0) {
        echo "✅ All medical records are already synchronized.\n";
    } else {
        echo "Found " . count($newRekamMedis) . " new medical record(s) to push.\n";
        foreach ($newRekamMedis as $rm) {
            echo "🩺 Syncing Medical Record ID {$rm->id}: Date {$rm->tanggal} (Inventaris ID {$rm->inventaris_id})\n";

            // Safety check for foreign key reference
            if (!in_array($rm->inventaris_id, $liveInventarisIds)) {
                echo "   ⚠️ Warning: Inventaris ID {$rm->inventaris_id} does not exist on live! Skipping medical record.\n";
                continue;
            }

            if (!$dryRun) {
                DB::connection('live')->table('rekam_medis')->insert((array)$rm);
            }
            $rekamMedisCopied++;
        }
        echo "   -> Completed Fase 3.\n";
    }
} catch (\Exception $e) {
    echo "❌ Error during Rekam Medis sync: " . $e->getMessage() . "\n";
}

echo "\n";

// -----------------------------------------------------------------------------
// SEQUENCE RESETS (POSTGRESQL ONLY)
// -----------------------------------------------------------------------------
if (!$dryRun && ($inventarisCopied > 0 || $productsPushed > 0 || $rekamMedisCopied > 0)) {
    echo "=========================================================\n";
    echo " SYNCHRONIZING AUTO-INCREMENT SEQUENCES\n";
    echo "=========================================================\n";

    $sequences = [
        'inventaris' => 'id',
        'produks' => 'id',
        'rekam_medis' => 'id',
    ];

    foreach ($sequences as $table => $column) {
        echo "Resetting sequence for '{$table}' ... ";
        try {
            DB::connection('live')->statement(
                "SELECT setval(pg_get_serial_sequence('{$table}', '{$column}'), coalesce(max({$column}), 0) + 1, false) FROM {$table};"
            );
            echo "SUCCESS.\n";
        } catch (\Exception $seqEx) {
            echo "FAILED (" . $seqEx->getMessage() . ").\n";
        }
    }
}

echo "\n---------------------------------------------------------\n";
if ($dryRun) {
    echo "🏁 Dry-run completed.\n";
    echo "   - Inventaris to push: " . count($newInventaris) . "\n";
    echo "   - Products to push:   " . count($newProducts) . "\n";
    echo "   - Rekam Medis to push:" . count($newRekamMedis) . "\n";
} else {
    echo "🏁 Sync completed successfully!\n";
    echo "   - Inventaris copied:  {$inventarisCopied}\n";
    echo "   - Products pushed:    {$productsPushed}\n";
    echo "   - Rekam Medis copied: {$rekamMedisCopied}\n";
}
echo "=========================================================\n";
