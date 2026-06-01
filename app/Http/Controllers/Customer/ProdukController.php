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

        $query = Produk::with('inventaris')
            ->whereHas('inventaris', function ($q) use ($allowedSpecies) {
                $q->whereIn('jenis', $allowedSpecies);
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

        $produks = $query->orderBy('created_at', 'desc')->paginate(12);

        return view('customer.produk', compact('produks'));
    }
}
