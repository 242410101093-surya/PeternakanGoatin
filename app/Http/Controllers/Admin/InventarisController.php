<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inventaris;
use App\Models\Produk;
use Illuminate\Http\Request;

class InventarisController extends Controller
{
    public function index(Request $request)
    {
        $query = Inventaris::query();
        
        // Search by jenis or ras
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('jenis', 'like', "%{$search}%")
                  ->orWhere('ras', 'like', "%{$search}%");
            });
        }
        
        // Filter by gender
        if ($request->filled('gender')) {
            $query->where('gender', $request->input('gender'));
        }
        
        // Filter by jenis
        if ($request->filled('jenis')) {
            $query->where('jenis', $request->input('jenis'));
        }
        
        // Filter by status
        if ($request->filled('status_stok')) {
            $query->where('status_stok', $request->input('status_stok'));
        }
        
        // Filter by weight range
        $minBerat = $request->input('min_berat');
        $maxBerat = $request->input('max_berat');
        
        if ($request->filled('min_berat')) {
            $query->where('berat', '>=', $minBerat);
        }
        
        if ($request->filled('max_berat')) {
            $query->where('berat', '<=', $maxBerat);
        }
        
        $inventaris = $query->orderBy('created_at', 'desc')->paginate(10);
        $totalLivestock = Inventaris::count();
        $countTersedia = Inventaris::where('status_stok', 'Tersedia')->count();
        $countTerbooking = Inventaris::where('status_stok', 'Terbooking')->count();
        $countTerjual = Inventaris::where('status_stok', 'Terjual')->count();
        $countPerawatan = Inventaris::where('status_stok', 'Dalam Perawatan')->count();
        
        // Get unique jenis for filter dropdown
        $jenisOptions = Inventaris::distinct()->pluck('jenis')->sort();
        
        return view('admin.inventaris.index', compact('inventaris', 'totalLivestock', 'countTersedia', 'countTerbooking', 'countTerjual', 'countPerawatan', 'jenisOptions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis' => 'required|string|max:255',
            'ras' => 'nullable|string|max:255',
            'gender' => 'required|in:Jantan,Betina',
            'umur' => 'required|integer|min:0',
            'berat' => 'required|numeric|min:0',
            'rekam_medis_general' => 'nullable|string',
        ]);

        Inventaris::create($request->all());

        return redirect()->route('admin.inventaris.index')->with('success', 'Inventaris berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'jenis' => 'required|string|max:255',
            'ras' => 'nullable|string|max:255',
            'gender' => 'required|in:Jantan,Betina',
            'umur' => 'required|integer|min:0',
            'berat' => 'required|numeric|min:0',
            'rekam_medis_general' => 'nullable|string',
            'status_stok' => 'required|in:Tersedia,Terbooking,Terjual,Dalam Perawatan',
        ]);

        $inventaris = Inventaris::findOrFail($id);
        $inventaris->update($request->all());

        return redirect()->route('admin.inventaris.index')->with('success', 'Inventaris berhasil diperbarui.');
    }

    public function destroy($id)
    {
        try {
            $inventaris = Inventaris::findOrFail($id);
            
            // Cek apakah inventaris ini terkait dengan Produk (Katalog)
            if (\App\Models\Produk::where('inventaris_id', $inventaris->id)->exists()) {
                return redirect()->route('admin.inventaris.index')->with('error', 'Gagal menghapus! Inventaris ini sedang terdaftar di Katalog Produk.');
            }
            
            $inventaris->delete();
            return redirect()->route('admin.inventaris.index')->with('success', 'Inventaris berhasil dihapus.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Inventaris deletion failed: ' . $e->getMessage());
            return redirect()->route('admin.inventaris.index')->with('error', 'Terjadi kesalahan sistem saat menghapus inventaris.');
        }
    }

    public function jual(Request $request, $id)
    {
        $request->validate([
            'harga' => 'required|numeric|min:0',
            'spesifikasi' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
        ]);

        try {
            \Illuminate\Support\Facades\DB::beginTransaction();
            
            $inventaris = Inventaris::findOrFail($id);

            $fotoPath = null;
            if ($request->hasFile('foto')) {
                $fotoPath = $request->file('foto')->store('produk_fotos', 'public');
            }

            Produk::create([
                'inventaris_id' => $inventaris->id,
                'nama_produk' => $inventaris->jenis . ' ' . ($inventaris->ras ? $inventaris->ras : ''),
                'spesifikasi' => $request->spesifikasi,
                'harga' => $request->harga,
                'foto' => $fotoPath,
            ]);

            $inventaris->update(['status_stok' => 'Tersedia']);
            
            \Illuminate\Support\Facades\DB::commit();
            return redirect()->route('admin.katalog.index')->with('success', 'Hewan berhasil dimasukkan ke katalog.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            \Illuminate\Support\Facades\Log::error('Jual inventaris failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem saat memasukkan hewan ke katalog.');
        }
    }
}
