<?php

// Bootstrap Laravel
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Produk;
use App\Models\Inventaris;

$allProduk = Produk::with('inventaris')->get();
echo "Total products: " . $allProduk->count() . "\n\n";

foreach ($allProduk as $p) {
    echo "Product: {$p->nama_produk} (ID: {$p->id})\n";
    if ($p->inventaris) {
        echo " - Inventaris ID: {$p->inventaris->id}\n";
        echo " - Jenis: {$p->inventaris->jenis}\n";
        echo " - Status Stok: {$p->inventaris->status_stok}\n";
    } else {
        echo " - No associated inventaris!\n";
    }
    echo "\n";
}

echo "Available inventaris count in DB: " . Inventaris::where('status_stok', 'Tersedia')->count() . "\n";
echo "Booked (Terbooking) inventaris count: " . Inventaris::where('status_stok', 'Terbooking')->count() . "\n";
echo "Sold (Terjual) inventaris count: " . Inventaris::where('status_stok', 'Terjual')->count() . "\n";
echo "In care (Dalam Perawatan) count: " . Inventaris::where('status_stok', 'Dalam Perawatan')->count() . "\n";
