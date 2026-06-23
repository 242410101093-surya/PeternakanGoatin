@extends('layouts.admin')

@section('title', 'Manajemen Inventaris')

@section('content')
<div class="w-full px-margin-mobile md:px-margin-desktop">
    <div class="max-w-container-max mx-auto space-y-stack-xl">
<!-- Page Header -->
<div class="flex flex-col md:flex-row md:items-end justify-between mb-stack-lg gap-stack-md">
    <div>
        <h1 class="font-h1 text-h1 text-on-surface tracking-tight mb-2">Manajemen Inventaris</h1>
        <p class="font-body-md text-body-md text-on-surface-variant max-w-2xl">Pantau jumlah ternak, lacak stok, dan kelola harga seluruh aset peternakan secara real-time.</p>
    </div>
    <button onclick="window.dispatchEvent(new CustomEvent('open-add-modal'))" class="inline-flex items-center justify-center gap-2 bg-primary hover:bg-surface-tint text-on-primary px-6 py-3 rounded-lg font-label-sm text-label-sm transition-all shadow-[0_4px_14px_rgba(74,124,89,0.15)] hover:shadow-[0_6px_20px_rgba(74,124,89,0.2)] whitespace-nowrap">
        <span class="material-symbols-outlined text-[18px]" style="font-variation-settings: 'FILL' 1;">add</span>
        Tambah Inventaris
    </button>
</div>

<!-- Summary Bento Grid -->
<div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-5 mb-8">
    <!-- Total Ternak -->
    <a href="{{ route('admin.inventaris.index') }}" class="block cursor-pointer bg-gradient-to-br from-[#2A7844] to-[#1e5c33] rounded-2xl p-6 relative overflow-hidden group hover:-translate-y-1 hover:shadow-2xl hover:shadow-[#2A7844]/40 transition-all duration-300 col-span-2 lg:col-span-1 border border-[#2A7844]/50">
        <div class="flex items-center justify-between mb-4 relative z-10">
            <div class="w-12 h-12 rounded-full bg-white/20 text-white flex items-center justify-center backdrop-blur-md">
                <span class="material-symbols-outlined text-2xl">pets</span>
            </div>
            <span class="text-xs font-bold uppercase tracking-wider text-emerald-100">Total</span>
        </div>
        <div class="relative z-10">
            <h3 class="text-4xl font-black text-white tracking-tight">{{ number_format($totalLivestock) }}</h3>
            <p class="text-sm font-medium text-emerald-100/80 mt-1">Seluruh ternak</p>
        </div>
        <!-- Decorative shapes -->
        <div class="absolute -top-10 -right-10 w-32 h-32 bg-white/10 rounded-full blur-2xl group-hover:bg-white/20 transition-colors"></div>
        <div class="absolute -bottom-10 -left-10 w-32 h-32 bg-emerald-400/20 rounded-full blur-2xl group-hover:bg-emerald-400/40 transition-colors"></div>
    </a>

    <!-- Tersedia -->
    <a href="{{ route('admin.inventaris.index', ['status_stok' => 'Tersedia']) }}" class="block cursor-pointer bg-white rounded-2xl p-6 relative overflow-hidden group hover:-translate-y-1 hover:shadow-[0_8px_30px_rgba(59,130,246,0.12)] transition-all duration-300 border border-slate-100 shadow-sm">
        <div class="flex items-center justify-between mb-4 relative z-10">
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center group-hover:scale-110 group-hover:bg-blue-600 group-hover:text-white transition-all duration-300">
                <span class="material-symbols-outlined text-2xl">check_circle</span>
            </div>
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Tersedia</span>
        </div>
        <div class="relative z-10">
            <h3 class="text-4xl font-black text-slate-800 tracking-tight">{{ number_format($countTersedia) }}</h3>
            <p class="text-sm font-medium text-slate-500 mt-1">Siap dijual</p>
        </div>
        <div class="absolute -bottom-8 -right-8 w-28 h-28 bg-blue-50/80 rounded-full blur-2xl group-hover:bg-blue-100 transition-colors"></div>
    </a>

    <!-- Terbooking -->
    <a href="{{ route('admin.inventaris.index', ['status_stok' => 'Terbooking']) }}" class="block cursor-pointer bg-white rounded-2xl p-6 relative overflow-hidden group hover:-translate-y-1 hover:shadow-[0_8px_30px_rgba(249,115,22,0.12)] transition-all duration-300 border border-slate-100 shadow-sm">
        <div class="flex items-center justify-between mb-4 relative z-10">
            <div class="w-12 h-12 rounded-2xl bg-orange-50 text-orange-500 flex items-center justify-center group-hover:scale-110 group-hover:bg-orange-500 group-hover:text-white transition-all duration-300">
                <span class="material-symbols-outlined text-2xl">book_online</span>
            </div>
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Terbooking</span>
        </div>
        <div class="relative z-10">
            <h3 class="text-4xl font-black text-slate-800 tracking-tight">{{ number_format($countTerbooking) }}</h3>
            <p class="text-sm font-medium text-slate-500 mt-1">Menunggu bayar</p>
        </div>
        <div class="absolute -bottom-8 -right-8 w-28 h-28 bg-orange-50/80 rounded-full blur-2xl group-hover:bg-orange-100 transition-colors"></div>
    </a>

    <!-- Terjual -->
    <a href="{{ route('admin.inventaris.index', ['status_stok' => 'Terjual']) }}" class="block cursor-pointer bg-white rounded-2xl p-6 relative overflow-hidden group hover:-translate-y-1 hover:shadow-[0_8px_30px_rgba(16,185,129,0.12)] transition-all duration-300 border border-slate-100 shadow-sm">
        <div class="flex items-center justify-between mb-4 relative z-10">
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-500 flex items-center justify-center group-hover:scale-110 group-hover:bg-emerald-500 group-hover:text-white transition-all duration-300">
                <span class="material-symbols-outlined text-2xl">shopping_bag</span>
            </div>
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Terjual</span>
        </div>
        <div class="relative z-10">
            <h3 class="text-4xl font-black text-slate-800 tracking-tight">{{ number_format($countTerjual) }}</h3>
            <p class="text-sm font-medium text-slate-500 mt-1">Transaksi sukses</p>
        </div>
        <div class="absolute -bottom-8 -right-8 w-28 h-28 bg-emerald-50/80 rounded-full blur-2xl group-hover:bg-emerald-100 transition-colors"></div>
    </a>

    <!-- Dalam Perawatan -->
    <a href="{{ route('admin.inventaris.index', ['status_stok' => 'Dalam Perawatan']) }}" class="block cursor-pointer bg-white rounded-2xl p-6 relative overflow-hidden group hover:-translate-y-1 hover:shadow-[0_8px_30px_rgba(239,68,68,0.12)] transition-all duration-300 border border-slate-100 shadow-sm">
        <div class="flex items-center justify-between mb-4 relative z-10">
            <div class="w-12 h-12 rounded-2xl bg-red-50 text-red-500 flex items-center justify-center group-hover:scale-110 group-hover:bg-red-500 group-hover:text-white transition-all duration-300">
                <span class="material-symbols-outlined text-2xl">healing</span>
            </div>
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Perawatan</span>
        </div>
        <div class="relative z-10">
            <h3 class="text-4xl font-black text-slate-800 tracking-tight">{{ number_format($countPerawatan) }}</h3>
            <p class="text-sm font-medium text-slate-500 mt-1">Sedang diobati</p>
        </div>
        <div class="absolute -bottom-8 -right-8 w-28 h-28 bg-red-50/80 rounded-full blur-2xl group-hover:bg-red-100 transition-colors"></div>
    </a>
</div>

<!-- Main Inventory List -->
<div class="bg-transparent flex flex-col mt-4">
    <!-- Table Header Actions -->
    <div class="px-2 mb-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <h2 class="font-h3 text-h3 text-slate-800 tracking-tight">Asset Roster</h2>
        <div class="flex items-center gap-3 flex-1 sm:flex-none">
            <form id="searchForm" method="GET" action="{{ route('admin.inventaris.index') }}" class="flex items-center gap-3 w-full sm:w-auto">
                <button type="button" onclick="document.getElementById('filterPanel').classList.toggle('hidden')" class="w-10 h-10 border border-slate-200 bg-white rounded-xl text-slate-500 hover:bg-slate-50 hover:text-slate-800 transition-colors flex items-center justify-center whitespace-nowrap shadow-sm">
                    <span class="material-symbols-outlined text-[20px]">filter_list</span>
                </button>
                <div class="relative flex-1 sm:flex-none">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-[18px]">search</span>
                    <input class="pl-10 pr-4 py-2.5 border border-slate-200 rounded-xl bg-white text-slate-800 font-body-md text-sm focus:outline-none focus:border-[#2A7844] focus:ring-2 focus:ring-[#2A7844]/20 transition-all w-full sm:w-64 shadow-sm" 
                           placeholder="Cari jenis atau ras..." 
                           type="text"
                           name="search"
                           value="{{ request('search') }}"/>
                </div>
                <button type="submit" class="w-10 h-10 bg-[#2A7844] text-white rounded-xl hover:bg-[#1e5c33] transition-colors flex items-center justify-center whitespace-nowrap shadow-sm hover:shadow-md">
                    <span class="material-symbols-outlined text-[20px]">search</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Filter Panel -->
    <div id="filterPanel" class="hidden mb-6 p-6 rounded-2xl border border-slate-200 bg-white shadow-sm">
        <form method="GET" action="{{ route('admin.inventaris.index') }}" id="filterForm" class="space-y-4">
            @if(request('search'))
                <input type="hidden" name="search" value="{{ request('search') }}">
            @endif
            
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <!-- Gender Filter -->
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant mb-2">Jenis Kelamin</label>
                    <select name="gender" class="w-full px-3 py-2 rounded-lg border border-outline-variant focus:border-primary bg-surface-bright text-on-surface font-body-sm">
                        <option value="">Semua Kelamin</option>
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

                <!-- Status Filter -->
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant mb-2">Status</label>
                    <select name="status_stok" class="w-full px-3 py-2 rounded-lg border border-outline-variant focus:border-primary bg-surface-bright text-on-surface font-body-sm">
                        <option value="">Semua Status</option>
                        <option value="Tersedia" {{ request('status_stok') == 'Tersedia' ? 'selected' : '' }}>Tersedia</option>
                        <option value="Terbooking" {{ request('status_stok') == 'Terbooking' ? 'selected' : '' }}>Terbooking</option>
                        <option value="Dalam Perawatan" {{ request('status_stok') == 'Dalam Perawatan' ? 'selected' : '' }}>Dalam Perawatan</option>
                        <option value="Terjual" {{ request('status_stok') == 'Terjual' ? 'selected' : '' }}>Terjual</option>
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
                <a href="{{ route('admin.inventaris.index') }}" class="px-4 py-2 font-label-sm text-label-sm text-on-surface-variant hover:bg-surface-container rounded-lg transition-colors border border-outline-variant">Atur Ulang Filter</a>
                <button type="submit" class="px-4 py-2 font-label-sm text-label-sm bg-primary text-on-primary hover:bg-primary-container rounded-lg transition-colors shadow-sm">Terapkan Filter</button>
            </div>
        </form>
    </div>
    
    <!-- Table Container -->
    <div class="relative min-h-[400px]">
        {{-- Modern Localized Loader --}}
        <div id="inventarisGridLoader" class="absolute inset-0 z-20 hidden flex-col items-center justify-center bg-white/30 backdrop-blur-[2px] rounded-2xl transition-all duration-300">
            @include('partials.modern_loader')
        </div>
        
        <div id="inventarisTableContainer" class="transition-opacity duration-300">
            @include('admin.inventaris.partials.table')
        </div>
    </div>
</div>

<!-- Modal Tambah Inventaris -->
<div x-data="{ open: false }" @open-add-modal.window="open = true" class="relative z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" style="display: none;"></div>

    <div class="fixed inset-0 z-10 w-screen overflow-y-auto" x-show="open" style="display: none;">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div x-show="open" @click.away="open = false" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="relative transform overflow-hidden rounded-[24px] bg-white text-left shadow-[0_20px_60px_rgba(5,31,32,0.15)] transition-all sm:my-8 sm:w-full sm:max-w-2xl border border-slate-100">
                <div class="px-8 py-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center">
                            <span class="material-symbols-outlined text-xl">add_circle</span>
                        </div>
                        <h3 class="text-xl font-bold text-slate-800">Tambah Inventaris</h3>
                    </div>
                    <button type="button" @click="open = false" class="w-8 h-8 flex items-center justify-center rounded-full text-slate-400 hover:bg-slate-100 hover:text-slate-600 transition-colors">
                        <span class="material-symbols-outlined text-xl">close</span>
                    </button>
                </div>
                <form action="{{ route('admin.inventaris.store') }}" method="POST" data-ajax="true" class="p-8 space-y-6">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Jenis Hewan</label>
                            <input type="text" name="jenis" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-[#2A7844] focus:ring-2 focus:ring-[#2A7844]/20 bg-slate-50 focus:bg-white text-slate-800 font-medium transition-all outline-none" placeholder="mis. Kambing">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Ras</label>
                            <input type="text" name="ras" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-[#2A7844] focus:ring-2 focus:ring-[#2A7844]/20 bg-slate-50 focus:bg-white text-slate-800 font-medium transition-all outline-none" placeholder="mis. Etawa">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Jenis Kelamin</label>
                            <div class="relative">
                                <select name="gender" required data-error-msg="Harap pilih jenis kelamin" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-[#2A7844] focus:ring-2 focus:ring-[#2A7844]/20 bg-slate-50 focus:bg-white text-slate-800 font-medium transition-all outline-none cursor-pointer">
                                    <option value="" disabled selected>Pilih Jenis Kelamin</option>
                                    <option value="Jantan">Jantan</option>
                                    <option value="Betina">Betina</option>
                                </select>
                            </div>
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Umur (Bulan)</label>
                            <input type="number" name="umur" required min="0" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-[#2A7844] focus:ring-2 focus:ring-[#2A7844]/20 bg-slate-50 focus:bg-white text-slate-800 font-medium transition-all outline-none">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Bobot (Kg)</label>
                            <input type="number" step="0.01" name="berat" required min="0" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-[#2A7844] focus:ring-2 focus:ring-[#2A7844]/20 bg-slate-50 focus:bg-white text-slate-800 font-medium transition-all outline-none">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Status Stok</label>
                            <div class="relative">
                                <select name="status_stok" required data-error-msg="Harap pilih salah satu status stok" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-[#2A7844] focus:ring-2 focus:ring-[#2A7844]/20 bg-slate-50 focus:bg-white text-slate-800 font-medium transition-all outline-none cursor-pointer">
                                    <option value="" disabled selected>Pilih Status</option>
                                    <option value="Tersedia">Tersedia</option>
                                    <option value="Terbooking">Terbooking</option>
                                    <option value="Dalam Perawatan">Dalam Perawatan</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Rekam Medis General</label>
                        <textarea name="rekam_medis_general" rows="3" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-[#2A7844] focus:ring-2 focus:ring-[#2A7844]/20 bg-slate-50 focus:bg-white text-slate-800 font-medium transition-all outline-none" placeholder="Kondisi kesehatan umum..."></textarea>
                    </div>
                    <div class="pt-6 mt-2 flex justify-end gap-3 border-t border-slate-100">
                        <button type="button" @click="open = false" class="px-6 py-2.5 font-bold text-sm text-slate-500 hover:bg-slate-100 rounded-xl transition-colors btn-batal">Batal</button>
                        <button type="submit" class="px-6 py-2.5 font-bold text-sm bg-gradient-to-r from-[#2A7844] to-[#1e5c33] text-white hover:shadow-lg hover:shadow-[#2A7844]/30 hover:-translate-y-0.5 rounded-xl transition-all">Simpan Baru</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Inventaris -->
<div x-data="{ open: false }" @open-edit-modal.window="open = true" class="relative z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" style="display: none;"></div>

    <div class="fixed inset-0 z-10 w-screen overflow-y-auto" x-show="open" style="display: none;">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div x-show="open" @click.away="open = false" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="relative transform overflow-hidden rounded-[24px] bg-white text-left shadow-[0_20px_60px_rgba(5,31,32,0.15)] transition-all sm:my-8 sm:w-full sm:max-w-2xl border border-slate-100">
                <div class="px-8 py-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center">
                            <span class="material-symbols-outlined text-xl">edit_document</span>
                        </div>
                        <h3 class="text-xl font-bold text-slate-800">Edit Inventaris</h3>
                    </div>
                    <button type="button" @click="open = false" class="w-8 h-8 flex items-center justify-center rounded-full text-slate-400 hover:bg-slate-100 hover:text-slate-600 transition-colors">
                        <span class="material-symbols-outlined text-xl">close</span>
                    </button>
                </div>
                <form id="editForm" method="POST" data-ajax="true" class="p-8 space-y-6">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Jenis Hewan</label>
                            <input type="text" name="jenis" id="edit_jenis" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-[#2A7844] focus:ring-2 focus:ring-[#2A7844]/20 bg-slate-50 focus:bg-white text-slate-800 font-medium transition-all outline-none">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Ras</label>
                            <input type="text" name="ras" id="edit_ras" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-[#2A7844] focus:ring-2 focus:ring-[#2A7844]/20 bg-slate-50 focus:bg-white text-slate-800 font-medium transition-all outline-none">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Jenis Kelamin</label>
                            <div class="relative">
                                <select name="gender" id="edit_gender" required data-error-msg="Harap pilih jenis kelamin" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-[#2A7844] focus:ring-2 focus:ring-[#2A7844]/20 bg-slate-50 focus:bg-white text-slate-800 font-medium transition-all outline-none cursor-pointer">
                                    <option value="" disabled selected>Pilih Jenis Kelamin</option>
                                    <option value="Jantan">Jantan</option>
                                    <option value="Betina">Betina</option>
                                </select>
                            </div>
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Umur (Bulan)</label>
                            <input type="number" name="umur" id="edit_umur" required min="0" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-[#2A7844] focus:ring-2 focus:ring-[#2A7844]/20 bg-slate-50 focus:bg-white text-slate-800 font-medium transition-all outline-none">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Bobot (Kg)</label>
                            <input type="number" step="0.01" name="berat" id="edit_berat" required min="0" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-[#2A7844] focus:ring-2 focus:ring-[#2A7844]/20 bg-slate-50 focus:bg-white text-slate-800 font-medium transition-all outline-none">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Status Stok</label>
                            <div class="relative">
                                <select name="status_stok" id="edit_status" required data-error-msg="Harap pilih salah satu status stok" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-[#2A7844] focus:ring-2 focus:ring-[#2A7844]/20 bg-slate-50 focus:bg-white text-slate-800 font-medium transition-all outline-none cursor-pointer">
                                    <option value="" disabled selected>Pilih Status</option>
                                    <option value="Tersedia">Tersedia</option>
                                    <option value="Terbooking">Terbooking</option>
                                    <option value="Terjual">Terjual</option>
                                    <option value="Dalam Perawatan">Dalam Perawatan</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Rekam Medis General</label>
                        <textarea name="rekam_medis_general" id="edit_rekam_medis" rows="3" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-[#2A7844] focus:ring-2 focus:ring-[#2A7844]/20 bg-slate-50 focus:bg-white text-slate-800 font-medium transition-all outline-none"></textarea>
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

<!-- Modal Jual Inventaris -->
<div x-data="{ open: false }" @open-jual-modal.window="open = true" class="relative z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" style="display: none;"></div>

    <div class="fixed inset-0 z-10 w-screen overflow-y-auto" x-show="open" style="display: none;">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div x-show="open" @click.away="open = false" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="relative transform overflow-hidden rounded-[24px] bg-white text-left shadow-[0_20px_60px_rgba(5,31,32,0.15)] transition-all sm:my-8 sm:w-full sm:max-w-lg border border-slate-100">
                <div class="px-8 py-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-orange-100 text-orange-600 flex items-center justify-center">
                            <span class="material-symbols-outlined text-xl">storefront</span>
                        </div>
                        <h3 class="text-xl font-bold text-slate-800">Jual Hewan (Katalog)</h3>
                    </div>
                    <button type="button" @click="open = false" class="w-8 h-8 flex items-center justify-center rounded-full text-slate-400 hover:bg-slate-100 hover:text-slate-600 transition-colors">
                        <span class="material-symbols-outlined text-xl">close</span>
                    </button>
                </div>
                <form id="jualForm" method="POST" enctype="multipart/form-data" data-ajax="true" class="p-8 space-y-6">
                    @csrf
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Harga (Rp)</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 font-bold">Rp</span>
                            <input type="number" name="harga" required min="0" class="w-full pl-12 pr-4 py-3 rounded-xl border border-slate-200 focus:border-[#2A7844] focus:ring-2 focus:ring-[#2A7844]/20 bg-slate-50 focus:bg-white text-slate-800 font-black text-lg transition-all outline-none" placeholder="mis. 2500000">
                        </div>
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Spesifikasi Ternak</label>
                        <textarea name="spesifikasi" id="jual_spesifikasi" rows="3" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-[#2A7844] focus:ring-2 focus:ring-[#2A7844]/20 bg-slate-50 focus:bg-white text-slate-800 font-medium transition-all outline-none" placeholder="Keterangan tambahan untuk pembeli..."></textarea>
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Foto Ternak</label>
                        <input type="file" name="foto" accept="image/*" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-[#2A7844] focus:ring-2 focus:ring-[#2A7844]/20 bg-slate-50 focus:bg-white text-slate-800 font-medium transition-all outline-none">
                        <p class="text-xs text-slate-400 mt-1">Format: JPG, PNG, JPEG, IMG (Maks 10MB)</p>
                    </div>
                    <div class="pt-6 mt-2 flex justify-end gap-3 border-t border-slate-100">
                        <button type="button" @click="open = false" class="px-6 py-2.5 font-bold text-sm text-slate-500 hover:bg-slate-100 rounded-xl transition-colors btn-batal">Batal</button>
                        <button type="submit" class="px-6 py-2.5 font-bold text-sm bg-gradient-to-r from-orange-500 to-orange-400 text-white hover:shadow-lg hover:shadow-orange-500/30 hover:-translate-y-0.5 rounded-xl transition-all">Masukkan ke Katalog</button>
                    </div>
                </form>
            </div>
        </div>
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
        
        window.currentOriginalStatus = item.status_stok;
        const statusSelect = document.getElementById('edit_status');
        const terjualOption = Array.from(statusSelect.options).find(opt => opt.value === 'Terjual');
        
        if (terjualOption) {
            terjualOption.style.display = '';
            terjualOption.disabled = false;
        }
        
        statusSelect.value = item.status_stok;
        document.getElementById('edit_rekam_medis').value = item.rekam_medis_general || '';
        window.dispatchEvent(new CustomEvent('open-edit-modal'));
    }

    function openJualModal(item) {
        document.getElementById('jualForm').action = `/admin/inventaris/${item.id}/jual`;
        document.getElementById('jual_spesifikasi').value = `Jenis: ${item.jenis} | Kelamin: ${item.gender} | Berat: ${item.berat} kg | Umur: ${item.umur} bulan`;
        window.dispatchEvent(new CustomEvent('open-jual-modal'));
    }

    document.addEventListener('DOMContentLoaded', function() {
        const searchForm = document.getElementById('searchForm');
        const filterForm = document.getElementById('filterForm');
        const tableContainer = document.getElementById('inventarisTableContainer');

        const editStatusSelect = document.getElementById('edit_status');
        if (editStatusSelect) {
            editStatusSelect.addEventListener('change', function() {
                if (this.value === 'Terjual' && window.currentOriginalStatus !== 'Terjual') {
                    if (window.showToast) {
                        window.showToast('Status "Terjual" hanya dapat diatur otomatis oleh sistem pesanan.', 'error');
                    } else {
                        alert('Status "Terjual" hanya dapat diatur otomatis oleh sistem pesanan.');
                    }
                    this.value = window.currentOriginalStatus;
                }
            });
        }

        let searchTimeout = null;

        function fetchInventaris(url = null) {
            let fetchUrl = url;
            if (!fetchUrl) {
                // Combine parameters from both forms
                const searchData = new FormData(searchForm);
                const filterData = new FormData(filterForm);
                const params = new URLSearchParams();
                
                for (let [key, val] of searchData.entries()) {
                    if (val) params.append(key, val);
                }
                for (let [key, val] of filterData.entries()) {
                    if (val && key !== 'search') params.append(key, val);
                }
                fetchUrl = searchForm.action + '?' + params.toString();
            }

            const loader = document.getElementById('inventarisGridLoader');
            if (loader) {
                loader.classList.remove('hidden');
                loader.classList.add('flex');
            }
            tableContainer.style.opacity = '0.4';
            tableContainer.style.pointerEvents = 'none';

            fetch(fetchUrl, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'text/html'
                }
            })
            .then(res => res.text())
            .then(html => {
                if (loader) {
                    loader.classList.add('hidden');
                    loader.classList.remove('flex');
                }
                tableContainer.innerHTML = html;
                tableContainer.style.opacity = '1';
                tableContainer.style.pointerEvents = 'auto';
            })
            .catch(err => {
                console.error('AJAX Error:', err);
                if (loader) {
                    loader.classList.add('hidden');
                    loader.classList.remove('flex');
                }
                tableContainer.style.opacity = '1';
                tableContainer.style.pointerEvents = 'auto';
            });
            
            window.history.pushState({}, '', fetchUrl);
        }
        window.fetchInventaris = fetchInventaris;

        // Search Form Submission
        if (searchForm) {
            searchForm.addEventListener('submit', function(e) {
                e.preventDefault();
                fetchInventaris();
            });

            // Live Search
            const searchInput = searchForm.querySelector('input[name="search"]');
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(() => {
                        fetchInventaris();
                    }, 500); // 500ms debounce
                });
            }
        }

        // Filter Form Submission
        if (filterForm) {
            filterForm.addEventListener('submit', function(e) {
                e.preventDefault();
                fetchInventaris();
            });

            // Auto-submit filter on change
            const filterInputs = filterForm.querySelectorAll('select, input');
            filterInputs.forEach(input => {
                input.addEventListener('change', () => {
                    fetchInventaris();
                });
            });
        }

        // Pagination Ajax
        document.addEventListener('click', function(e) {
            const link = e.target.closest('#inventarisTableContainer a[href*="page="]');
            if (link) {
                e.preventDefault();
                fetchInventaris(link.href);
            }
        });
    });
</script>
    </div>
</div>
@endsection
