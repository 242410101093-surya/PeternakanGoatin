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
                        ->whereIn('jenis_transaksi', ['Pemasukan', 'Pengiriman Kurir', 'Pesanan Sudah Sampai'])
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
            
        // Calculate chart data for last 6 months
        $chartData = [];
        for ($i = 5; $i >= 0; $i--) {
            $monthStart = Carbon::now()->subMonths($i)->startOfMonth();
            $monthEnd = Carbon::now()->subMonths($i)->endOfMonth();
            $label = $monthStart->translatedFormat('M'); // Jan, Feb, etc.
            
            $revenue = DB::table('laporan_keuangans')
                        ->whereIn('jenis_transaksi', ['Pemasukan', 'Pengiriman Kurir', 'Pesanan Sudah Sampai'])
                        ->whereBetween('tanggal', [$monthStart, $monthEnd])
                        ->sum('jumlah');
            
            $chartData[] = [
                'label' => $label,
                'revenue' => $revenue
            ];
        }
        
        $maxRevenue = max(array_column($chartData, 'revenue'));
        if ($maxRevenue == 0) $maxRevenue = 10000;
        
        $svgPoints = [];
        $xStep = 540 / 5; // 6 points: index 0 to 5
        foreach ($chartData as $index => $data) {
            $x = 30 + ($index * $xStep);
            $y = 160 - (($data['revenue'] / $maxRevenue) * 140);
            $svgPoints[] = [$x, $y, $data['label']];
        }
        
        return view('admin.dashboard', compact('totalUsers', 'labaBersih', 'pendingOrders', 'currentMonth', 'unreadNotifications', 'svgPoints'));
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
                            ->whereIn('jenis_transaksi', ['Pemasukan', 'Pengiriman Kurir', 'Pesanan Sudah Sampai'])
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

    public function rejectNotification(Request $request, $id)
    {
        $notification = \App\Models\Notification::findOrFail($id);
        $notification->update([
            'is_read' => true,
        ]);

        $pesanan = \App\Models\Pesanan::where('notification_id', $notification->id)->first();
        if ($pesanan) {
            $pesanan->update([
                'status' => 'Dibatalkan',
            ]);

            // Return stock to Tersedia
            if ($pesanan->produk && $pesanan->produk->inventaris) {
                $pesanan->produk->inventaris->update(['status_stok' => 'Tersedia']);
            }
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Pesanan berhasil ditolak dan dibatalkan.',
                'pendingOrders' => \App\Models\Notification::where('is_read', false)->count()
            ]);
        }

        return redirect()->route('admin.dashboard')->with('success', 'Pesanan berhasil ditolak.');
    }

    public function checkNew(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $unreadCount = \App\Models\Notification::where('is_read', false)->count();
            
            $latestNotifications = \App\Models\Notification::with('pesanan.produk')
                ->where('is_read', false)
                ->orderBy('created_at', 'desc')
                ->get();

            $modalHtml = '';
            $dashboardHtml = '';
            if (view()->exists('admin.partials.notifications_modal_list')) {
                $modalHtml = view('admin.partials.notifications_modal_list', ['unreadNotifications' => $latestNotifications])->render();
            }
            if (view()->exists('admin.partials.notifications_dashboard_list')) {
                $dashboardHtml = view('admin.partials.notifications_dashboard_list', ['unreadNotifications' => $latestNotifications])->render();
            }

            return response()->json([
                'success' => true,
                'unread_count' => $unreadCount,
                'modal_html' => $modalHtml,
                'dashboard_html' => $dashboardHtml
            ]);
        }
        
        return response()->json(['error' => 'Unauthorized'], 401);
    }
}
