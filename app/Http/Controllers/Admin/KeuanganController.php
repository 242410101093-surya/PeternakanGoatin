<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LaporanKeuangan;

class KeuanganController extends Controller
{
    public function index(Request $request)
    {
        $query = LaporanKeuangan::query();

        // Search by keterangan
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('keterangan', 'like', "%{$search}%");
        }

        // Filter by jenis_transaksi
        if ($request->filled('jenis_transaksi')) {
            $query->where('jenis_transaksi', $request->input('jenis_transaksi'));
        }

        // Filter by date range
        if ($request->filled('tanggal_dari')) {
            $query->whereDate('tanggal', '>=', $request->input('tanggal_dari'));
        }

        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('tanggal', '<=', $request->input('tanggal_sampai'));
        }

        // Filter by amount range
        if ($request->filled('min_jumlah')) {
            $query->where('jumlah', '>=', $request->input('min_jumlah'));
        }

        if ($request->filled('max_jumlah')) {
            $query->where('jumlah', '<=', $request->input('max_jumlah'));
        }

        $laporans = $query->orderBy('tanggal', 'desc')->paginate(15);

        // Build filtered metrics query (same filters except jenis_transaksi)
        $metricsBaseQuery = LaporanKeuangan::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $metricsBaseQuery->where('keterangan', 'like', "%{$search}%");
        }
        if ($request->filled('tanggal_dari')) {
            $metricsBaseQuery->whereDate('tanggal', '>=', $request->input('tanggal_dari'));
        }
        if ($request->filled('tanggal_sampai')) {
            $metricsBaseQuery->whereDate('tanggal', '<=', $request->input('tanggal_sampai'));
        }
        if ($request->filled('min_jumlah')) {
            $metricsBaseQuery->where('jumlah', '>=', $request->input('min_jumlah'));
        }
        if ($request->filled('max_jumlah')) {
            $metricsBaseQuery->where('jumlah', '<=', $request->input('max_jumlah'));
        }

        $totalRevenue = (clone $metricsBaseQuery)->whereIn('jenis_transaksi', ['Pemasukan', 'Pengiriman Kurir', 'Pesanan Sudah Sampai'])->sum('jumlah');
        $totalExpenses = (clone $metricsBaseQuery)->where('jenis_transaksi', 'Pengeluaran')->sum('jumlah');
        $netProfit = $totalRevenue - $totalExpenses;

        $hasFilters = $request->hasAny(['search', 'jenis_transaksi', 'tanggal_dari', 'tanggal_sampai', 'min_jumlah', 'max_jumlah']);

        return view('admin.keuangan.index', compact('laporans', 'totalRevenue', 'totalExpenses', 'netProfit', 'hasFilters'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'jenis_transaksi' => 'required|in:Pemasukan,Pengeluaran,Pengiriman Kurir,Pesanan Sudah Sampai',
            'jumlah' => 'required|numeric|min:0',
            'keterangan' => 'required|string|max:255',
        ]);

        LaporanKeuangan::create($request->all());

        return redirect()->route('admin.keuangan.index')->with('success', 'Laporan Keuangan berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $laporan = LaporanKeuangan::findOrFail($id);

        $allowedTypes = ['Pemasukan', 'Pengeluaran', 'Pengiriman Kurir', 'Pesanan Sudah Sampai'];
        if ($laporan->pesanan_id !== null || in_array($laporan->jenis_transaksi, ['Pemasukan', 'Pengiriman Kurir', 'Pesanan Sudah Sampai'])) {
            // If linked to an order or is an automatic transaction type, it cannot be changed to Pengeluaran
            $allowedTypes = ['Pemasukan', 'Pengiriman Kurir', 'Pesanan Sudah Sampai'];
        }

        $request->validate([
            'tanggal' => 'required|date',
            'jenis_transaksi' => 'required|in:' . implode(',', $allowedTypes),
            'jumlah' => 'required|numeric|min:0',
            'keterangan' => 'required|string|max:255',
        ]);

        $laporan->update($request->all());

        // Update corresponding Pesanan status if linked
        if ($laporan->pesanan_id !== null && $laporan->pesanan) {
            $statusMapping = [
                'Pemasukan' => 'Disetujui',
                'Pengiriman Kurir' => 'Pengiriman Kurir',
                'Pesanan Sudah Sampai' => 'Pesanan Sudah Sampai',
            ];
            if (isset($statusMapping[$laporan->jenis_transaksi])) {
                $laporan->pesanan->update([
                    'status' => $statusMapping[$laporan->jenis_transaksi]
                ]);
            }
        }

        return redirect()->route('admin.keuangan.index')->with('success', 'Laporan Keuangan berhasil diperbarui.');
    }

    public function updateJenis(Request $request, $id)
    {
        $laporan = LaporanKeuangan::findOrFail($id);

        if ($laporan->pesanan_id === null) {
            return response()->json([
                'success' => false,
                'message' => 'Transaksi manual tidak dapat diubah jenisnya.'
            ], 422);
        }

        $request->validate([
            'jenis_transaksi' => 'required|in:Pemasukan,Pengiriman Kurir,Pesanan Sudah Sampai',
        ]);

        $laporan->update([
            'jenis_transaksi' => $request->jenis_transaksi
        ]);

        // Update corresponding Pesanan status
        if ($laporan->pesanan) {
            $statusMapping = [
                'Pemasukan' => 'Disetujui',
                'Pengiriman Kurir' => 'Pengiriman Kurir',
                'Pesanan Sudah Sampai' => 'Pesanan Sudah Sampai',
            ];
            if (isset($statusMapping[$laporan->jenis_transaksi])) {
                $laporan->pesanan->update([
                    'status' => $statusMapping[$laporan->jenis_transaksi]
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Status transaksi dan pengiriman berhasil diperbarui.'
        ]);
    }

    public function destroy($id)
    {
        $laporan = LaporanKeuangan::findOrFail($id);
        $laporan->delete();

        return redirect()->route('admin.keuangan.index')->with('success', 'Laporan Keuangan berhasil dihapus.');
    }
}
