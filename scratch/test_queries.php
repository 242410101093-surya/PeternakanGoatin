<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$start = \Carbon\Carbon::now()->startOfMonth();
$end = \Carbon\Carbon::now()->endOfMonth();

$dashboardPemasukan = \Illuminate\Support\Facades\DB::table('laporan_keuangans')
    ->whereIn('jenis_transaksi', ['Pemasukan', 'Pengiriman Kurir', 'Pesanan Sudah Sampai'])
    ->whereBetween('tanggal', [$start, $end])
    ->sum('jumlah');

$dashboardPengeluaran = \Illuminate\Support\Facades\DB::table('laporan_keuangans')
    ->where('jenis_transaksi', 'Pengeluaran')
    ->whereBetween('tanggal', [$start, $end])
    ->sum('jumlah');

$dashboardLaba = $dashboardPemasukan - $dashboardPengeluaran;

echo "Dashboard Laba: " . $dashboardLaba . "\n";

$metricsBaseQuery = \App\Models\LaporanKeuangan::query();

// Mock request dates that match UI
$tanggal_dari = '2026-06-01';
$tanggal_sampai = '2026-06-30';

$metricsBaseQuery->whereDate('tanggal', '>=', $tanggal_dari);
$metricsBaseQuery->whereDate('tanggal', '<=', $tanggal_sampai);

$totalRevenue = (clone $metricsBaseQuery)->whereIn('jenis_transaksi', ['Pemasukan', 'Pengiriman Kurir', 'Pesanan Sudah Sampai'])->sum('jumlah');
$totalExpenses = (clone $metricsBaseQuery)->where('jenis_transaksi', 'Pengeluaran')->sum('jumlah');

$keuanganLaba = $totalRevenue - $totalExpenses;

echo "Keuangan Laba: " . $keuanganLaba . "\n";
