<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$inventaris_tersedia = App\Models\Inventaris::where('status_stok', 'Tersedia')->get();
$count = 0;
foreach ($inventaris_tersedia as $inv) {
    if (!App\Models\Produk::where('inventaris_id', $inv->id)->exists()) {
        App\Models\Produk::create([
            'inventaris_id' => $inv->id,
            'nama_produk' => $inv->jenis . ' ' . ($inv->ras ? $inv->ras : ''),
            'spesifikasi' => 'Otomatis ditambahkan dari Inventaris',
            'harga' => 2500000,
            'foto' => null
        ]);
        $count++;
    }
}
echo 'Berhasil menambahkan ' . $count . ' hewan ke Katalog.';
