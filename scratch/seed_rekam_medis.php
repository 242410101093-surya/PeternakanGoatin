<?php

// Bootstrap Laravel
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\RekamMedis;
use App\Models\Inventaris;

// Check if inventaris with ID 15 exists
$inventaris = Inventaris::find(15);
if (!$inventaris) {
    echo "Error: Inventaris with ID 15 not found!\n";
    exit(1);
}

echo "Found Inventaris: " . $inventaris->jenis . " (ID: " . $inventaris->id . ")\n";

// Add some medical records for ID 15
$records = [
    [
        'inventaris_id' => 15,
        'tanggal' => '2026-05-10',
        'dokter_hewan' => 'Drh. Siti',
        'diagnosa' => 'Flu Ringan & Kurang Nafsu Makan',
        'tindakan' => 'Pemberian multivitamin & obat antibiotik oral',
        'status' => 'Masa Pemulihan',
    ],
    [
        'inventaris_id' => 15,
        'tanggal' => '2026-05-24',
        'dokter_hewan' => 'Drh. Siti',
        'diagnosa' => 'Pemeriksaan Rutin Pasca Flu',
        'tindakan' => 'Pemberian obat cacing rutin',
        'status' => 'Sehat',
    ],
    [
        'inventaris_id' => 15,
        'tanggal' => '2026-06-05',
        'dokter_hewan' => 'Drh. Andi',
        'diagnosa' => 'Vaksinasi PMK Tahunan',
        'tindakan' => 'Suntik Vaksin PMK dosis 2ml',
        'status' => 'Sehat',
    ]
];

foreach ($records as $recordData) {
    $record = RekamMedis::create($recordData);
    echo "Created medical record ID: " . $record->id . " on " . $record->tanggal . "\n";
}

echo "Done seeding rekam medis for ID 15!\n";
