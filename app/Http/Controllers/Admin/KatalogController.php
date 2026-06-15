<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inventaris;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KatalogController extends Controller
{
    public function index(Request $request)
    {
        $allowedSpecies = ['Domba', 'Kambing Etawa', 'Kambing Gibas'];

        $query = Produk::with('inventaris')
            ->whereHas('inventaris', function ($q) use ($allowedSpecies) {
                $q->whereIn('jenis', $allowedSpecies)
                  ->where('status_stok', '!=', 'Terjual');
            });

        // Search by product name, ras, or spesifikasi
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('nama_produk', 'like', "%{$search}%")
                  ->orWhere('spesifikasi', 'like', "%{$search}%")
                  ->orWhereHas('inventaris', function($q2) use ($search) {
                      $q2->where('ras', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by jenis
        if ($request->filled('jenis')) {
            $query->whereHas('inventaris', function ($q) use ($request) {
                $q->where('jenis', $request->input('jenis'));
            });
        }

        // Filter by gender
        if ($request->filled('gender')) {
            $query->whereHas('inventaris', function ($q) use ($request) {
                $q->where('gender', $request->input('gender'));
            });
        }

        // Filter by price range
        if ($request->filled('min_harga')) {
            $query->where('harga', '>=', $request->input('min_harga'));
        }

        if ($request->filled('max_harga')) {
            $query->where('harga', '<=', $request->input('max_harga'));
        }

        $produks = $query->orderBy('created_at', 'desc')->paginate(12);

        $totalProducts = Produk::whereHas('inventaris', function ($q) use ($allowedSpecies) {
            $q->whereIn('jenis', $allowedSpecies)
              ->where('status_stok', '!=', 'Terjual');
        })->count();

        // Listing Aktif = produk di katalog yang masih berstatus Tersedia (terlihat oleh customer)
        $activeListings = Produk::whereHas('inventaris', function($q) use ($allowedSpecies) {
            $q->whereIn('jenis', $allowedSpecies)
              ->where('status_stok', 'Tersedia');
        })->count();

        // Terbooking = produk yang sudah dipilih customer (tidak muncul di catalog customer)
        $lowStockAlerts = Produk::whereHas('inventaris', function($q) use ($allowedSpecies) {
            $q->whereIn('jenis', $allowedSpecies)
              ->where('status_stok', 'Terbooking');
        })->count();

        // Get unique jenis for filter dropdown
        $jenisOptions = Inventaris::whereIn('jenis', $allowedSpecies)->distinct()->pluck('jenis')->sort();

        return view('admin.katalog.index', compact('produks', 'totalProducts', 'activeListings', 'lowStockAlerts', 'jenisOptions'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'harga' => 'required|numeric|min:0',
            'spesifikasi' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
        ]);

        try {
            $produk = Produk::findOrFail($id);
            $data = $request->only(['harga', 'spesifikasi']);

            if ($request->hasFile('foto')) {
                if ($produk->foto && Storage::disk('public')->exists($produk->foto)) {
                    Storage::disk('public')->delete($produk->foto);
                }
                $data['foto'] = $request->file('foto')->store('produk_fotos', 'supabase');
            }

            $produk->update($data);
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => true, 'message' => 'Katalog berhasil diperbarui.']);
            }
            return redirect()->route('admin.katalog.index')->with('success', 'Katalog berhasil diperbarui.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Katalog update failed: ' . $e->getMessage());
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Terjadi kesalahan sistem saat memperbarui katalog.'], 500);
            }
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem saat memperbarui katalog.');
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            \Illuminate\Support\Facades\DB::beginTransaction();
            $produk = Produk::findOrFail($id);

            // Cek apakah produk ini ada di pesanan yang aktif
            if (\App\Models\Pesanan::where('produk_id', $produk->id)->exists()) {
                if ($request->wantsJson() || $request->ajax()) {
                    return response()->json(['success' => false, 'message' => 'Gagal menghapus! Produk ini sedang terkait dengan sebuah Pesanan.'], 400);
                }
                return redirect()->route('admin.katalog.index')->with('error', 'Gagal menghapus! Produk ini sedang terkait dengan sebuah Pesanan.');
            }

            if ($produk->inventaris) {
                $produk->inventaris->update(['status_stok' => 'Dalam Perawatan']); // Kembalikan ke perawatan, bukan tersedia
            }

            if ($produk->foto && Storage::disk('public')->exists($produk->foto)) {
                Storage::disk('public')->delete($produk->foto);
            }

            $produk->delete();
            \Illuminate\Support\Facades\DB::commit();
            
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Produk dihapus dari katalog.']);
            }
            return redirect()->route('admin.katalog.index')->with('success', 'Produk dihapus dari katalog.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            \Illuminate\Support\Facades\Log::error('Katalog destroy failed: ' . $e->getMessage());
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Terjadi kesalahan sistem saat menghapus produk.'], 500);
            }
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem saat menghapus produk.');
        }
    }
}
