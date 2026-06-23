<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Artikel;
use Illuminate\Support\Facades\Storage;

class ArtikelController extends Controller
{
    public function index(Request $request)
    {
        $query = Artikel::query();

        // Search by judul or konten
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('konten', 'like', "%{$search}%")
                  ->orWhere('kategori', 'like', "%{$search}%");
            });
        }

        // Filter by kategori
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->input('kategori'));
        }

        $artikels = $query->orderBy('created_at', 'desc')->paginate(10);
        $totalArtikels = Artikel::count();

        // Get unique kategori for filter dropdown
        $kategoriOptions = Artikel::distinct()->pluck('kategori')->filter()->sort();

        return view('admin.artikel.index', compact('artikels', 'totalArtikels', 'kategoriOptions'));
    }

    public function store(Request $request)
    {
        $messages = [
            'foto.image' => 'Format file gambar tidak valid (Gunakan .jpg/.png)',
            'foto.mimes' => 'Format file gambar tidak valid (Gunakan .jpg/.png)',
            'foto.max' => 'Ukuran gambar maksimal 10MB',
        ];

        $request->validate([
            'judul' => 'required|string|max:255',
            'konten' => 'required|string',
            'kategori' => 'nullable|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
        ], $messages);

        $data = $request->except('foto');

        if ($request->hasFile('foto')) {
            try {
                $data['foto'] = $request->file('foto')->store('artikel_fotos');
            } catch (\Exception $e) {
                $pesanError = str_contains($e->getMessage(), 'cURL error 77') 
                    ? 'Gagal mengunggah gambar: Terjadi kendala sertifikat SSL pada server lokal.' 
                    : 'Terjadi kesalahan sistem saat menghubungi server cloud storage.';

                return redirect()->back()->withInput()->with('error', $pesanError);
            }
        }

        Artikel::create($data);

        return redirect()->route('admin.artikel.index')->with('success', 'Artikel berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $messages = [
            'foto.image' => 'Format file gambar tidak valid (Gunakan .jpg/.png)',
            'foto.mimes' => 'Format file gambar tidak valid (Gunakan .jpg/.png)',
            'foto.max' => 'Ukuran gambar maksimal 10MB',
        ];

        $request->validate([
            'judul' => 'required|string|max:255',
            'konten' => 'required|string',
            'kategori' => 'nullable|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
        ], $messages);

        $artikel = Artikel::findOrFail($id);
        $data = $request->except(['foto', 'hapus_foto']);

        try {
            if ($request->input('hapus_foto') == '1') {
                if ($artikel->foto && Storage::exists($artikel->foto)) {
                    Storage::delete($artikel->foto);
                }
                $data['foto'] = null;
            }

            if ($request->hasFile('foto')) {
                if ($artikel->foto && Storage::exists($artikel->foto)) {
                    Storage::delete($artikel->foto);
                }
                $data['foto'] = $request->file('foto')->store('artikel_fotos');
            }
        } catch (\Exception $e) {
            $pesanError = str_contains($e->getMessage(), 'cURL error 77') 
                ? 'Gagal memproses gambar: Terjadi kendala sertifikat SSL pada server lokal.' 
                : 'Terjadi kesalahan sistem saat menghubungi server cloud storage.';

            return redirect()->back()->withInput()->with('error', $pesanError);
        }

        $artikel->update($data);

        return redirect()->route('admin.artikel.index')->with('success', 'Artikel berhasil diperbarui.');
    }

    public function destroy(Request $request, $id)
    {
        $artikel = Artikel::findOrFail($id);

        try {
            if ($artikel->foto && Storage::exists($artikel->foto)) {
                Storage::delete($artikel->foto);
            }
        } catch (\Exception $e) {
            $pesanError = str_contains($e->getMessage(), 'cURL error 77') 
                ? 'Gagal menghapus gambar: Terjadi kendala sertifikat SSL pada server lokal.' 
                : 'Terjadi kesalahan sistem saat menghubungi server cloud storage.';

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => $pesanError], 500);
            }
            return redirect()->route('admin.artikel.index')->with('error', $pesanError);
        }

        $artikel->delete();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Artikel berhasil dihapus.']);
        }
        return redirect()->route('admin.artikel.index')->with('success', 'Artikel berhasil dihapus.');
    }

    public function hapusFoto(Request $request, $id)
    {
        $artikel = Artikel::findOrFail($id);

        try {
            if ($artikel->foto && Storage::exists($artikel->foto)) {
                Storage::delete($artikel->foto);
            }
        } catch (\Exception $e) {
            $pesanError = str_contains($e->getMessage(), 'cURL error 77') 
                ? 'Gagal menghapus gambar: Terjadi kendala sertifikat SSL pada server lokal.' 
                : 'Terjadi kesalahan sistem saat menghubungi server cloud storage.';

            return response()->json(['success' => false, 'message' => $pesanError], 500);
        }

        $artikel->update(['foto' => null]);

        return response()->json(['success' => true, 'message' => 'Foto artikel berhasil dihapus.']);
    }
}
