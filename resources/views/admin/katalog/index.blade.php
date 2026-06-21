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

    {{-- ── Premium Bento Grid Metrics ── --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Total Produk -->
        <div class="bg-gradient-to-br from-[#FEFCE8] to-[#FFFcf2] rounded-[24px] p-6 relative overflow-hidden group hover:-translate-y-1.5 hover:shadow-[0_12px_40px_rgba(234,179,8,0.15)] transition-all duration-300 border border-yellow-200/60 flex flex-col justify-between">
            <div class="flex items-start justify-between mb-6 relative z-10">
                <div class="w-14 h-14 rounded-2xl bg-yellow-100 text-yellow-600 flex items-center justify-center group-hover:scale-110 group-hover:bg-yellow-500 group-hover:text-white transition-all duration-300 shadow-sm border border-yellow-200">
                    <span class="material-symbols-outlined text-[28px]">inventory_2</span>
                </div>
                <span class="text-[10px] font-black uppercase tracking-[0.2em] text-yellow-700 bg-yellow-100 px-3 py-1.5 rounded-full border border-yellow-200">Katalog</span>
            </div>
            <div class="relative z-10">
                <p class="text-xs font-bold text-yellow-600 uppercase tracking-wider mb-1">Total Produk</p>
                <div class="flex items-end gap-3">
                    <h3 class="text-5xl font-black text-slate-800 tracking-tight">{{ number_format($totalProducts) }}</h3>
                    <p class="text-[11px] font-medium text-yellow-600/80 mb-2 leading-tight">Seluruh<br>inventaris</p>
                </div>
            </div>
            <!-- Decorative shapes -->
            <div class="absolute -top-12 -right-12 w-32 h-32 bg-yellow-300/20 rounded-full blur-2xl group-hover:bg-yellow-400/30 transition-colors"></div>
            <div class="absolute -bottom-12 -left-12 w-40 h-40 bg-amber-200/20 rounded-full blur-3xl group-hover:bg-amber-300/30 transition-colors"></div>
        </div>

        <!-- Tersedia di Katalog -->
        <div class="bg-gradient-to-br from-[#2A7844] to-[#164e28] rounded-[24px] p-6 relative overflow-hidden group hover:-translate-y-1.5 hover:shadow-[0_16px_40px_rgba(42,120,68,0.3)] transition-all duration-300 border border-[#2A7844]/50 flex flex-col justify-between">
            <div class="flex items-start justify-between mb-6 relative z-10">
                <div class="w-14 h-14 rounded-2xl bg-white/10 text-white flex items-center justify-center backdrop-blur-md group-hover:scale-110 group-hover:bg-white/20 transition-all duration-300 shadow-inner">
                    <span class="material-symbols-outlined text-[28px]" style="font-variation-settings: 'FILL' 1;">storefront</span>
                </div>
                <span class="text-[10px] font-black uppercase tracking-[0.2em] text-emerald-100 bg-white/10 backdrop-blur-md px-3 py-1.5 rounded-full border border-white/10">Live</span>
            </div>
            <div class="relative z-10">
                <p class="text-xs font-bold text-emerald-100/90 uppercase tracking-wider mb-1">Tersedia di Katalog</p>
                <div class="flex items-end gap-3">
                    <h3 class="text-5xl font-black text-white tracking-tight">{{ number_format($activeListings) }}</h3>
                    <p class="text-[11px] font-medium text-emerald-200 mb-2 leading-tight">Terlihat<br>pelanggan</p>
                </div>
            </div>
            <!-- Decorative shapes -->
            <div class="absolute -top-12 -right-12 w-32 h-32 bg-white/10 rounded-full blur-2xl group-hover:bg-white/20 transition-colors"></div>
            <div class="absolute -bottom-12 -left-12 w-40 h-40 bg-emerald-400/20 rounded-full blur-3xl group-hover:bg-emerald-400/30 transition-colors"></div>
        </div>

        <!-- Terbooking Customer -->
        <div class="bg-gradient-to-br from-[#FFF8F1] to-[#FFF1E5] rounded-[24px] p-6 relative overflow-hidden group hover:-translate-y-1.5 hover:shadow-[0_12px_40px_rgba(249,115,22,0.15)] transition-all duration-300 border border-orange-100 flex flex-col justify-between">
            <div class="flex items-start justify-between mb-6 relative z-10">
                <div class="w-14 h-14 rounded-2xl bg-orange-100 text-orange-500 flex items-center justify-center group-hover:scale-110 group-hover:bg-orange-500 group-hover:text-white transition-all duration-300 shadow-sm border border-orange-200">
                    <span class="material-symbols-outlined text-[28px]">bookmark_added</span>
                </div>
                <span class="text-[10px] font-black uppercase tracking-[0.2em] text-orange-600 bg-orange-100 px-3 py-1.5 rounded-full border border-orange-200">Pending</span>
            </div>
            <div class="relative z-10">
                <p class="text-xs font-bold text-orange-500 uppercase tracking-wider mb-1">Terbooking Customer</p>
                <div class="flex items-end gap-3">
                    <h3 class="text-5xl font-black text-slate-800 tracking-tight">{{ number_format($lowStockAlerts) }}</h3>
                    <p class="text-[11px] font-medium text-orange-400 mb-2 leading-tight">Menunggu<br>konfirmasi</p>
                </div>
            </div>
            <div class="absolute -bottom-10 -right-10 w-40 h-40 bg-orange-200/40 rounded-full blur-3xl group-hover:bg-orange-300/40 transition-colors"></div>
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
                    @if(config('app.env') === 'production')
                        <img src="{{ Storage::disk('supabase')->url($produk->foto) }}" alt="{{ $produk->nama_produk }}"
                             class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                    @else
                        <img src="{{ asset('storage/' . $produk->foto) }}" alt="{{ $produk->nama_produk }}"
                             class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                    @endif
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
                    <div class="flex items-center gap-1 px-3 py-1.5 rounded-xl bg-black/50 backdrop-blur-md border border-white/20 shadow-lg transition-transform hover:scale-105 cursor-default">
                        <span class="material-symbols-outlined text-[13px] text-emerald-400">tag</span>
                        <span class="text-[11px] font-black tracking-widest text-white">{{ str_pad($produk->inventaris->id, 4, '0', STR_PAD_LEFT) }}</span>
                    </div>
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
    <div x-data="{ open: false }" @open-edit-produk-modal.window="open = true" class="relative z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" style="display: none;"></div>

        <div class="fixed inset-0 z-10 w-screen overflow-y-auto" x-show="open" style="display: none;">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div x-show="open" @click.away="open = false" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="relative transform overflow-hidden rounded-[24px] bg-white text-left shadow-[0_20px_60px_rgba(5,31,32,0.15)] transition-all sm:my-8 sm:w-full sm:max-w-lg border border-slate-100">
                    <div class="px-8 py-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center">
                                <span class="material-symbols-outlined text-xl">edit_document</span>
                            </div>
                            <h3 class="text-xl font-bold text-slate-800">Edit Produk</h3>
                        </div>
                        <button type="button" @click="open = false" class="w-8 h-8 flex items-center justify-center rounded-full text-slate-400 hover:bg-slate-100 hover:text-slate-600 transition-colors">
                            <span class="material-symbols-outlined text-xl">close</span>
                        </button>
                    </div>
                    <form id="editProdukForm" method="POST" enctype="multipart/form-data" data-ajax="true" class="p-8 space-y-6">
                        @csrf
                        @method('PUT')
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Harga (Rp)</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 font-bold">Rp</span>
                                <input type="number" name="harga" id="edit_produk_harga" required min="0" class="w-full pl-12 pr-4 py-3 rounded-xl border border-slate-200 focus:border-[#2A7844] focus:ring-2 focus:ring-[#2A7844]/20 bg-slate-50 focus:bg-white text-slate-800 font-black text-lg transition-all outline-none">
                            </div>
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Spesifikasi Ternak</label>
                            <textarea name="spesifikasi" id="edit_produk_spesifikasi" rows="3" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-[#2A7844] focus:ring-2 focus:ring-[#2A7844]/20 bg-slate-50 focus:bg-white text-slate-800 font-medium transition-all outline-none"></textarea>
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Foto Ternak (Biarkan kosong jika tidak ingin mengubah)</label>
                            <input type="file" name="foto" accept="image/*" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-[#2A7844] focus:ring-2 focus:ring-[#2A7844]/20 bg-slate-50 focus:bg-white text-slate-800 font-medium transition-all outline-none">
                            <p class="text-xs text-slate-400 mt-1">Format: JPG, PNG, JPEG, IMG (Maks 10MB)</p>
                        </div>
                        <div class="pt-6 mt-2 flex justify-end gap-3 border-t border-slate-100">
                            <button type="button" @click="open = false" class="px-6 py-2.5 font-bold text-sm text-slate-500 hover:bg-slate-100 rounded-xl transition-colors btn-batal">Batal</button>
                            <button type="submit" class="px-6 py-2.5 font-bold text-sm bg-gradient-to-r from-[#2A7844] to-[#1e5c33] text-white hover:shadow-lg hover:shadow-[#2A7844]/30 hover:-translate-y-0.5 rounded-xl transition-all">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
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
            window.dispatchEvent(new CustomEvent('open-edit-produk-modal'));
        }
    </script>
    </div>
</div>
@endsection
