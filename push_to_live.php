<?php

/**
 * Goatin Local to Live Product Pusher
 * 
 * This script synchronizes new products and their corresponding inventaris records, 
 * along with their image files, from the local MySQL database to the live 
 * PostgreSQL database (Railway) and Supabase Storage.
 * 
 * Usage:
 *   php push_to_live.php            -> Executes the push and uploads/inserts
 *   php push_to_live.php --dry-run  -> Previews the push without making any changes
 */

echo "=========================================================\n";
echo "       GOATIN LOCAL TO LIVE PRODUCT PUSHER               \n";
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

// 5. Fetch Data
try {
    // Get all products from local MySQL database
    $localProducts = DB::connection('mysql')->table('produks')->orderBy('id', 'asc')->get();
    echo "Local database has " . $localProducts->count() . " products.\n";

    // Get all product names from live PostgreSQL database
    $liveProductNames = DB::connection('live')->table('produks')->pluck('nama_produk')->toArray();
    echo "Live database has " . count($liveProductNames) . " products.\n\n";

    // 6. Filter products that are missing on live database
    $newProducts = [];
    foreach ($localProducts as $product) {
        if (!in_array($product->nama_produk, $liveProductNames)) {
            $newProducts[] = $product;
        }
    }

    if (count($newProducts) === 0) {
        echo "✅ All products are already synchronized. Nothing to push!\n";
        exit(0);
    }

    echo "Found " . count($newProducts) . " new product(s) to push to live database.\n";
    echo "---------------------------------------------------------\n";

    $pushedCount = 0;

    foreach ($newProducts as $product) {
        echo "📦 Processing Product ID {$product->id}: '{$product->nama_produk}'\n";

        // A. Handle Inventaris relationship (foreign key constraint)
        if ($product->inventaris_id !== null) {
            $liveInventarisExists = DB::connection('live')
                ->table('inventaris')
                ->where('id', $product->inventaris_id)
                ->exists();

            if (!$liveInventarisExists) {
                echo "   -> Copying related inventaris ID {$product->inventaris_id} ... ";
                $localInventaris = DB::connection('mysql')
                    ->table('inventaris')
                    ->where('id', $product->inventaris_id)
                    ->first();

                if ($localInventaris) {
                    if (!$dryRun) {
                        DB::connection('live')->table('inventaris')->insert((array)$localInventaris);
                    }
                    echo "SUCCESS (copied).\n";
                } else {
                    echo "FAILED (local record not found).\n";
                    echo "   ⚠️ Skipping product due to missing inventaris source data.\n";
                    continue;
                }
            } else {
                echo "   -> Related inventaris ID {$product->inventaris_id} already exists on live database.\n";
            }
        }

        // B. Handle Image upload to Supabase Storage
        if ($product->foto) {
            $localFilePath = storage_path('app/public/' . $product->foto);
            
            if (file_exists($localFilePath)) {
                echo "   -> Uploading photo '{$product->foto}' to Supabase Storage ... ";
                if (!$dryRun) {
                    try {
                        $fileContents = file_get_contents($localFilePath);
                        Storage::disk('supabase')->put($product->foto, $fileContents);
                        echo "SUCCESS.\n";
                    } catch (\Exception $uploadEx) {
                        echo "FAILED (" . $uploadEx->getMessage() . ").\n";
                        echo "   ⚠️ Continuing without photo upload blocks.\n";
                    }
                } else {
                    echo "SIMULATED (dry-run).\n";
                }
            } else {
                echo "   -> ⚠️ Image file not found locally at: {$localFilePath}\n";
            }
        }

        // C. Insert Product to Live Database
        echo "   -> Inserting product record to live PostgreSQL ... ";
        if (!$dryRun) {
            DB::connection('live')->table('produks')->insert((array)$product);
        }
        echo "SUCCESS.\n";
        $pushedCount++;
    }

    // 7. Reset sequences in live PostgreSQL to prevent auto-increment collisions
    if (!$dryRun && $pushedCount > 0) {
        echo "\n🔄 Synchronizing PostgreSQL auto-increment sequences ... ";
        try {
            DB::connection('live')->statement("SELECT setval(pg_get_serial_sequence('produks', 'id'), coalesce(max(id), 1)) FROM produks;");
            DB::connection('live')->statement("SELECT setval(pg_get_serial_sequence('inventaris', 'id'), coalesce(max(id), 1)) FROM inventaris;");
            echo "SUCCESS.\n";
        } catch (\Exception $seqEx) {
            echo "FAILED (" . $seqEx->getMessage() . ").\n";
            echo "   ⚠️ You may need to manually sync sequence IDs later.\n";
        }
    }

    echo "\n---------------------------------------------------------\n";
    if ($dryRun) {
        echo "🏁 Dry-run completed. " . count($newProducts) . " product(s) would be pushed.\n";
    } else {
        echo "🏁 Sync completed successfully! Pushed {$pushedCount} product(s) to live database.\n";
    }

} catch (\Exception $e) {
    echo "\n❌ System Error: " . $e->getMessage() . "\n";
    exit(1);
}
