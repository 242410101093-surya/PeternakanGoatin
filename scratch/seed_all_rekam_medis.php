<?php

// Bootstrap Laravel
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\RekamMedis;
use App\Models\Inventaris;

// Fetch all inventaris records
$animals = Inventaris::all();
echo "Found " . $animals->count() . " animals in inventaris table.\n";

$doctors = ['Drh. Siti', 'Drh. Andi', 'Drh. Budi', 'Drh. Rina'];

$templates = [
    [
        'diagnosa' => 'Flu Ringan & Pilek',
        'tindakan' => 'Pemberian antibiotik oral dan vitamin penambah nafsu makan',
        'status' => 'Masa Pemulihan',
    ],
    [
        'diagnosa' => 'Kondisi Sehat & Bugar',
        'tindakan' => 'Pemeriksaan rutin berkala, tidak ada tindakan khusus',
        'status' => 'Sehat',
    ],
    [
        'diagnosa' => 'Vaksinasi Rutin PMK',
        'tindakan' => 'Pemberian vaksin PMK dosis 2ml secara intramuskular',
        'status' => 'Sehat',
    ],
    [
        'diagnosa' => 'Kecacingan Ringan',
        'tindakan' => 'Pemberian obat cacing albendazole dan vitamin B-kompleks',
        'status' => 'Masa Pemulihan',
    ],
    [
        'diagnosa' => 'Luka Gores Ringan di Kaki',
        'tindakan' => 'Pembersihan luka dengan antiseptik dan pemberian salep antibiotik',
        'status' => 'Masa Pemulihan',
    ],
    [
        'diagnosa' => 'Pemeriksaan Kehamilan / Rutin',
        'tindakan' => 'Pemeriksaan fisik umum dan suplemen mineral tambahan',
        'status' => 'Sehat',
    ]
];

$countCreated = 0;

foreach ($animals as $animal) {
    // Check current rekam medis count
    $existingCount = $animal->rekamMedis()->count();
    
    // We want at least 1-2 records for every animal
    $targetRecords = ($existingCount > 0) ? 1 : rand(1, 2);
    
    for ($i = 0; $i < $targetRecords; $i++) {
        $template = $templates[array_rand($templates)];
        $doctor = $doctors[array_rand($doctors)];
        
        // Random date within the last 4 months
        $daysAgo = rand(5, 120);
        $date = date('Y-m-d', strtotime("-$daysAgo days"));
        
        $record = RekamMedis::create([
            'inventaris_id' => $animal->id,
            'tanggal' => $date,
            'dokter_hewan' => $doctor,
            'diagnosa' => $template['diagnosa'],
            'tindakan' => $template['tindakan'],
            'status' => $template['status'],
        ]);
        
        $countCreated++;
    }
    
    echo "Processed animal ID {$animal->id} ({$animal->jenis}): now has " . $animal->rekamMedis()->count() . " records.\n";
}

echo "Successfully created {$countCreated} new medical records across all animals!\n";
