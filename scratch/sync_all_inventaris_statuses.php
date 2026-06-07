<?php

// Bootstrap Laravel
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\RekamMedis;
use App\Models\Inventaris;

$animals = Inventaris::all();
$updatedCount = 0;

foreach ($animals as $animal) {
    // Get the latest rekam medis by date/time
    $latestRekam = RekamMedis::where('inventaris_id', $animal->id)
                             ->orderBy('tanggal', 'desc')
                             ->orderBy('id', 'desc')
                             ->first();

    if ($latestRekam) {
        $statusLower = strtolower($latestRekam->status);
        
        // If latest rekam medis indicates recovery or illness
        if (str_contains($statusLower, 'pemulihan') || str_contains($statusLower, 'perawatan') || str_contains($statusLower, 'sakit') || str_contains($statusLower, 'flu')) {
            if ($animal->status_stok !== 'Dalam Perawatan') {
                $animal->status_stok = 'Dalam Perawatan';
                $animal->save();
                $updatedCount++;
                echo "Updated animal ID {$animal->id} ({$animal->jenis}) status_stok to 'Dalam Perawatan' (Latest medical record: '{$latestRekam->status}')\n";
            }
        } else if ($statusLower === 'sehat') {
            // If latest medical record is healthy, but status was 'Dalam Perawatan'
            if ($animal->status_stok === 'Dalam Perawatan') {
                $animal->status_stok = 'Tersedia';
                $animal->save();
                $updatedCount++;
                echo "Restored animal ID {$animal->id} ({$animal->jenis}) status_stok to 'Tersedia' (Latest medical record: 'Sehat')\n";
            }
        }
    }
}

echo "Sync complete! Updated {$updatedCount} animal status fields.\n";
