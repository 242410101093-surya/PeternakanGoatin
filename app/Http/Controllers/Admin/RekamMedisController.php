<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RekamMedis;
use App\Models\Inventaris;
use App\Models\PertumbuhanTernak;

class RekamMedisController extends Controller
{
    public function index(Request $request)
    {
        $query = RekamMedis::with('inventaris');

        // Search by inventaris jenis or ras
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->whereHas('inventaris', function($q) use ($search) {
                $q->where('jenis', 'like', "%{$search}%")
                  ->orWhere('ras', 'like', "%{$search}%");
            });
        }

        // Filter by specific inventaris
        if ($request->filled('inventaris_id')) {
            $query->where('inventaris_id', $request->input('inventaris_id'));
        }

        // Filter by diagnosis
        if ($request->filled('diagnosis')) {
            $query->where('diagnosis', 'like', '%' . $request->input('diagnosis') . '%');
        }

        // Filter by date range
        if ($request->filled('tanggal_dari')) {
            $query->whereDate('tanggal', '>=', $request->input('tanggal_dari'));
        }

        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('tanggal', '<=', $request->input('tanggal_sampai'));
        }

        $rekamMedis = $query->orderBy('tanggal', 'desc')->get();
        $inventarisList = Inventaris::orderBy('jenis')->get();
        
        // Handle chart data for selected livestock
        $selectedInventarisId = $request->get('inventaris_id', $inventarisList->first()->id ?? null);
        $chartData = [];
        $chartLabels = [];
        
        if ($selectedInventarisId) {
            $pertumbuhan = PertumbuhanTernak::where('inventaris_id', $selectedInventarisId)
                                            ->orderBy('tanggal_pencatatan', 'asc')
                                            ->get();
            $chartLabels = $pertumbuhan->pluck('tanggal_pencatatan')->map(function($date) {
                return \Carbon\Carbon::parse($date)->format('d M Y');
            })->toArray();
            $chartData = $pertumbuhan->pluck('berat')->toArray();
        }

        return view('admin.rekam-medis.index', compact('rekamMedis', 'inventarisList', 'selectedInventarisId', 'chartLabels', 'chartData'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'inventaris_id' => 'required|exists:inventaris,id',
            'tanggal' => 'required|date',
            'dokter_hewan' => 'nullable|string|max:255',
            'diagnosa' => 'required|string|max:255',
            'tindakan' => 'required|string|max:255',
            'status' => 'required|string|max:255',
        ]);

        RekamMedis::create($request->all());

        return redirect()->route('admin.rekam-medis.index')->with('success', 'Rekam Medis berhasil ditambahkan.');
    }

    public function storeBerat(Request $request)
    {
        $request->validate([
            'inventaris_id' => 'required|exists:inventaris,id',
            'tanggal_pencatatan' => 'required|date',
            'berat' => 'required|numeric|min:0',
        ]);

        PertumbuhanTernak::create($request->all());
        
        // Update berat terbaru di tabel inventaris
        $inventaris = Inventaris::find($request->inventaris_id);
        $inventaris->update(['berat' => $request->berat]);

        return redirect()->route('admin.rekam-medis.index', ['inventaris_id' => $request->inventaris_id])
                         ->with('success', 'Data berat badan berhasil dicatat.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'dokter_hewan' => 'nullable|string|max:255',
            'diagnosa' => 'required|string|max:255',
            'tindakan' => 'required|string|max:255',
            'status' => 'required|string|max:255',
        ]);

        $rekam = RekamMedis::findOrFail($id);
        $rekam->update($request->except(['inventaris_id']));

        return redirect()->route('admin.rekam-medis.index')->with('success', 'Rekam Medis berhasil diperbarui.');
    }

    public function destroy($id)
    {
        RekamMedis::findOrFail($id)->delete();
        return redirect()->route('admin.rekam-medis.index')->with('success', 'Rekam Medis berhasil dihapus.');
    }
}
