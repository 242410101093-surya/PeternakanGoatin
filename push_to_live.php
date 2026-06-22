<?php

/**
 * Goatin Local to Live Data Auto-Syncer
 * 
 * Skrip ini dilengkapi dengan Menu Interaktif CLI.
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
    $livePdo = DB::connection('live')->getPdo();
    echo "CONNECTED.\n\n";
} catch (\Exception $e) {
    echo "FAILED.\n❌ Live DB connection error: " . $e->getMessage() . "\n";
    exit(1);
}

// -----------------------------------------------------------------------------
// MENU INTERAKTIF CLI
// -----------------------------------------------------------------------------
echo "=========================================================\n";
echo " PILIH MODUL YANG INGIN DI-PUSH KE LIVE SERVER\n";
echo "=========================================================\n";
echo "[1] Push SEMUA Data (Full Sync)\n";
echo "[2] Push Data Inventaris Saja\n";
echo "[3] Push Data Produk (Katalog & Foto) Saja\n";
echo "[4] Push Data Artikel (Konten & Foto) Saja\n";
echo "[5] Push Data Rekam Medis Saja\n";
echo "[6] Push Data Laporan Keuangan Saja\n";
echo "[0] Batalkan Proses\n";
echo "=========================================================\n";
fwrite(STDOUT, "Masukkan pilihan Anda (0-6): ");
$choice = trim(fgets(STDIN));

if ($choice === '0') {
    echo "Proses dibatalkan oleh pengguna.\n";
    exit(0);
}

$syncAll = ($choice === '1');

// Global counters
$inventarisCopied = 0;
$productsPushed = 0;
$rekamMedisCopied = 0;
$articlesPushed = 0;
$keuanganPushed = 0;

// -----------------------------------------------------------------------------
// FASE 1: SINKRONISASI TABEL INVENTARIS
// -----------------------------------------------------------------------------
if ($syncAll || $choice === '2') {
    echo "\n=========================================================\n";
    echo " FASE: SINKRONISASI INVENTARIS\n";
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
        }
    } catch (\Exception $e) {
        echo "❌ Error during Inventaris sync: " . $e->getMessage() . "\n";
    }
}

// -----------------------------------------------------------------------------
// FASE 2: SINKRONISASI TABEL PRODUK
// -----------------------------------------------------------------------------
if ($syncAll || $choice === '3') {
    echo "\n=========================================================\n";
    echo " FASE: SINKRONISASI PRODUK (KATALOG)\n";
    echo "=========================================================\n";

    try {
        $localProducts = DB::connection('mysql')->table('produks')->orderBy('id', 'asc')->get();
        echo "Local database has " . $localProducts->count() . " products.\n";

        $liveProductIds = DB::connection('live')->table('produks')->pluck('id')->toArray();
        $liveProductNames = DB::connection('live')->table('produks')->pluck('nama_produk')->toArray();
        
        // Refresh live inventaris IDs
        $liveInventarisIds = DB::connection('live')->table('inventaris')->pluck('id')->toArray();
        
        echo "Live database has " . count($liveProductIds) . " products.\n\n";

        $newProducts = [];
        foreach ($localProducts as $product) {
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

                if ($product->inventaris_id !== null && !in_array($product->inventaris_id, $liveInventarisIds)) {
                    echo "   ⚠️ Warning: Inventaris ID {$product->inventaris_id} does not exist on live! Skipping product.\n";
                    continue;
                }

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
                            echo "SIMULATED.\n";
                        }
                    } else {
                        echo "   -> ⚠️ Image file not found locally.\n";
                    }
                }

                echo "   -> Inserting product record to live PostgreSQL ... ";
                if (!$dryRun) {
                    if (in_array($product->id, $liveProductIds)) {
                        DB::connection('live')->table('produks')->where('id', $product->id)->delete();
                    }
                    DB::connection('live')->table('produks')->insert((array)$product);
                }
                echo "SUCCESS.\n";
                $productsPushed++;
            }
        }
    } catch (\Exception $e) {
        echo "❌ Error during Product sync: " . $e->getMessage() . "\n";
    }
}

// -----------------------------------------------------------------------------
// FASE 3: SINKRONISASI ARTIKEL
// -----------------------------------------------------------------------------
if ($syncAll || $choice === '4') {
    echo "\n=========================================================\n";
    echo " FASE: SINKRONISASI ARTIKEL\n";
    echo "=========================================================\n";

    try {
        $localArticles = DB::connection('mysql')->table('artikels')->orderBy('id', 'asc')->get();
        echo "Local database has " . $localArticles->count() . " articles.\n";

        $liveArticleIds = DB::connection('live')->table('artikels')->pluck('id')->toArray();
        echo "Live database has " . count($liveArticleIds) . " articles.\n\n";

        $newArticles = [];
        foreach ($localArticles as $article) {
            if (!in_array($article->id, $liveArticleIds)) {
                $newArticles[] = $article;
            }
        }

        if (count($newArticles) === 0) {
            echo "✅ All articles are already synchronized.\n";
        } else {
            echo "Found " . count($newArticles) . " new article(s) to push.\n";
            foreach ($newArticles as $article) {
                echo "📝 Processing Article ID {$article->id}: '{$article->judul}'\n";

                if ($article->foto) {
                    $localFilePath = storage_path('app/public/' . $article->foto);
                    if (file_exists($localFilePath)) {
                        echo "   -> Uploading photo '{$article->foto}' to Supabase ... ";
                        if (!$dryRun) {
                            try {
                                $fileContents = file_get_contents($localFilePath);
                                Storage::disk('supabase')->put($article->foto, $fileContents);
                                echo "SUCCESS.\n";
                            } catch (\Exception $uploadEx) {
                                echo "FAILED (" . $uploadEx->getMessage() . ").\n";
                            }
                        } else {
                            echo "SIMULATED.\n";
                        }
                    } else {
                        echo "   -> ⚠️ Image file not found locally.\n";
                    }
                }

                echo "   -> Inserting article record to live PostgreSQL ... ";
                if (!$dryRun) {
                    $articleData = (array)$article;
                    DB::connection('live')->table('artikels')->insert($articleData);
                }
                echo "SUCCESS.\n";
                $articlesPushed++;
            }
        }
    } catch (\Exception $e) {
        echo "❌ Error during Article sync: " . $e->getMessage() . "\n";
    }
}

// -----------------------------------------------------------------------------
// FASE 4: SINKRONISASI REKAM MEDIS
// -----------------------------------------------------------------------------
if ($syncAll || $choice === '5') {
    echo "\n=========================================================\n";
    echo " FASE: SINKRONISASI REKAM MEDIS\n";
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
                echo "🩺 Syncing Medical Record ID {$rm->id}: Date {$rm->tanggal}\n";

                if (!in_array($rm->inventaris_id, $liveInventarisIds)) {
                    echo "   ⚠️ Warning: Inventaris ID {$rm->inventaris_id} does not exist on live! Skipping.\n";
                    continue;
                }

                if (!$dryRun) {
                    DB::connection('live')->table('rekam_medis')->insert((array)$rm);
                }
                $rekamMedisCopied++;
            }
        }
    } catch (\Exception $e) {
        echo "❌ Error during Rekam Medis sync: " . $e->getMessage() . "\n";
    }
}

// -----------------------------------------------------------------------------
// FASE 5: SINKRONISASI LAPORAN KEUANGAN (SKENARIO B)
// -----------------------------------------------------------------------------
if ($syncAll || $choice === '6') {
    echo "\n=========================================================\n";
    echo " FASE: SINKRONISASI LAPORAN KEUANGAN (SKENARIO B)\n";
    echo "=========================================================\n";

    try {
        $localKeuangan = DB::connection('mysql')->table('laporan_keuangans')->orderBy('id', 'asc')->get();
        echo "Local database has " . $localKeuangan->count() . " financial records.\n";

        // To avoid inserting duplicates in Skenario B without an ID, we could check for an exact match
        // Or we assume a fresh live DB. Here we check by 'tanggal' and 'keterangan'.
        
        foreach ($localKeuangan as $lap) {
            echo "💰 Processing Laporan Keuangan: {$lap->keterangan} ({$lap->jumlah})\n";

            if ($lap->nota_pembayaran) {
                $localFilePath = storage_path('app/public/' . $lap->nota_pembayaran);
                if (file_exists($localFilePath)) {
                    echo "   -> Uploading nota '{$lap->nota_pembayaran}' to Supabase ... ";
                    if (!$dryRun) {
                        try {
                            $fileContents = file_get_contents($localFilePath);
                            Storage::disk('supabase')->put($lap->nota_pembayaran, $fileContents);
                            echo "SUCCESS.\n";
                        } catch (\Exception $uploadEx) {
                            echo "FAILED (" . $uploadEx->getMessage() . ").\n";
                        }
                    } else {
                        echo "SIMULATED.\n";
                    }
                } else {
                    echo "   -> ⚠️ Nota file not found locally.\n";
                }
            }

            echo "   -> Inserting financial record (WITHOUT LOCAL ID) to live PostgreSQL ... ";
            if (!$dryRun) {
                $lapArray = (array)$lap;
                // Skenario B: Remove local ID so Postgres auto-increments
                unset($lapArray['id']); 
                
                // Do NOT insert if it completely duplicates an existing record based on basic fields
                $exists = DB::connection('live')->table('laporan_keuangans')
                            ->where('tanggal', $lapArray['tanggal'])
                            ->where('jumlah', $lapArray['jumlah'])
                            ->where('keterangan', $lapArray['keterangan'])
                            ->exists();
                            
                if (!$exists) {
                    DB::connection('live')->table('laporan_keuangans')->insert($lapArray);
                    echo "SUCCESS.\n";
                    $keuanganPushed++;
                } else {
                    echo "SKIPPED (Already exists).\n";
                }
            } else {
                echo "SIMULATED.\n";
            }
        }
    } catch (\Exception $e) {
        echo "❌ Error during Laporan Keuangan sync: " . $e->getMessage() . "\n";
    }
}

// -----------------------------------------------------------------------------
// SEQUENCE RESETS (POSTGRESQL ONLY)
// -----------------------------------------------------------------------------
if (!$dryRun && ($inventarisCopied > 0 || $productsPushed > 0 || $rekamMedisCopied > 0 || $articlesPushed > 0)) {
    echo "\n=========================================================\n";
    echo " SYNCHRONIZING AUTO-INCREMENT SEQUENCES\n";
    echo "=========================================================\n";

    $sequences = [
        'inventaris' => 'id',
        'produks' => 'id',
        'rekam_medis' => 'id',
        'artikels' => 'id'
    ];

    foreach ($sequences as $table => $column) {
        // Only run for tables that were actually processed if not syncing all
        $shouldReset = $syncAll || 
                       ($table === 'inventaris' && $choice === '2') ||
                       ($table === 'produks' && $choice === '3') ||
                       ($table === 'artikels' && $choice === '4') ||
                       ($table === 'rekam_medis' && $choice === '5');
                       
        if ($shouldReset) {
            echo "Resetting sequence for '{$table}' ... ";
            try {
                // If sequence name is standard table_id_seq:
                $seqName = "{$table}_{$column}_seq";
                DB::connection('live')->statement(
                    "SELECT setval('{$seqName}', coalesce(max({$column}), 0) + 1, false) FROM {$table};"
                );
                echo "SUCCESS.\n";
            } catch (\Exception $seqEx) {
                echo "FAILED (" . $seqEx->getMessage() . ").\n";
            }
        }
    }
}

echo "\n---------------------------------------------------------\n";
if ($dryRun) {
    echo "🏁 Dry-run completed.\n";
} else {
    echo "🏁 Sync completed successfully!\n";
    echo "   - Inventaris copied:  {$inventarisCopied}\n";
    echo "   - Products pushed:    {$productsPushed}\n";
    echo "   - Articles pushed:    {$articlesPushed}\n";
    echo "   - Rekam Medis copied: {$rekamMedisCopied}\n";
    echo "   - Laporan Keuangan:   {$keuanganPushed}\n";
}
echo "=========================================================\n";
