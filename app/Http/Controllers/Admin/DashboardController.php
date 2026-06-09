<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::where('role', 'user')->count();

        // Laba bersih hanya untuk bulan ini
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $pemasukanBulanIni = DB::table('laporan_keuangans')
                        ->where('jenis_transaksi', 'Pemasukan')
                        ->whereBetween('tanggal', [$startOfMonth, $endOfMonth])
                        ->sum('jumlah');
        $pengeluaranBulanIni = DB::table('laporan_keuangans')
                        ->where('jenis_transaksi', 'Pengeluaran')
                        ->whereBetween('tanggal', [$startOfMonth, $endOfMonth])
                        ->sum('jumlah');
        
        $labaBersih = $pemasukanBulanIni - $pengeluaranBulanIni;
        $currentMonth = Carbon::now()->translatedFormat('F Y');

        $pendingOrders = DB::table('notifications')->where('is_read', false)->count();
        $unreadNotifications = \App\Models\Notification::with('pesanan.produk')
            ->where('is_read', false)
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('admin.dashboard', compact('totalUsers', 'labaBersih', 'pendingOrders', 'currentMonth', 'unreadNotifications'));
    }

    public function markNotificationRead($id)
    {
        $notification = \App\Models\Notification::findOrFail($id);
        $notification->update(['is_read' => true]);

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Notifikasi berhasil ditandai sebagai dibaca.',
                'pendingOrders' => \App\Models\Notification::where('is_read', false)->count()
            ]);
        }

        return back()->with('success', 'Notifikasi berhasil ditandai sebagai dibaca.');
    }

    public function markAllNotificationsRead()
    {
        \App\Models\Notification::where('is_read', false)->update(['is_read' => true]);

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Semua notifikasi berhasil ditandai sebagai dibaca.',
                'pendingOrders' => 0
            ]);
        }

        return back()->with('success', 'Semua notifikasi berhasil ditandai sebagai dibaca.');
    }

    public function confirmNotification(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'harga_jual' => 'required|numeric|min:0',
        ]);

        $notification = \App\Models\Notification::findOrFail($id);
        $notification->update([
            'title' => $request->title,
            'message' => $request->message,
            'is_read' => true,
        ]);

        $pesanan = \App\Models\Pesanan::where('notification_id', $notification->id)->first();
        if ($pesanan) {
            $pesanan->update([
                'status' => 'Disetujui',
                'harga_jual' => $request->harga_jual,
            ]);

            // Also update the stock status of the livestock/inventaris associated with the product to 'Terjual'
            if ($pesanan->produk && $pesanan->produk->inventaris) {
                $pesanan->produk->inventaris->update(['status_stok' => 'Terjual']);
            }
        }

        // Create LaporanKeuangan record
        $keterangan = "Penjualan Ternak: " . ($pesanan && $pesanan->produk ? $pesanan->produk->nama_produk : 'Ternak');
        if ($pesanan && $pesanan->user) {
            $keterangan .= " (Pelanggan: " . $pesanan->user->name . ")";
        }

        \App\Models\LaporanKeuangan::create([
            'tanggal' => now()->toDateString(),
            'jenis_transaksi' => 'Pemasukan',
            'jumlah' => $request->harga_jual,
            'keterangan' => $keterangan,
            'pesanan_id' => $pesanan->id,
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            // Recalculate profit this month
            $startOfMonth = Carbon::now()->startOfMonth();
            $endOfMonth = Carbon::now()->endOfMonth();

            $pemasukanBulanIni = DB::table('laporan_keuangans')
                            ->where('jenis_transaksi', 'Pemasukan')
                            ->whereBetween('tanggal', [$startOfMonth, $endOfMonth])
                            ->sum('jumlah');
            $pengeluaranBulanIni = DB::table('laporan_keuangans')
                            ->where('jenis_transaksi', 'Pengeluaran')
                            ->whereBetween('tanggal', [$startOfMonth, $endOfMonth])
                            ->sum('jumlah');

            $labaBersih = $pemasukanBulanIni - $pengeluaranBulanIni;

            return response()->json([
                'success' => true,
                'message' => 'Pesanan berhasil dikonfirmasi dan dicatat di keuangan.',
                'pendingOrders' => \App\Models\Notification::where('is_read', false)->count(),
                'labaBersih' => number_format($labaBersih, 0, ',', '.')
            ]);
        }

        return redirect()->route('admin.dashboard')->with('success', 'Pesanan berhasil dikonfirmasi dan dicatat di keuangan.');
    }
}
