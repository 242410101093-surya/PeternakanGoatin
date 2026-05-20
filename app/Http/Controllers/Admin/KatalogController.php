<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KatalogController extends Controller
{
    public function index()
    {
        $allowedSpecies = ['Domba', 'Kambing Etawa', 'Kambing Kibas'];

        $produks = Produk::with('inventaris')
            ->whereHas('inventaris', function ($q) use ($allowedSpecies) {
                $q->whereIn('jenis', $allowedSpecies);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        $totalProducts = Produk::whereHas('inventaris', function ($q) use ($allowedSpecies) {
            $q->whereIn('jenis', $allowedSpecies);
        })->count();
        $activeListings = Produk::whereHas('inventaris', function($q) use ($allowedSpecies) {
            $q->whereIn('jenis', $allowedSpecies)
              ->where('status_stok', 'Dijual');
        })->count();

        return view('admin.katalog.index', compact('produks', 'totalProducts', 'activeListings'));
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
