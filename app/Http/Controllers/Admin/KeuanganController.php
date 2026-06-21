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
            'nota_pembayaran' => 'required|file|mimes:jpeg,png,jpg,pdf|max:5120',
        ]);

        $data = $request->all();

        if ($request->hasFile('nota_pembayaran')) {
            $disk = config('app.env') === 'production' ? 'supabase' : 'public';
            $file = $request->file('nota_pembayaran');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('nota_pembayaran', $filename, $disk);
            $data['nota_pembayaran'] = $filename;
        }

        LaporanKeuangan::create($data);

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
            'nota_pembayaran' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120',
        ]);

        try {
            \Illuminate\Support\Facades\DB::beginTransaction();

            $data = $request->all();

            if ($request->hasFile('nota_pembayaran')) {
                $disk = config('app.env') === 'production' ? 'supabase' : 'public';
                // Hapus file lama jika ada
                if ($laporan->nota_pembayaran) {
                    \Illuminate\Support\Facades\Storage::disk($disk)->delete('nota_pembayaran/' . $laporan->nota_pembayaran);
                }

                $file = $request->file('nota_pembayaran');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->storeAs('nota_pembayaran', $filename, $disk);
                $data['nota_pembayaran'] = $filename;
            }

            $laporan->update($data);

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

            \Illuminate\Support\Facades\DB::commit();
            return redirect()->route('admin.keuangan.index')->with('success', 'Laporan Keuangan berhasil diperbarui.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            \Illuminate\Support\Facades\Log::error('Keuangan update failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem saat memperbarui laporan.');
        }
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

        try {
            \Illuminate\Support\Facades\DB::beginTransaction();

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

            \Illuminate\Support\Facades\DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Status transaksi dan pengiriman berhasil diperbarui.'
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            \Illuminate\Support\Facades\Log::error('Keuangan updateJenis failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem saat memperbarui status.'
            ], 500);
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            \Illuminate\Support\Facades\DB::beginTransaction();
            $laporan = LaporanKeuangan::findOrFail($id);

            if ($laporan->pesanan_id !== null) {
                $pesanan = $laporan->pesanan;
                if ($pesanan) {
                    // Change Pesanan status
                    $pesanan->update(['status' => 'Dibatalkan']);
                    
                    // Return stock to Tersedia
                    if ($pesanan->produk && $pesanan->produk->inventaris) {
                        $pesanan->produk->inventaris->update(['status_stok' => 'Tersedia']);
                    }
                }

                if ($laporan->nota_pembayaran) {
                    $disk = config('app.env') === 'production' ? 'supabase' : 'public';
                    \Illuminate\Support\Facades\Storage::disk($disk)->delete('nota_pembayaran/' . $laporan->nota_pembayaran);
                }

                $laporan->delete();
                \Illuminate\Support\Facades\DB::commit();
                if ($request->wantsJson() || $request->ajax()) {
                    return response()->json(['success' => true, 'message' => 'Transaksi dibatalkan. Pesanan telah diubah statusnya dan stok ternak dikembalikan.']);
                }
                return redirect()->route('admin.keuangan.index')->with('success', 'Transaksi dibatalkan. Pesanan telah diubah statusnya dan stok ternak dikembalikan.');
            }

            if ($laporan->nota_pembayaran) {
                $disk = config('app.env') === 'production' ? 'supabase' : 'public';
                \Illuminate\Support\Facades\Storage::disk($disk)->delete('nota_pembayaran/' . $laporan->nota_pembayaran);
            }

            $laporan->delete();
            \Illuminate\Support\Facades\DB::commit();
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Laporan Keuangan berhasil dihapus.']);
            }
            return redirect()->route('admin.keuangan.index')->with('success', 'Laporan Keuangan berhasil dihapus.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            \Illuminate\Support\Facades\Log::error('Keuangan destroy failed: ' . $e->getMessage());
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Terjadi kesalahan sistem saat membatalkan transaksi.'], 500);
            }
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem saat membatalkan transaksi.');
        }
    }
}
