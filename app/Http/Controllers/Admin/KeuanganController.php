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

        $totalRevenue = (clone $metricsBaseQuery)->where('jenis_transaksi', 'Pemasukan')->sum('jumlah');
        $totalExpenses = (clone $metricsBaseQuery)->where('jenis_transaksi', 'Pengeluaran')->sum('jumlah');
        $netProfit = $totalRevenue - $totalExpenses;

        $hasFilters = $request->hasAny(['search', 'jenis_transaksi', 'tanggal_dari', 'tanggal_sampai', 'min_jumlah', 'max_jumlah']);

        return view('admin.keuangan.index', compact('laporans', 'totalRevenue', 'totalExpenses', 'netProfit', 'hasFilters'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'jenis_transaksi' => 'required|in:Pemasukan,Pengeluaran',
            'jumlah' => 'required|numeric|min:0',
            'keterangan' => 'required|string|max:255',
        ]);

        LaporanKeuangan::create($request->all());

        return redirect()->route('admin.keuangan.index')->with('success', 'Laporan Keuangan berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'jenis_transaksi' => 'required|in:Pemasukan,Pengeluaran',
            'jumlah' => 'required|numeric|min:0',
            'keterangan' => 'required|string|max:255',
        ]);

        $laporan = LaporanKeuangan::findOrFail($id);
        $laporan->update($request->all());

        return redirect()->route('admin.keuangan.index')->with('success', 'Laporan Keuangan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $laporan = LaporanKeuangan::findOrFail($id);
        $laporan->delete();

        return redirect()->route('admin.keuangan.index')->with('success', 'Laporan Keuangan berhasil dihapus.');
    }
}
