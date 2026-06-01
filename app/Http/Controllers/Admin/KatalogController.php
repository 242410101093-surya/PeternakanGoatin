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
                $q->whereIn('jenis', $allowedSpecies);
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
            $q->whereIn('jenis', $allowedSpecies);
        })->count();
        $activeListings = Produk::whereHas('inventaris', function($q) use ($allowedSpecies) {
            $q->whereIn('jenis', $allowedSpecies)
              ->where('status_stok', 'Dijual');
        })->count();
        $lowStockAlerts = Inventaris::lowStockCount();

        // Get unique jenis for filter dropdown
        $jenisOptions = Inventaris::whereIn('jenis', $allowedSpecies)->distinct()->pluck('jenis')->sort();

        return view('admin.katalog.index', compact('produks', 'totalProducts', 'activeListings', 'lowStockAlerts', 'jenisOptions'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'harga' => 'required|numeric|min:0',
            'spesifikasi' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $produk = Produk::findOrFail($id);

        $data = $request->only(['harga', 'spesifikasi']);

        if ($request->hasFile('foto')) {
            if ($produk->foto && Storage::disk('public')->exists($produk->foto)) {
                Storage::disk('public')->delete($produk->foto);
            }
            $data['foto'] = $request->file('foto')->store('produk_fotos', 'public');
        }

        $produk->update($data);

        return redirect()->route('admin.katalog.index')->with('success', 'Katalog berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $produk = Produk::findOrFail($id);

        if ($produk->inventaris) {
            $produk->inventaris->update(['status_stok' => 'Tersedia']);
        }

        if ($produk->foto && Storage::disk('public')->exists($produk->foto)) {
            Storage::disk('public')->delete($produk->foto);
        }

        $produk->delete();

        return redirect()->route('admin.katalog.index')->with('success', 'Produk dihapus dari katalog.');
    }
}
