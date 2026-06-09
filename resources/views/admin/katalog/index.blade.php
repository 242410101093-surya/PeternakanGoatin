@extends('layouts.admin')

@section('title', 'Katalog Produk')

@section('content')
<div class="w-full px-margin-mobile md:px-margin-desktop">
    <div class="max-w-container-max mx-auto space-y-stack-xl">

    {{-- ── Header Section ── --}}
    <div class="flex flex-col md:flex-row md:items-end justify-between mb-stack-lg gap-stack-md">
        <div>
            <div class="flex items-center gap-2 mb-1.5">
                <span class="w-2.5 h-2.5 rounded-full bg-primary block"></span>
                <span class="font-caption text-caption text-on-surface-variant uppercase tracking-wider">Manajemen Produk</span>
            </div>
            <h1 class="font-h1 text-h1 text-on-surface">Katalog Produk</h1>
            <p class="font-body-md text-body-md text-on-surface-variant mt-stack-xs">Kelola inventaris, pantau harga, dan atur visibilitas marketplace.</p>
        </div>
        <div class="flex items-center gap-stack-sm">
            <a href="{{ route('admin.inventaris.index') }}" class="px-6 py-2.5 rounded-lg bg-primary text-on-primary hover:bg-primary-container shadow-[0_4px_12px_rgba(74,124,89,0.2)] transition-all font-label-sm text-label-sm flex items-center gap-2">
                <span class="material-symbols-outlined text-[20px]">add</span> Tambah Produk
            </a>
        </div>
    </div>

    {{-- ── Bento Grid Metrics ── --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-gutter">
        <div class="bg-surface-container-lowest rounded-xl p-gutter border border-surface-container shadow-[0_2px_8px_rgba(74,124,89,0.03)] flex items-start justify-between">
            <div>
                <p class="font-caption text-caption text-on-surface-variant uppercase tracking-wider">Total Produk</p>
                <p class="font-h2 text-h2 text-on-surface mt-stack-xs">{{ number_format($totalProducts) }}</p>
            </div>
            <div class="w-12 h-12 rounded-full bg-surface-container flex items-center justify-center text-primary">
                <span class="material-symbols-outlined">inventory_2</span>
            </div>
        </div>
        <div class="bg-primary-container rounded-xl p-gutter border border-primary shadow-[0_4px_16px_rgba(74,124,89,0.15)] flex items-start justify-between">
            <div>
                <p class="font-caption text-caption text-on-primary-container/80 uppercase tracking-wider">Tersedia di Katalog</p>
                <p class="font-h2 text-h2 text-on-primary-container mt-stack-xs">{{ number_format($activeListings) }}</p>
                <p class="font-caption text-caption text-on-primary-container/60 mt-1">Terlihat oleh pelanggan</p>
            </div>
            <div class="w-12 h-12 rounded-full bg-white/20 flex items-center justify-center text-on-primary-container">
                <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">storefront</span>
            </div>
        </div>
        <div class="bg-surface-container-lowest rounded-xl p-gutter border border-tertiary-container shadow-[0_2px_8px_rgba(74,124,89,0.03)] flex items-start justify-between">
            <div>
                <p class="font-caption text-caption text-tertiary uppercase tracking-wider">Terbooking Customer</p>
                <p class="font-h2 text-h2 text-on-surface mt-stack-xs">{{ number_format($lowStockAlerts) }}</p>
                <p class="font-caption text-caption text-on-surface-variant mt-1">Menunggu konfirmasi</p>
            </div>
            <div class="w-12 h-12 rounded-full bg-tertiary-container flex items-center justify-center text-tertiary">
                <span class="material-symbols-outlined">bookmark_added</span>
            </div>
        </div>
    </div>

    {{-- ── Search and Filter Bar ── --}}
    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6">
        <form method="GET" action="{{ route('admin.katalog.index') }}" class="space-y-4">
            <div class="flex flex-col sm:flex-row gap-4">
                <button type="button" onclick="document.getElementById('filterPanel').classList.toggle('hidden')" class="px-4 py-2 border border-outline-variant rounded-lg text-on-surface-variant hover:bg-surface-container transition-colors flex items-center justify-center gap-2 whitespace-nowrap">
                    <span class="material-symbols-outlined">filter_list</span>
                    Filter
                </button>
                <div class="relative flex-1">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-[20px]">search</span>
                    <input class="pl-10 pr-4 py-2 border border-outline-variant rounded-lg bg-surface-bright text-on-surface font-body-md text-sm focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all w-full"
                           placeholder="Cari nama produk atau ras..."
                           type="text" name="search" value="{{ request('search') }}"/>
                </div>
                <button type="submit" class="px-4 py-2 border border-primary rounded-lg text-primary hover:bg-primary-container transition-colors flex items-center justify-center gap-2 whitespace-nowrap">
                    <span class="material-symbols-outlined">search</span>
                </button>
            </div>
            <div id="filterPanel" class="hidden grid grid-cols-1 md:grid-cols-3 gap-4 pt-4 border-t border-surface-variant">
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant mb-2">Jenis Ternak</label>
                    <select name="jenis" class="w-full px-3 py-2 rounded-lg border border-outline-variant focus:border-primary bg-surface-bright text-on-surface font-body-sm">
                        <option value="">Semua Jenis</option>
                        @foreach($jenisOptions as $jenis)
                            <option value="{{ $jenis }}" {{ request('jenis') == $jenis ? 'selected' : '' }}>{{ $jenis }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant mb-2">Jenis Kelamin</label>
                    <select name="gender" class="w-full px-3 py-2 rounded-lg border border-outline-variant focus:border-primary bg-surface-bright text-on-surface font-body-sm">
                        <option value="">Semua Kelamin</option>
                        <option value="Jantan" {{ request('gender') == 'Jantan' ? 'selected' : '' }}>Jantan</option>
                        <option value="Betina" {{ request('gender') == 'Betina' ? 'selected' : '' }}>Betina</option>
                    </select>
                </div>
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant mb-2">Rentang Harga (Rp)</label>
                    <div class="flex gap-2 items-center">
                        <input type="number" name="min_harga" placeholder="Min" class="flex-1 min-w-0 px-3 py-2 rounded-lg border border-outline-variant focus:border-primary bg-surface-bright text-on-surface font-body-sm" value="{{ request('min_harga') }}">
                        <span class="text-on-surface-variant">-</span>
                        <input type="number" name="max_harga" placeholder="Maks" class="flex-1 min-w-0 px-3 py-2 rounded-lg border border-outline-variant focus:border-primary bg-surface-bright text-on-surface font-body-sm" value="{{ request('max_harga') }}">
                    </div>
                </div>
                <div class="md:col-span-3 flex gap-3 justify-end pt-4 border-t border-surface-variant">
                    <a href="{{ route('admin.katalog.index') }}" class="px-4 py-2 font-label-sm text-label-sm text-on-surface-variant hover:bg-surface-container rounded-lg transition-colors border border-outline-variant">Atur Ulang Filter</a>
                    <button type="submit" class="px-4 py-2 font-label-sm text-label-sm bg-primary text-on-primary hover:bg-primary-container rounded-lg transition-colors shadow-sm">Terapkan Filter</button>
                </div>
            </div>
        </form>
    </div>

    {{-- ── Main Catalog Grid — sama persis dengan tampilan customer ── --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse ($produks as $produk)
        <div class="group flex flex-col rounded-2xl overflow-hidden border border-surface-container hover:shadow-[0_8px_32px_rgba(74,124,89,0.14)] hover:border-primary/20 transition-all duration-300 bg-surface-container-lowest">

            {{-- ── Header Image — identik dengan customer ── --}}
            <div class="h-52 relative overflow-hidden shrink-0 flex items-center justify-center"
                 style="background: linear-gradient(135deg, #051F20 0%, #0B2B26 100%);">

                @if($produk->foto)
                    <img src="{{ asset('storage/' . $produk->foto) }}" alt="{{ $produk->nama_produk }}"
                         class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                @else
                    <div class="absolute inset-0 flex flex-col items-center justify-center p-4">
                        <div class="absolute -right-8 -top-8 w-24 h-24 rounded-full filter blur-xl opacity-20" style="background:#2A7844;"></div>
                        <div class="w-16 h-16 rounded-full flex items-center justify-center mb-2" style="background:rgba(255,255,255,0.06); border:1px solid rgba(255,255,255,0.12);">
                            <span class="material-symbols-outlined text-white text-3xl font-light">pets</span>
                        </div>
                        <span class="text-[10px] font-bold tracking-widest text-emerald-400 uppercase">Goatin Prime</span>
                    </div>
                @endif

                {{-- ID Kambing badge kiri atas (khusus admin) --}}
                @if($produk->inventaris)
                <div class="absolute top-3 left-3 z-10">
                    <span class="px-2.5 py-1 rounded-lg text-[10px] font-extrabold shadow-sm flex items-center gap-1"
                          style="background:rgba(74,124,89,0.92); color:#fff; border:1px solid rgba(255,255,255,0.15);">
                        <span class="material-symbols-outlined text-white" style="font-size:11px;">tag</span>
                        #{{ str_pad($produk->inventaris->id, 4, '0', STR_PAD_LEFT) }}
                    </span>
                </div>

                {{-- Kelamin badge kanan atas (seperti customer di kiri, di admin pindah ke kanan) --}}
                <div class="absolute top-3 right-3 z-10">
                    <span class="px-2.5 py-1 rounded-lg text-[10px] font-extrabold shadow-sm flex items-center gap-1"
                          style="background:rgba(255,255,255,0.95); color:#2A7844; border:1px solid rgba(42,120,68,0.15);">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 block"></span>
                        {{ $produk->inventaris->gender }}
                    </span>
                </div>
                @endif
            </div>

            {{-- ── Body Info — mengikuti struktur customer ── --}}
            <div class="p-5 flex flex-col flex-grow space-y-3">

                {{-- Nama produk + tombol admin (edit/hapus) --}}
                <div class="flex items-start justify-between gap-2">
                    <h3 class="font-bold text-base leading-snug group-hover:text-emerald-700 transition-colors line-clamp-2 flex-1" style="color:#051F20;">
                        {{ $produk->nama_produk }}
                    </h3>
                    <div class="flex gap-1 shrink-0">
                        <button onclick="openEditProdukModal({{ $produk }})" class="p-1 text-on-surface-variant hover:text-primary transition-colors" title="Edit">
                            <span class="material-symbols-outlined text-sm">edit</span>
                        </button>
                        <form action="{{ route('admin.katalog.destroy', $produk->id) }}" method="POST" class="inline delete-form" data-message="Yakin ingin menghapus '{{ $produk->nama_produk }}' dari katalog? Tindakan ini tidak bisa dibatalkan.">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-1 text-on-surface-variant hover:text-error transition-colors" title="Hapus">
                                <span class="material-symbols-outlined text-sm">delete</span>
                            </button>
                        </form>
                    </div>
                </div>

                {{-- ID Kambing info (khusus admin) --}}
                @if($produk->inventaris)
                <div class="flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-primary text-[14px]">tag</span>
                    <span class="text-xs font-medium" style="color:#64748B;">ID Kambing:</span>
                    <span class="font-mono font-bold text-xs" style="color:#2A7844;">#{{ str_pad($produk->inventaris->id, 4, '0', STR_PAD_LEFT) }}</span>
                </div>
                @endif

                {{-- Info Ternak & Harga --}}
                <div class="mt-auto pt-4 flex flex-col">
                    @if($produk->inventaris)
                    <div class="text-[12px] font-medium mb-3" style="color:#64748B;">
                        Jenis: {{ $produk->inventaris->jenis }} | Kelamin: {{ $produk->inventaris->gender }} | Berat: {{ $produk->inventaris->berat }} kg | Umur: {{ $produk->inventaris->umur }} bulan
                    </div>
                    @else
                    <div class="text-[12px] font-medium mb-3 text-slate-400">
                        Produk Umum
                    </div>
                    @endif

                    <div class="pt-4 flex items-center justify-between border-t border-slate-100">
                        <div class="flex flex-col">
                            <span class="text-[10px] font-bold uppercase tracking-wider" style="color:#94A3B8;">Harga Ternak</span>
                            <span class="text-base font-extrabold" style="color:#2A7844;">
                                Rp {{ number_format($produk->harga, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full py-16 px-8 text-center bg-white/30 rounded-2xl border border-surface-variant">
            <div class="w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4 bg-primary-container/30 border border-primary/20">
                <span class="material-symbols-outlined text-3xl text-primary">inventory_2</span>
            </div>
            <h3 class="font-bold text-base mb-1" style="color:#051F20;">Belum Ada Produk</h3>
            <p class="text-xs" style="color:#64748B;">Belum ada produk di katalog. Tambahkan dari fitur Inventaris.</p>
        </div>
        @endforelse
    </div>

    {{-- ── Pagination ── --}}
    <div class="flex justify-center pt-stack-md pb-stack-xl">
        {{ $produks->links() }}
    </div>

    {{-- ── Modal Edit Produk ── --}}
    <div id="editProdukModal" class="fixed inset-0 z-50 hidden bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-surface-container-lowest rounded-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto shadow-2xl border border-surface-variant">
            <div class="p-6 border-b border-surface-variant flex items-center justify-between sticky top-0 bg-surface-container-lowest z-10">
                <h3 class="font-h3 text-h3 text-on-surface">Edit Produk</h3>
                <button onclick="document.getElementById('editProdukModal').classList.add('hidden')" class="text-on-surface-variant hover:text-error transition-colors">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <form id="editProdukForm" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant mb-1">Harga (Rp)</label>
                    <input type="number" name="harga" id="edit_produk_harga" required min="0" class="w-full px-3 py-2 rounded-lg border border-outline-variant focus:border-primary bg-surface-bright text-on-surface">
                </div>
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant mb-1">Spesifikasi Ternak</label>
                    <textarea name="spesifikasi" id="edit_produk_spesifikasi" rows="3" class="w-full px-3 py-2 rounded-lg border border-outline-variant focus:border-primary bg-surface-bright text-on-surface"></textarea>
                </div>
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant mb-1">Foto Ternak (Biarkan kosong jika tidak ingin mengubah)</label>
                    <input type="file" name="foto" accept="image/*" class="w-full px-3 py-2 rounded-lg border border-outline-variant focus:border-primary bg-surface-bright text-on-surface">
                    <p class="text-xs text-on-surface-variant mt-1">Format: JPG, PNG, JPEG, IMG (Maks 10MB)</p>
                </div>
                <div class="pt-4 flex justify-end gap-3 border-t border-surface-variant">
                    <button type="button" onclick="document.getElementById('editProdukModal').classList.add('hidden')" class="px-4 py-2 font-label-sm text-label-sm text-on-surface-variant hover:bg-surface-container rounded-lg transition-colors">Batal</button>
                    <button type="submit" class="px-4 py-2 font-label-sm text-label-sm bg-primary text-on-primary hover:bg-primary-container rounded-lg transition-colors shadow-sm">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openEditProdukModal(produk) {
            document.getElementById('editProdukForm').action = `/admin/katalog/${produk.id}`;
            document.getElementById('edit_produk_harga').value = Number(produk.harga);

            let spec = produk.spesifikasi;
            if (!spec && produk.inventaris) {
                const inv = produk.inventaris;
                spec = `Jenis: ${inv.jenis || ''} | Kelamin: ${inv.gender || ''} | Berat: ${inv.berat || ''} kg | Umur: ${inv.umur || ''} bulan`;
            }
            document.getElementById('edit_produk_spesifikasi').value = spec || '';
            document.getElementById('editProdukModal').classList.remove('hidden');
        }
    </script>
    </div>
</div>
@endsection
