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
        
        if ($request->ajax()) {
            return view('admin.inventaris.partials.table', compact('inventaris'))->render();
        }

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
            'status_stok' => 'required|in:Tersedia,Terbooking,Terjual,Dalam Perawatan',
        ]);

        Inventaris::create($request->all());

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Inventaris berhasil ditambahkan.']);
        }

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

        if ($request->status_stok === 'Terjual' && $inventaris->status_stok !== 'Terjual') {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Status "Terjual" hanya dapat diubah secara otomatis saat ada transaksi pesanan yang disetujui.'], 400);
            }
            return redirect()->back()->with('error', 'Status "Terjual" hanya dapat diubah secara otomatis saat ada transaksi pesanan yang disetujui.');
        }

        $inventaris->update($request->all());

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Inventaris berhasil diperbarui.']);
        }

        return redirect()->route('admin.inventaris.index')->with('success', 'Inventaris berhasil diperbarui.');
    }

    public function destroy(Request $request, $id)
    {
        try {
            $inventaris = Inventaris::findOrFail($id);
            
            // Cek apakah inventaris ini terkait dengan Produk (Katalog)
            if (\App\Models\Produk::where('inventaris_id', $inventaris->id)->exists()) {
                if ($request->wantsJson() || $request->ajax()) {
                    return response()->json(['success' => false, 'message' => 'Gagal menghapus! Inventaris ini sedang terdaftar di Katalog Produk.'], 400);
                }
                return redirect()->route('admin.inventaris.index')->with('error', 'Gagal menghapus! Inventaris ini sedang terdaftar di Katalog Produk.');
            }
            
            $inventaris->delete();
            
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Inventaris berhasil dihapus.']);
            }
            return redirect()->route('admin.inventaris.index')->with('success', 'Inventaris berhasil dihapus.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Inventaris deletion failed: ' . $e->getMessage());
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Terjadi kesalahan sistem saat menghapus inventaris.'], 500);
            }
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
                $fotoPath = $request->file('foto')->store('produk_fotos', 'supabase');
            }

            Produk::create([
                'inventaris_id' => $inventaris->id,
                'nama_produk' => $inventaris->jenis . ' ' . $inventaris->gender . ' (Umur: ' . $inventaris->umur . ' bln)',
                'spesifikasi' => $request->spesifikasi,
                'harga' => $request->harga,
                'foto' => $fotoPath,
            ]);

            $inventaris->update(['status_stok' => 'Tersedia']);
            
            \Illuminate\Support\Facades\DB::commit();
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => true, 'message' => 'Hewan berhasil dimasukkan ke katalog.']);
            }
            return redirect()->route('admin.inventaris.index')->with('success', 'Hewan berhasil dimasukkan ke katalog.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            
            $pesanError = str_contains($e->getMessage(), 'cURL error 77') 
                ? 'Gagal memasukkan hewan ke katalog: Terjadi kendala sertifikat SSL pada server lokal.' 
                : 'Gagal memasukkan hewan ke katalog: Terjadi kesalahan sistem saat menghubungi server cloud storage.';

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $pesanError], 500);
            }
            return back()->with('error', $pesanError);
        }
    }
}
