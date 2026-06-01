@extends('layouts.admin')

@section('title', 'Inventory Management')

@section('content')
<div class="w-full px-margin-mobile md:px-margin-desktop">
    <div class="max-w-container-max mx-auto space-y-stack-xl">
<!-- Page Header -->
<div class="flex flex-col md:flex-row md:items-end justify-between mb-stack-lg gap-stack-md">
    <div>
        <h1 class="font-h1 text-h1 text-on-surface tracking-tight mb-2">Inventory Management</h1>
        <p class="font-body-md text-body-md text-on-surface-variant max-w-2xl">Monitor livestock counts, track feed stock levels, and manage pricing across all agricultural assets in real-time.</p>
    </div>
    <button onclick="document.getElementById('addModal').classList.remove('hidden')" class="inline-flex items-center justify-center gap-2 bg-primary hover:bg-surface-tint text-on-primary px-6 py-3 rounded-lg font-label-sm text-label-sm transition-all shadow-[0_4px_14px_rgba(74,124,89,0.15)] hover:shadow-[0_6px_20px_rgba(74,124,89,0.2)] whitespace-nowrap">
        <span class="material-symbols-outlined text-[18px]" style="font-variation-settings: 'FILL' 1;">add</span>
        Tambah Inventaris
    </button>
</div>

<!-- Summary Bento Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6 relative overflow-hidden group hover:shadow-[0_8px_24px_rgba(74,124,89,0.08)] transition-all duration-300">
        <div class="absolute top-0 right-0 p-6 opacity-10 group-hover:opacity-20 transition-opacity">
            <span class="material-symbols-outlined text-6xl text-primary">pets</span>
        </div>
        <p class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider mb-2">Total Livestock</p>
        <h3 class="font-h2 text-h2 text-on-surface mb-1">{{ number_format($totalLivestock) }} <span class="font-body-md text-body-md text-on-surface-variant font-normal">Head</span></h3>
        <p class="font-caption text-caption text-primary flex items-center gap-1">
            <span class="material-symbols-outlined text-[14px]">trending_up</span> +12 this month
        </p>
    </div>
    <div class="bg-surface-container-lowest border border-tertiary rounded-xl p-6 relative overflow-hidden group hover:shadow-[0_8px_24px_rgba(170,93,42,0.08)] transition-all duration-300">
        <div class="absolute top-0 right-0 p-6 opacity-10 group-hover:opacity-20 transition-opacity">
            <span class="material-symbols-outlined text-6xl text-tertiary">warning</span>
        </div>
        <p class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider mb-2">{{ $lowStockAlerts > 20 ? 'Stock' : 'Low Stock Alerts' }}</p>
        <h3 class="font-h2 text-h2 text-tertiary mb-1">{{ number_format($lowStockAlerts) }} <span class="font-body-md text-body-md text-on-surface-variant font-normal">Items</span></h3>
        <p class="font-caption text-caption text-tertiary flex items-center gap-1">
            <span class="material-symbols-outlined text-[14px]">inventory</span> Action required
        </p>
    </div>
</div>

<!-- Main Inventory List -->
<div class="bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden shadow-[0_4px_16px_rgba(0,0,0,0.02)]">
    <!-- Table Header Actions -->
    <div class="p-6 border-b border-surface-variant flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-surface-bright">
        <h2 class="font-h3 text-h3 text-on-surface">Asset Roster</h2>
        <div class="flex items-center gap-3 flex-1 sm:flex-none">
            <form method="GET" action="{{ route('admin.inventaris.index') }}" class="flex items-center gap-3 w-full sm:w-auto">
                <div class="relative flex-1 sm:flex-none">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-[20px]">search</span>
                    <input class="pl-10 pr-4 py-2 border border-outline-variant rounded-lg bg-surface-container-lowest text-on-surface font-body-md text-sm focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all w-full sm:w-64" 
                           placeholder="Cari jenis atau ras..." 
                           type="text"
                           name="search"
                           value="{{ request('search') }}"/>
                </div>
                <button type="button" onclick="document.getElementById('filterPanel').classList.toggle('hidden')" class="p-2 border border-outline-variant rounded-lg text-on-surface-variant hover:bg-surface-container transition-colors flex items-center justify-center whitespace-nowrap">
                    <span class="material-symbols-outlined">filter_list</span>
                </button>
                <button type="submit" class="p-2 border border-primary rounded-lg text-primary hover:bg-primary-container transition-colors flex items-center justify-center whitespace-nowrap">
                    <span class="material-symbols-outlined">search</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Filter Panel -->
    <div id="filterPanel" class="hidden p-6 border-b border-surface-variant bg-surface-container-low">
        <form method="GET" action="{{ route('admin.inventaris.index') }}" id="filterForm" class="space-y-4">
            @if(request('search'))
                <input type="hidden" name="search" value="{{ request('search') }}">
            @endif
            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Gender Filter -->
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant mb-2">Gender</label>
                    <select name="gender" class="w-full px-3 py-2 rounded-lg border border-outline-variant focus:border-primary bg-surface-bright text-on-surface font-body-sm">
                        <option value="">Semua Gender</option>
                        <option value="Jantan" {{ request('gender') == 'Jantan' ? 'selected' : '' }}>Jantan (Laki-laki)</option>
                        <option value="Betina" {{ request('gender') == 'Betina' ? 'selected' : '' }}>Betina (Perempuan)</option>
                    </select>
                </div>

                <!-- Jenis Filter -->
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant mb-2">Jenis Ternak</label>
                    <select name="jenis" class="w-full px-3 py-2 rounded-lg border border-outline-variant focus:border-primary bg-surface-bright text-on-surface font-body-sm">
                        <option value="">Semua Jenis</option>
                        @foreach($jenisOptions as $jenis)
                            <option value="{{ $jenis }}" {{ request('jenis') == $jenis ? 'selected' : '' }}>{{ $jenis }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Min Weight Filter -->
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant mb-2">Berat Min (KG)</label>
                    <input type="number" step="0.1" name="min_berat" placeholder="0" class="w-full px-3 py-2 rounded-lg border border-outline-variant focus:border-primary bg-surface-bright text-on-surface font-body-sm" value="{{ request('min_berat') }}">
                </div>

                <!-- Max Weight Filter -->
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant mb-2">Berat Max (KG)</label>
                    <input type="number" step="0.1" name="max_berat" placeholder="1000" class="w-full px-3 py-2 rounded-lg border border-outline-variant focus:border-primary bg-surface-bright text-on-surface font-body-sm" value="{{ request('max_berat') }}">
                </div>
            </div>

            <!-- Filter Actions -->
            <div class="flex gap-3 justify-end pt-4 border-t border-surface-variant">
                <a href="{{ route('admin.inventaris.index') }}" class="px-4 py-2 font-label-sm text-label-sm text-on-surface-variant hover:bg-surface-container rounded-lg transition-colors border border-outline-variant">Reset Filter</a>
                <button type="submit" class="px-4 py-2 font-label-sm text-label-sm bg-primary text-on-primary hover:bg-primary-container rounded-lg transition-colors shadow-sm">Terapkan Filter</button>
            </div>
        </form>
    </div>
    
    <!-- Table Container -->
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-surface-container-low border-b border-surface-variant">
                    <th class="px-6 py-4 font-label-sm text-label-sm text-on-surface-variant font-semibold">Jenis & Ras</th>
                    <th class="px-6 py-4 font-label-sm text-label-sm text-on-surface-variant font-semibold">Gender</th>
                    <th class="px-6 py-4 font-label-sm text-label-sm text-on-surface-variant font-semibold">Umur (Bulan)</th>
                    <th class="px-6 py-4 font-label-sm text-label-sm text-on-surface-variant font-semibold">Bobot (Kg)</th>
                    <th class="px-6 py-4 font-label-sm text-label-sm text-on-surface-variant font-semibold">Status</th>
                    <th class="px-6 py-4 font-label-sm text-label-sm text-on-surface-variant font-semibold text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-surface-variant">
                @forelse ($inventaris as $item)
                <tr class="hover:bg-surface-container/50 transition-colors group">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-4">
                            <div class="h-10 w-10 rounded-full bg-primary-container/20 flex items-center justify-center flex-shrink-0 text-primary">
                                <span class="material-symbols-outlined">pets</span>
                            </div>
                            <div>
                                <p class="font-body-md text-body-md text-on-surface font-semibold">{{ $item->jenis }}</p>
                                <p class="font-caption text-caption text-on-surface-variant mt-0.5">Ras: {{ $item->ras ?? '-' }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 font-body-md text-body-md text-on-surface">
                        {{ $item->gender }}
                    </td>
                    <td class="px-6 py-4 font-body-md text-body-md text-on-surface">
                        {{ $item->umur }} Bulan
                    </td>
                    <td class="px-6 py-4 font-body-md text-body-md text-on-surface font-medium">
                        {{ $item->berat }} Kg
                    </td>
                    <td class="px-6 py-4">
                        @php
                            $statusColor = 'bg-secondary-container/50 text-on-secondary-container border-secondary/20';
                            if ($item->status_stok == 'Terjual') $statusColor = 'bg-surface-variant text-on-surface-variant border-outline-variant';
                            if ($item->status_stok == 'Dijual') $statusColor = 'bg-primary-container/30 text-primary-container border-primary/20';
                            if ($item->status_stok == 'Dalam Perawatan') $statusColor = 'bg-tertiary-container/30 text-tertiary-container border-tertiary/20';
                        @endphp
                        <div class="inline-flex items-center px-2.5 py-1 rounded-full {{ $statusColor }} border">
                            <span class="font-label-sm text-label-sm">{{ $item->status_stok }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                            @if($item->status_stok == 'Tersedia' || $item->status_stok == 'Dalam Perawatan')
                            <button onclick="openJualModal({{ $item }})" class="p-1.5 text-primary hover:bg-primary-container/20 rounded" title="Jual">
                                <span class="material-symbols-outlined text-sm">storefront</span>
                            </button>
                            @endif
                            <button onclick="openEditModal({{ $item }})" class="p-1.5 text-on-surface-variant hover:text-primary rounded" title="Edit">
                                <span class="material-symbols-outlined text-sm">edit</span>
                            </button>
                            <form action="{{ route('admin.inventaris.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus data ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 text-on-surface-variant hover:text-error rounded" title="Hapus">
                                    <span class="material-symbols-outlined text-sm">delete</span>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-on-surface-variant">
                        Belum ada data inventaris.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Table Footer / Pagination -->
    <div class="px-6 py-4 border-t border-surface-variant bg-surface-container-lowest">
        {{ $inventaris->links() }}
    </div>
</div>

<!-- Modal Tambah Inventaris -->
<div id="addModal" class="fixed inset-0 z-50 hidden bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-surface-container-lowest rounded-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto shadow-2xl border border-surface-variant">
        <div class="p-6 border-b border-surface-variant flex items-center justify-between sticky top-0 bg-surface-container-lowest z-10">
            <h3 class="font-h3 text-h3 text-on-surface">Tambah Inventaris</h3>
            <button onclick="document.getElementById('addModal').classList.add('hidden')" class="text-on-surface-variant hover:text-error transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form action="{{ route('admin.inventaris.store') }}" method="POST" class="p-6 space-y-4">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant mb-1">Jenis Hewan</label>
                    <input type="text" name="jenis" required class="w-full px-3 py-2 rounded-lg border border-outline-variant focus:border-primary bg-surface-bright text-on-surface" placeholder="mis. Kambing">
                </div>
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant mb-1">Ras</label>
                    <input type="text" name="ras" class="w-full px-3 py-2 rounded-lg border border-outline-variant focus:border-primary bg-surface-bright text-on-surface" placeholder="mis. Etawa">
                </div>
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant mb-1">Gender</label>
                    <select name="gender" required class="w-full px-3 py-2 rounded-lg border border-outline-variant focus:border-primary bg-surface-bright text-on-surface">
                        <option value="Jantan">Jantan</option>
                        <option value="Betina">Betina</option>
                    </select>
                </div>
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant mb-1">Umur (Bulan)</label>
                    <input type="number" name="umur" required min="0" class="w-full px-3 py-2 rounded-lg border border-outline-variant focus:border-primary bg-surface-bright text-on-surface">
                </div>
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant mb-1">Bobot (Kg)</label>
                    <input type="number" step="0.01" name="berat" required min="0" class="w-full px-3 py-2 rounded-lg border border-outline-variant focus:border-primary bg-surface-bright text-on-surface">
                </div>
            </div>
            <div>
                <label class="block font-label-sm text-label-sm text-on-surface-variant mb-1">Rekam Medis General</label>
                <textarea name="rekam_medis_general" rows="3" class="w-full px-3 py-2 rounded-lg border border-outline-variant focus:border-primary bg-surface-bright text-on-surface" placeholder="Kondisi kesehatan umum..."></textarea>
            </div>
            <div class="pt-4 flex justify-end gap-3 border-t border-surface-variant">
                <button type="button" onclick="document.getElementById('addModal').classList.add('hidden')" class="px-4 py-2 font-label-sm text-label-sm text-on-surface-variant hover:bg-surface-container rounded-lg transition-colors">Batal</button>
                <button type="submit" class="px-4 py-2 font-label-sm text-label-sm bg-primary text-on-primary hover:bg-primary-container rounded-lg transition-colors shadow-sm">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Inventaris -->
<div id="editModal" class="fixed inset-0 z-50 hidden bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-surface-container-lowest rounded-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto shadow-2xl border border-surface-variant">
        <div class="p-6 border-b border-surface-variant flex items-center justify-between sticky top-0 bg-surface-container-lowest z-10">
            <h3 class="font-h3 text-h3 text-on-surface">Edit Inventaris</h3>
            <button onclick="document.getElementById('editModal').classList.add('hidden')" class="text-on-surface-variant hover:text-error transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form id="editForm" method="POST" class="p-6 space-y-4">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant mb-1">Jenis Hewan</label>
                    <input type="text" name="jenis" id="edit_jenis" required class="w-full px-3 py-2 rounded-lg border border-outline-variant focus:border-primary bg-surface-bright text-on-surface">
                </div>
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant mb-1">Ras</label>
                    <input type="text" name="ras" id="edit_ras" class="w-full px-3 py-2 rounded-lg border border-outline-variant focus:border-primary bg-surface-bright text-on-surface">
                </div>
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant mb-1">Gender</label>
                    <select name="gender" id="edit_gender" required class="w-full px-3 py-2 rounded-lg border border-outline-variant focus:border-primary bg-surface-bright text-on-surface">
                        <option value="Jantan">Jantan</option>
                        <option value="Betina">Betina</option>
                    </select>
                </div>
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant mb-1">Umur (Bulan)</label>
                    <input type="number" name="umur" id="edit_umur" required min="0" class="w-full px-3 py-2 rounded-lg border border-outline-variant focus:border-primary bg-surface-bright text-on-surface">
                </div>
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant mb-1">Bobot (Kg)</label>
                    <input type="number" step="0.01" name="berat" id="edit_berat" required min="0" class="w-full px-3 py-2 rounded-lg border border-outline-variant focus:border-primary bg-surface-bright text-on-surface">
                </div>
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant mb-1">Status Stok</label>
                    <select name="status_stok" id="edit_status" required class="w-full px-3 py-2 rounded-lg border border-outline-variant focus:border-primary bg-surface-bright text-on-surface">
                        <option value="Tersedia">Tersedia</option>
                        <option value="Dijual">Dijual</option>
                        <option value="Terjual">Terjual</option>
                        <option value="Dalam Perawatan">Dalam Perawatan</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="block font-label-sm text-label-sm text-on-surface-variant mb-1">Rekam Medis General</label>
                <textarea name="rekam_medis_general" id="edit_rekam_medis" rows="3" class="w-full px-3 py-2 rounded-lg border border-outline-variant focus:border-primary bg-surface-bright text-on-surface"></textarea>
            </div>
            <div class="pt-4 flex justify-end gap-3 border-t border-surface-variant">
                <button type="button" onclick="document.getElementById('editModal').classList.add('hidden')" class="px-4 py-2 font-label-sm text-label-sm text-on-surface-variant hover:bg-surface-container rounded-lg transition-colors">Batal</button>
                <button type="submit" class="px-4 py-2 font-label-sm text-label-sm bg-primary text-on-primary hover:bg-primary-container rounded-lg transition-colors shadow-sm">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Jual Inventaris -->
<div id="jualModal" class="fixed inset-0 z-50 hidden bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-surface-container-lowest rounded-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto shadow-2xl border border-surface-variant">
        <div class="p-6 border-b border-surface-variant flex items-center justify-between sticky top-0 bg-surface-container-lowest z-10">
            <h3 class="font-h3 text-h3 text-on-surface">Jual Hewan (Katalog)</h3>
            <button onclick="document.getElementById('jualModal').classList.add('hidden')" class="text-on-surface-variant hover:text-error transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form id="jualForm" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="block font-label-sm text-label-sm text-on-surface-variant mb-1">Harga (Rp)</label>
                <input type="number" name="harga" required min="0" class="w-full px-3 py-2 rounded-lg border border-outline-variant focus:border-primary bg-surface-bright text-on-surface" placeholder="mis. 2500000">
            </div>
            <div>
                <label class="block font-label-sm text-label-sm text-on-surface-variant mb-1">Spesifikasi Ternak</label>
                <textarea name="spesifikasi" rows="3" class="w-full px-3 py-2 rounded-lg border border-outline-variant focus:border-primary bg-surface-bright text-on-surface" placeholder="Keterangan tambahan untuk pembeli..."></textarea>
            </div>
            <div>
                <label class="block font-label-sm text-label-sm text-on-surface-variant mb-1">Foto Ternak</label>
                <input type="file" name="foto" accept="image/*" class="w-full px-3 py-2 rounded-lg border border-outline-variant focus:border-primary bg-surface-bright text-on-surface">
                <p class="text-xs text-on-surface-variant mt-1">Format: JPG, PNG (Maks 2MB)</p>
            </div>
            <div class="pt-4 flex justify-end gap-3 border-t border-surface-variant">
                <button type="button" onclick="document.getElementById('jualModal').classList.add('hidden')" class="px-4 py-2 font-label-sm text-label-sm text-on-surface-variant hover:bg-surface-container rounded-lg transition-colors">Batal</button>
                <button type="submit" class="px-4 py-2 font-label-sm text-label-sm bg-primary text-on-primary hover:bg-primary-container rounded-lg transition-colors shadow-sm">Masukkan ke Katalog</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openEditModal(item) {
        document.getElementById('editForm').action = `/admin/inventaris/${item.id}`;
        document.getElementById('edit_jenis').value = item.jenis;
        document.getElementById('edit_ras').value = item.ras || '';
        document.getElementById('edit_gender').value = item.gender;
        document.getElementById('edit_umur').value = item.umur;
        document.getElementById('edit_berat').value = item.berat;
        document.getElementById('edit_status').value = item.status_stok;
        document.getElementById('edit_rekam_medis').value = item.rekam_medis_general || '';
        document.getElementById('editModal').classList.remove('hidden');
    }

    function openJualModal(item) {
        document.getElementById('jualForm').action = `/admin/inventaris/${item.id}/jual`;
        document.getElementById('jualModal').classList.remove('hidden');
    }
    </script>
    </div>
    </div>
</div>
@endsection
