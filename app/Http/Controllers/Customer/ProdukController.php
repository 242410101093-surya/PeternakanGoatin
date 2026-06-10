<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    public function index(Request $request)
    {
        $allowedSpecies = ['Domba', 'Kambing Etawa', 'Kambing Gibas'];

        $query = Produk::with(['inventaris.rekamMedis'])
            ->whereHas('inventaris', function ($q) use ($allowedSpecies) {
                $q->whereIn('jenis', $allowedSpecies)
                  ->where('status_stok', 'Tersedia');
            });

        // Filter by jenis
        if ($request->filled('jenis')) {
            $query->whereHas('inventaris', function ($q) use ($request) {
                $q->where('jenis', $request->input('jenis'));
            });
        }

        // Filter by umur (age in months)
        $minUmur = $request->input('min_umur');
        $maxUmur = $request->input('max_umur');

        if ($request->filled('min_umur')) {
            $query->whereHas('inventaris', function ($q) use ($minUmur) {
                $q->where('umur', '>=', $minUmur);
            });
        }

        if ($request->filled('max_umur')) {
            $query->whereHas('inventaris', function ($q) use ($maxUmur) {
                $q->where('umur', '<=', $maxUmur);
            });
        }

        // Filter by berat (weight in kg)
        $minBerat = $request->input('min_berat');
        $maxBerat = $request->input('max_berat');

        if ($request->filled('min_berat')) {
            $query->whereHas('inventaris', function ($q) use ($minBerat) {
                $q->where('berat', '>=', $minBerat);
            });
        }

        if ($request->filled('max_berat')) {
            $query->whereHas('inventaris', function ($q) use ($maxBerat) {
                $q->where('berat', '<=', $maxBerat);
            });
        }

        $produks = $query->orderBy('created_at', 'desc')->paginate(8);

        return view('customer.produk', compact('produks'));
    }

    public function notifikasiBeli(Request $request, $id)
    {
        $produk = Produk::with('inventaris')->findOrFail($id);
        $user = auth()->user();

        // Create notification content
        $title = "Permintaan Pembelian Baru";
        $alamatInfo = $user->alamat ? "{$user->alamat} (" . ($user->tipe_alamat ?? 'Lainnya') . ")" : "Belum diisi";
        if ($user->latitude && $user->longitude) {
            $alamatInfo .= "\n- **Titik Koordinat:** [Buka Peta](https://www.google.com/maps/search/?api=1&query={$user->latitude},{$user->longitude}) ({$user->latitude}, {$user->longitude})";
        }

        $message = "Pelanggan **{$user->name}** (WhatsApp: **" . ($user->whatsapp ?? '-') . "**) " .
                   "ingin membeli produk **{$produk->nama_produk}**.\n\n" .
                   "**Detail Ternak:**\n" .
                   "- **ID Ternak:** " . ($produk->inventaris->id ?? '-') . "\n" .
                   "- **Jenis:** " . ($produk->inventaris->jenis ?? '-') . "\n" .
                   "- **Ras:** " . ($produk->inventaris->ras ?? '-') . "\n" .
                   "- **Gender:** " . ($produk->inventaris->gender ?? '-') . "\n" .
                   "- **Umur:** " . ($produk->inventaris->umur ?? '-') . " Bulan\n" .
                   "- **Berat:** " . ($produk->inventaris->berat ?? '-') . " Kg\n" .
                   "- **Harga:** Rp " . number_format($produk->harga, 0, ',', '.') . "\n\n" .
                   "**Detail Pengiriman:**\n" .
                   "- **Alamat:** " . $alamatInfo . "\n\n" .
                   "Pelanggan sedang melakukan chat ke WhatsApp Admin untuk melakukan konfirmasi.";

        $notif = \App\Models\Notification::create([
            'title' => $title,
            'message' => $message,
            'is_read' => false,
        ]);

        \App\Models\Pesanan::create([
            'user_id' => $user->id,
            'produk_id' => $produk->id,
            'notification_id' => $notif->id,
            'harga_jual' => $produk->harga,
            'status' => 'Pending',
            'alamat' => $user->alamat,
            'tipe_alamat' => $user->tipe_alamat,
            'latitude' => $user->latitude,
            'longitude' => $user->longitude,
        ]);

        // Update status_stok to Terbooking
        if ($produk->inventaris) {
            $produk->inventaris->status_stok = 'Terbooking';
            $produk->inventaris->save();
        }

        return response()->json(['status' => 'success']);
    }
}
