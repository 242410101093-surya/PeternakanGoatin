@extends('layouts.admin')

@section('title', 'Laporan Keuangan')

@section('content')
<div class="w-full px-margin-mobile md:px-margin-desktop">
    <div class="max-w-container-max mx-auto space-y-stack-xl">
    <!-- Page Header & Actions -->
    <div class="flex flex-col md:flex-row md:items-end justify-between mb-stack-lg gap-stack-md">
        <div>
            <h2 class="font-h2 text-h2 text-on-surface mb-stack-xs">Laporan Keuangan</h2>
            <p class="font-body-md text-body-md text-on-surface-variant">Tinjau pemasukan, pengeluaran, dan kesehatan keuangan secara keseluruhan.</p>
        </div>
        <div class="flex flex-wrap items-center gap-stack-sm whitespace-nowrap">
            <button onclick="document.getElementById('addKeuanganModal').classList.remove('hidden')" class="flex items-center bg-primary text-on-primary font-label-sm text-label-sm px-4 py-2 rounded-lg hover:bg-surface-tint transition-colors ambient-shadow">
                <span class="material-symbols-outlined mr-2 text-[18px]">add</span>
                Catat Transaksi Baru
            </button>
        </div>
    </div>

    <!-- Search & Filter Bar -->
    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6 mb-stack-lg">
        <form method="GET" action="{{ route('admin.keuangan.index') }}" id="filterForm" class="space-y-4">
            <div class="flex flex-col sm:flex-row gap-4">
                <button type="button" onclick="document.getElementById('filterPanel').classList.toggle('hidden')" class="px-4 py-2 border border-outline-variant rounded-lg text-on-surface-variant hover:bg-surface-container transition-colors flex items-center justify-center gap-2 whitespace-nowrap">
                    <span class="material-symbols-outlined">filter_list</span>
                    Filter
                    @if(isset($hasFilters) && $hasFilters)
                        <span class="w-2 h-2 rounded-full bg-primary"></span>
                    @endif
                </button>
                <div class="relative flex-1">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-[20px]">search</span>
                    <input class="pl-10 pr-4 py-2 border border-outline-variant rounded-lg bg-surface-bright text-on-surface font-body-md text-sm focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all w-full" 
                           placeholder="Cari berdasarkan keterangan..." 
                           type="text"
                           name="search"
                           value="{{ request('search') }}"/>
                </div>
                <button type="submit" class="px-4 py-2 border border-primary rounded-lg text-primary hover:bg-primary-container transition-colors flex items-center justify-center gap-2 whitespace-nowrap">
                    <span class="material-symbols-outlined">search</span>
                </button>
            </div>

            <!-- Filter Panel -->
            <div id="filterPanel" class="{{ (isset($hasFilters) && $hasFilters) ? '' : 'hidden' }} grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 pt-4 border-t border-surface-variant">
                <!-- Jenis Transaksi -->
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant mb-2">Jenis Transaksi</label>
                    <select name="jenis_transaksi" class="w-full px-3 py-2 rounded-lg border border-outline-variant focus:border-primary bg-surface-bright text-on-surface font-body-sm">
                        <option value="">Semua Transaksi</option>
                        <option value="Pemasukan" {{ request('jenis_transaksi') === 'Pemasukan' ? 'selected' : '' }}>Pemasukan</option>
                        <option value="Pengeluaran" {{ request('jenis_transaksi') === 'Pengeluaran' ? 'selected' : '' }}>Pengeluaran</option>
                    </select>
                </div>

                <!-- Tanggal Dari -->
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant mb-2">Tanggal Dari</label>
                    <input type="date" name="tanggal_dari" value="{{ request('tanggal_dari') }}" class="w-full px-3 py-2 rounded-lg border border-outline-variant focus:border-primary bg-surface-bright text-on-surface font-body-sm">
                </div>

                <!-- Tanggal Sampai -->
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant mb-2">Tanggal Sampai</label>
                    <input type="date" name="tanggal_sampai" value="{{ request('tanggal_sampai') }}" class="w-full px-3 py-2 rounded-lg border border-outline-variant focus:border-primary bg-surface-bright text-on-surface font-body-sm">
                </div>

                <!-- Jumlah Range -->
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant mb-2">Jumlah (Rp)</label>
                    <div class="flex gap-2 items-center">
                        <input type="number" name="min_jumlah" placeholder="Min" value="{{ request('min_jumlah') }}" class="flex-1 min-w-0 px-3 py-2 rounded-lg border border-outline-variant focus:border-primary bg-surface-bright text-on-surface font-body-sm">
                        <span class="text-on-surface-variant">-</span>
                        <input type="number" name="max_jumlah" placeholder="Max" value="{{ request('max_jumlah') }}" class="flex-1 min-w-0 px-3 py-2 rounded-lg border border-outline-variant focus:border-primary bg-surface-bright text-on-surface font-body-sm">
                    </div>
                </div>

                <!-- Filter Actions -->
                <div class="lg:col-span-4 flex gap-3 justify-end pt-4 border-t border-surface-variant">
                    <a href="{{ route('admin.keuangan.index') }}" class="px-4 py-2 font-label-sm text-label-sm text-on-surface-variant hover:bg-surface-container rounded-lg transition-colors border border-outline-variant">Atur Ulang Filter</a>
                    <button type="submit" class="px-4 py-2 font-label-sm text-label-sm bg-primary text-on-primary hover:bg-primary-container rounded-lg transition-colors shadow-sm">Terapkan Filter</button>
                </div>
            </div>
        </form>
    </div>

    <!-- Active Filters Indicator -->
    @if(isset($hasFilters) && $hasFilters)
    <div class="flex items-center gap-2 mb-4 px-1">
        <span class="material-symbols-outlined text-sm text-primary">filter_alt</span>
        <span class="font-caption text-caption text-on-surface-variant">Filter aktif — Menampilkan data yang difilter</span>
        <a href="{{ route('admin.keuangan.index') }}" class="ml-auto font-caption text-caption text-primary hover:underline flex items-center gap-1">
            <span class="material-symbols-outlined text-sm">close</span> Reset
        </a>
    </div>
    @endif
    
    <!-- Bento Grid: Key Metrics -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
        <!-- Revenue Card -->
        <div class="bg-gradient-to-br from-[#2A7844] to-[#1e5c33] rounded-2xl p-6 relative overflow-hidden group hover:-translate-y-1 hover:shadow-2xl hover:shadow-[#2A7844]/40 transition-all duration-300 border border-[#2A7844]/50">
            <div class="flex items-center justify-between mb-4 relative z-10">
                <div class="w-12 h-12 rounded-full bg-white/20 text-white flex items-center justify-center backdrop-blur-md">
                    <span class="material-symbols-outlined text-2xl">payments</span>
                </div>
                <div class="flex flex-col items-end">
                    <span class="text-xs font-bold uppercase tracking-wider text-emerald-100">Pemasukan</span>
                    @if(isset($hasFilters) && $hasFilters)
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 mt-1 rounded-full bg-white/20 text-white font-caption text-[10px]">
                            <span class="material-symbols-outlined text-[12px]">filter_alt</span> Filtered
                        </span>
                    @endif
                </div>
            </div>
            <div class="relative z-10">
                <h3 class="text-3xl lg:text-4xl font-black text-white tracking-tight truncate">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
                <p class="text-sm font-medium text-emerald-100/80 mt-1">Total Pemasukan</p>
            </div>
            <!-- Decorative shapes -->
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-white/10 rounded-full blur-2xl group-hover:bg-white/20 transition-colors"></div>
            <div class="absolute -bottom-10 -left-10 w-32 h-32 bg-emerald-400/20 rounded-full blur-2xl group-hover:bg-emerald-400/40 transition-colors"></div>
        </div>

        <!-- Expenses Card -->
        <div class="bg-white rounded-2xl p-6 relative overflow-hidden group hover:-translate-y-1 hover:shadow-[0_8px_30px_rgba(239,68,68,0.12)] transition-all duration-300 border border-slate-100 shadow-sm">
            <div class="flex items-center justify-between mb-4 relative z-10">
                <div class="w-12 h-12 rounded-2xl bg-red-50 text-red-500 flex items-center justify-center group-hover:scale-110 group-hover:bg-red-500 group-hover:text-white transition-all duration-300">
                    <span class="material-symbols-outlined text-2xl">receipt_long</span>
                </div>
                <div class="flex flex-col items-end">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Pengeluaran</span>
                    @if(isset($hasFilters) && $hasFilters)
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 mt-1 rounded-full bg-red-100 text-red-600 font-caption text-[10px]">
                            <span class="material-symbols-outlined text-[12px]">filter_alt</span> Filtered
                        </span>
                    @endif
                </div>
            </div>
            <div class="relative z-10">
                <h3 class="text-3xl lg:text-4xl font-black text-slate-800 tracking-tight truncate">Rp {{ number_format($totalExpenses, 0, ',', '.') }}</h3>
                <p class="text-sm font-medium text-slate-500 mt-1">Total Pengeluaran</p>
            </div>
            <div class="absolute -bottom-8 -right-8 w-28 h-28 bg-red-50/80 rounded-full blur-2xl group-hover:bg-red-100 transition-colors"></div>
        </div>

        <!-- Net Profit Card -->
        <div class="bg-white rounded-2xl p-6 relative overflow-hidden group hover:-translate-y-1 hover:shadow-[0_8px_30px_rgba(59,130,246,0.12)] transition-all duration-300 border border-slate-100 shadow-sm">
            <div class="flex items-center justify-between mb-4 relative z-10">
                <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center group-hover:scale-110 group-hover:bg-blue-600 group-hover:text-white transition-all duration-300">
                    <span class="material-symbols-outlined text-2xl">account_balance</span>
                </div>
                <div class="flex flex-col items-end">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Laba Bersih</span>
                    @if(isset($hasFilters) && $hasFilters)
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 mt-1 rounded-full bg-blue-100 text-blue-600 font-caption text-[10px]">
                            <span class="material-symbols-outlined text-[12px]">filter_alt</span> Filtered
                        </span>
                    @endif
                </div>
            </div>
            <div class="relative z-10">
                <h3 class="text-3xl lg:text-4xl font-black {{ $netProfit < 0 ? 'text-red-500' : 'text-slate-800' }} tracking-tight truncate">Rp {{ number_format($netProfit, 0, ',', '.') }}</h3>
                <p class="text-sm font-medium text-slate-500 mt-1">Total Laba Bersih</p>
            </div>
            <div class="absolute -bottom-8 -right-8 w-28 h-28 bg-blue-50/80 rounded-full blur-2xl group-hover:bg-blue-100 transition-colors"></div>
        </div>
    </div>
    
    <!-- Main Content Split: Transactions Table -->
    <div class="bg-transparent flex flex-col mt-4">
        <!-- Toolbar -->
        <div class="px-2 mb-4 flex flex-col sm:flex-row gap-4 items-center justify-between">
            <h3 class="font-h3 text-h3 text-slate-800">Riwayat Transaksi</h3>
            <span class="font-medium text-sm text-slate-500 bg-slate-100 px-3 py-1 rounded-full">{{ $laporans->total() }} transaksi ditemukan</span>
        </div>

        <!-- Table Container -->
        <div class="overflow-x-auto pb-8 pt-2">
            <table class="w-full text-left border-separate whitespace-nowrap" style="border-spacing: 0 12px;">
                <thead>
                    <tr class="font-label-sm text-xs text-slate-400 uppercase tracking-widest font-bold">
                        <th class="pb-2 px-6">Tanggal</th>
                        <th class="pb-2 px-6">Jenis Transaksi</th>
                        <th class="pb-2 px-6">Keterangan</th>
                        <th class="pb-2 px-6">Jumlah</th>
                        <th class="pb-2 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="font-body-md text-sm">
                    @forelse($laporans as $laporan)
                    <tr x-data="{ isRowOpen: false }" class="bg-white hover:bg-[#f8fdfa] transition-all duration-300 shadow-[0_4px_20px_rgba(0,0,0,0.03)] hover:shadow-[0_8px_30px_rgba(42,120,68,0.12)] group cursor-default">
                        <td class="py-5 px-6 rounded-l-2xl border-y border-l border-slate-100 group-hover:border-[#2A7844]/20 text-slate-600 font-medium transition-colors">
                            {{ \Carbon\Carbon::parse($laporan->tanggal)->format('d M Y') }}
                        </td>
                        <td :class="isRowOpen ? 'relative z-50' : 'relative z-10'" class="py-5 px-6 border-y border-slate-100 group-hover:border-[#2A7844]/20 transition-colors">
                            @if($laporan->pesanan_id !== null || in_array($laporan->jenis_transaksi, ['Pemasukan', 'Pengiriman Kurir', 'Pesanan Sudah Sampai']))
                                @php
                                    $selectColor = 'text-blue-700';
                                    $selectBg = 'bg-blue-50';
                                    $selectBorder = 'border-blue-200';
                                    $selectIcon = 'text-blue-500';
                                    $label = 'Pengiriman Kurir';
                                    
                                    if ($laporan->jenis_transaksi == 'Pemasukan') {
                                        $selectColor = 'text-emerald-700';
                                        $selectBg = 'bg-emerald-50';
                                        $selectBorder = 'border-emerald-200';
                                        $selectIcon = 'text-emerald-500';
                                        $label = 'Pemasukan (Otomatis)';
                                    } elseif ($laporan->jenis_transaksi == 'Pesanan Sudah Sampai') {
                                        $selectColor = 'text-teal-700';
                                        $selectBg = 'bg-teal-50';
                                        $selectBorder = 'border-teal-200';
                                        $label = 'Pesanan Sudah Sampai';
                                    }
                                @endphp
                                <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl border text-xs font-bold shadow-sm {{ $selectBg }} {{ $selectColor }} {{ $selectBorder }}">
                                    {{ $label }}
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl border text-xs font-bold shadow-sm bg-red-50 text-red-600 border-red-200 uppercase">
                                    Pengeluaran
                                </span>
                            @endif
                        </td>
                        <td class="py-5 px-6 border-y border-slate-100 group-hover:border-[#2A7844]/20 transition-colors text-slate-600">
                            <div class="flex flex-col items-start gap-2">
                                <span>{{ $laporan->keterangan }}</span>
                                @if($laporan->nota_pembayaran)
                                @php
                                    $isPdf = Str::endsWith(strtolower($laporan->nota_pembayaran), '.pdf');
                                    $notaUrl = config('app.env') === 'production' 
                                        ? '{{ env('SUPABASE_URL') }}/storage/v1/object/public/{{ env('SUPABASE_BUCKET') }}/nota_pembayaran/' . $laporan->nota_pembayaran . '?render=image' 
                                        : asset('storage/nota_pembayaran/' . $laporan->nota_pembayaran);
                                @endphp
                                <button type="button" onclick="if(typeof openViewNotaModal === 'function') openViewNotaModal('{{ $notaUrl }}', {{ $isPdf ? 'true' : 'false' }})" class="inline-flex items-center gap-1 px-3 py-1 bg-slate-50 hover:bg-blue-50 text-slate-500 hover:text-blue-600 rounded-lg text-[11px] font-bold border border-slate-200 transition-colors shadow-sm">
                                    <span class="material-symbols-outlined text-[14px]">receipt_long</span>
                                    Lihat Nota
                                </button>
                                @else
                                    @if($laporan->jenis_transaksi == 'Pengeluaran')
                                    <span class="inline-flex items-center gap-1 px-3 py-1 bg-slate-50 text-slate-400 rounded-lg text-[11px] font-medium border border-slate-100 border-dashed">
                                        <span class="material-symbols-outlined text-[14px]">receipt_long</span>
                                        Tidak ada nota
                                    </span>
                                    @endif
                                @endif
                            </div>
                        </td>
                        <td class="py-5 px-6 border-y border-slate-100 group-hover:border-[#2A7844]/20 transition-colors">
                            <span class="font-black text-lg {{ in_array($laporan->jenis_transaksi, ['Pemasukan', 'Pengiriman Kurir', 'Pesanan Sudah Sampai']) ? 'text-[#2A7844]' : 'text-slate-800' }}">
                                {{ in_array($laporan->jenis_transaksi, ['Pemasukan', 'Pengiriman Kurir', 'Pesanan Sudah Sampai']) ? '+' : '-' }} Rp {{ number_format($laporan->jumlah, 0, ',', '.') }}
                            </span>
                        </td>
                        <td class="py-5 px-6 rounded-r-2xl border-y border-r border-slate-100 group-hover:border-[#2A7844]/20 text-right transition-colors">
                            <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity translate-x-4 group-hover:translate-x-0 duration-300">
                                <button onclick="openEditKeuanganModal(this)" 
                                        data-laporan="{{ json_encode($laporan) }}" 
                                        data-nota-url="{{ $laporan->nota_pembayaran ? (config('app.env') === 'production' ? '{{ env('SUPABASE_URL') }}/storage/v1/object/public/{{ env('SUPABASE_BUCKET') }}/nota_pembayaran/' . $laporan->nota_pembayaran . '?render=image' : asset('storage/nota_pembayaran/' . $laporan->nota_pembayaran)) : '' }}" 
                                        data-nota-is-pdf="{{ $laporan->nota_pembayaran && Str::endsWith(strtolower($laporan->nota_pembayaran), '.pdf') ? 'true' : 'false' }}"
                                        class="w-8 h-8 flex items-center justify-center bg-white border border-slate-200 text-slate-400 hover:text-[#2A7844] hover:border-[#2A7844] hover:bg-[#2A7844]/5 rounded-xl shadow-sm transition-all" title="Edit Transaksi">
                                    <span class="material-symbols-outlined text-[16px]">edit</span>
                                </button>
                                <form action="{{ route('admin.keuangan.destroy', $laporan->id) }}" method="POST" class="inline delete-form" data-message="{{ $laporan->pesanan_id !== null ? 'Yakin ingin MEMBATALKAN pesanan ini? Laporan uang akan ditarik dan stok ternak akan dikembalikan menjadi Tersedia.' : 'Yakin ingin menghapus transaksi \''.$laporan->keterangan.'\'? Tindakan ini tidak bisa dibatalkan.' }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-8 h-8 flex items-center justify-center bg-white border border-slate-200 text-slate-400 hover:text-red-500 hover:border-red-500 hover:bg-red-50 rounded-xl shadow-sm transition-all" title="Hapus Transaksi">
                                        <span class="material-symbols-outlined text-[16px]">delete</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-16 px-6 text-center text-slate-400 bg-white rounded-3xl border border-dashed border-slate-200">
                            <div class="w-16 h-16 mx-auto mb-4 bg-slate-50 rounded-full flex items-center justify-center">
                                <span class="material-symbols-outlined text-3xl opacity-50">receipt_long</span>
                            </div>
                            <p class="font-medium text-slate-500">Belum ada transaksi tercatat.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="py-2">
            {{ $laporans->appends(request()->query())->links() }}
        </div>
    </div>
    </div>
</div>

<!-- Modal Tambah Transaksi -->
<div id="addKeuanganModal" class="fixed inset-0 z-50 hidden bg-slate-900/40 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-[24px] w-full max-w-lg shadow-[0_20px_60px_rgba(5,31,32,0.15)] border border-slate-100 overflow-hidden transform transition-all">
        <div class="px-8 py-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center">
                    <span class="material-symbols-outlined text-xl">add_circle</span>
                </div>
                <h3 class="text-xl font-bold text-slate-800">Catat Transaksi</h3>
            </div>
            <button onclick="document.getElementById('addKeuanganModal').classList.add('hidden')" class="w-8 h-8 flex items-center justify-center rounded-full text-slate-400 hover:bg-slate-100 hover:text-slate-600 transition-colors">
                <span class="material-symbols-outlined text-xl">close</span>
            </button>
        </div>
        <form id="addKeuanganForm" action="{{ route('admin.keuangan.store') }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-6">
            @csrf
            
            <div class="space-y-1.5">
                <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Tanggal Transaksi</label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">calendar_month</span>
                    <input type="date" name="tanggal" required value="{{ date('Y-m-d') }}" class="w-full pl-12 pr-4 py-3 rounded-xl border border-slate-200 focus:border-[#2A7844] focus:ring-2 focus:ring-[#2A7844]/20 bg-slate-50 focus:bg-white text-slate-800 font-medium transition-all outline-none">
                </div>
            </div>

            <div class="space-y-1.5">
                <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Jenis Transaksi</label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">category</span>
                    <select name="jenis_transaksi" required class="w-full pl-12 pr-10 py-3 rounded-xl border border-slate-200 focus:border-[#2A7844] focus:ring-2 focus:ring-[#2A7844]/20 bg-slate-50 focus:bg-white text-slate-800 font-medium transition-all outline-none appearance-none cursor-pointer">
                        <option value="Pengeluaran">Pengeluaran</option>
                    </select>
                    <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">expand_more</span>
                </div>
            </div>

            <div class="space-y-1.5">
                <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Keterangan</label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">description</span>
                    <input type="text" name="keterangan" required class="w-full pl-12 pr-4 py-3 rounded-xl border border-slate-200 focus:border-[#2A7844] focus:ring-2 focus:ring-[#2A7844]/20 bg-slate-50 focus:bg-white text-slate-800 font-medium transition-all outline-none" placeholder="Misal: Pembelian pakan ternak">
                </div>
            </div>

            <div class="space-y-1.5">
                <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Jumlah (Rp)</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 font-bold">Rp</span>
                    <input type="number" name="jumlah" required min="0" class="w-full pl-12 pr-4 py-3 rounded-xl border border-slate-200 focus:border-[#2A7844] focus:ring-2 focus:ring-[#2A7844]/20 bg-slate-50 focus:bg-white text-slate-800 font-black text-lg transition-all outline-none placeholder-slate-300" placeholder="0">
                </div>
            </div>

            <div class="space-y-1.5">
                <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Bukti Nota Transaksi</label>
                <div class="relative group">
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-hover:text-[#2A7844] transition-colors">upload_file</span>
                    <input type="file" name="nota_pembayaran" required accept=".jpg,.jpeg,.png,.pdf" class="w-full pl-12 pr-4 py-2.5 rounded-xl border border-slate-200 focus:border-[#2A7844] focus:ring-2 focus:ring-[#2A7844]/20 bg-slate-50 focus:bg-white text-slate-800 text-sm font-medium transition-all outline-none file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-[#2A7844]/10 file:text-[#2A7844] hover:file:bg-[#2A7844]/20 cursor-pointer">
                </div>
                <p class="text-[11px] text-slate-400 font-medium ml-1">Format: JPG, PNG, PDF. Maks: 5MB</p>
            </div>

            <div class="pt-6 mt-2 flex justify-end gap-3 border-t border-slate-100">
                <button type="button" onclick="document.getElementById('addKeuanganModal').classList.add('hidden')" class="px-6 py-2.5 font-bold text-sm text-slate-500 hover:bg-slate-100 rounded-xl transition-colors btn-batal">Batal</button>
                <button type="submit" class="px-6 py-2.5 font-bold text-sm bg-gradient-to-r from-[#2A7844] to-[#1e5c33] text-white hover:shadow-lg hover:shadow-[#2A7844]/30 hover:-translate-y-0.5 rounded-xl transition-all">Simpan Transaksi</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Transaksi -->
<div id="editKeuanganModal" class="fixed inset-0 z-50 hidden bg-slate-900/40 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-[24px] w-full max-w-lg shadow-[0_20px_60px_rgba(5,31,32,0.15)] border border-slate-100 overflow-hidden transform transition-all">
        <div class="px-8 py-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center">
                    <span class="material-symbols-outlined text-xl">edit_document</span>
                </div>
                <h3 class="text-xl font-bold text-slate-800">Edit Transaksi</h3>
            </div>
            <button onclick="document.getElementById('editKeuanganModal').classList.add('hidden')" class="w-8 h-8 flex items-center justify-center rounded-full text-slate-400 hover:bg-slate-100 hover:text-slate-600 transition-colors">
                <span class="material-symbols-outlined text-xl">close</span>
            </button>
        </div>
        <form id="editKeuanganForm" method="POST" enctype="multipart/form-data" class="p-8 space-y-6">
            @csrf
            @method('PUT')
            
            <div class="space-y-1.5">
                <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Tanggal Transaksi</label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">calendar_month</span>
                    <input type="date" name="tanggal" id="edit_tanggal" required class="w-full pl-12 pr-4 py-3 rounded-xl border border-slate-200 focus:border-[#2A7844] focus:ring-2 focus:ring-[#2A7844]/20 bg-slate-50 focus:bg-white text-slate-800 font-medium transition-all outline-none">
                </div>
            </div>

            <div class="space-y-1.5">
                <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Jenis Transaksi</label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">category</span>
                    <select name="jenis_transaksi" id="edit_jenis" required class="w-full pl-12 pr-10 py-3 rounded-xl border border-slate-200 focus:border-[#2A7844] focus:ring-2 focus:ring-[#2A7844]/20 bg-slate-50 focus:bg-white text-slate-800 font-medium transition-all outline-none appearance-none cursor-pointer">
                        <option value="Pemasukan">Pemasukan (Otomatis)</option>
                        <option value="Pengeluaran">Pengeluaran</option>
                        <option value="Pengiriman Kurir">Pengiriman Kurir</option>
                        <option value="Pesanan Sudah Sampai">Pesanan Sudah Sampai</option>
                    </select>
                    <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">expand_more</span>
                </div>
            </div>

            <div class="space-y-1.5">
                <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Keterangan</label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">description</span>
                    <input type="text" name="keterangan" id="edit_keterangan" required class="w-full pl-12 pr-4 py-3 rounded-xl border border-slate-200 focus:border-[#2A7844] focus:ring-2 focus:ring-[#2A7844]/20 bg-slate-50 focus:bg-white text-slate-800 font-medium transition-all outline-none">
                </div>
            </div>

            <div class="space-y-1.5">
                <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Jumlah (Rp)</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 font-bold">Rp</span>
                    <input type="number" name="jumlah" id="edit_jumlah" required min="0" class="w-full pl-12 pr-4 py-3 rounded-xl border border-slate-200 focus:border-[#2A7844] focus:ring-2 focus:ring-[#2A7844]/20 bg-slate-50 focus:bg-white text-slate-800 font-black text-lg transition-all outline-none">
                </div>
            </div>

            <div class="space-y-1.5">
                <label class="text-xs font-bold text-slate-500 uppercase tracking-wider flex justify-between items-center">
                    <span>Ganti Nota Transaksi <span class="text-[10px] text-slate-400 normal-case font-medium">(Opsional)</span></span>
                    <button type="button" id="edit_view_nota" onclick="" class="hidden text-[#2A7844] hover:text-emerald-700 font-semibold lowercase text-[11px] items-center gap-0.5">
                        <span class="material-symbols-outlined text-[14px]">visibility</span> Lihat Nota Saat Ini
                    </button>
                </label>
                <div class="relative group">
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-hover:text-blue-500 transition-colors">upload_file</span>
                    <input type="file" name="nota_pembayaran" accept=".jpg,.jpeg,.png,.pdf" class="w-full pl-12 pr-4 py-2.5 rounded-xl border border-slate-200 focus:border-[#2A7844] focus:ring-2 focus:ring-[#2A7844]/20 bg-slate-50 focus:bg-white text-slate-800 text-sm font-medium transition-all outline-none file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-600 hover:file:bg-blue-100 cursor-pointer">
                </div>
                <p class="text-[11px] text-slate-400 font-medium ml-1">Kosongkan jika tidak ingin mengubah nota.</p>
            </div>

            <div class="pt-6 mt-2 flex justify-end gap-3 border-t border-slate-100">
                <button type="button" onclick="document.getElementById('editKeuanganModal').classList.add('hidden')" class="px-6 py-2.5 font-bold text-sm text-slate-500 hover:bg-slate-100 rounded-xl transition-colors btn-batal">Batal</button>
                <button type="submit" class="px-6 py-2.5 font-bold text-sm bg-gradient-to-r from-blue-600 to-blue-500 text-white hover:shadow-lg hover:shadow-blue-500/30 hover:-translate-y-0.5 rounded-xl transition-all">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Lihat Nota -->
<div id="viewNotaModal" class="fixed inset-0 z-[60] hidden bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-[24px] w-full max-w-2xl shadow-[0_20px_60px_rgba(5,31,32,0.15)] border border-slate-100 overflow-hidden transform transition-all flex flex-col max-h-[90vh]">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center">
                    <span class="material-symbols-outlined text-xl">receipt_long</span>
                </div>
                <h3 class="text-xl font-bold text-slate-800">Nota Pembayaran</h3>
            </div>
            <button onclick="document.getElementById('viewNotaModal').classList.add('hidden')" class="w-8 h-8 flex items-center justify-center rounded-full text-slate-400 hover:bg-slate-100 hover:text-slate-600 transition-colors">
                <span class="material-symbols-outlined text-xl">close</span>
            </button>
        </div>
        <div class="p-4 flex-1 overflow-auto bg-slate-50/30 flex items-center justify-center min-h-[300px]">
            <iframe id="notaIframe" src="" class="w-full h-[60vh] rounded-xl border border-slate-200 hidden" frameborder="0"></iframe>
            <img id="notaImage" src="" class="max-w-full max-h-[60vh] rounded-xl object-contain hidden shadow-sm border border-slate-200" alt="Nota Pembayaran" onerror="this.onerror=null; this.src='{{ asset('images/placeholder-medis.png') }}';">
        </div>
        <div class="px-6 py-4 bg-white border-t border-slate-100 flex justify-between items-center">
            <a id="downloadNotaBtn" href="#" download class="inline-flex items-center gap-2 px-4 py-2 font-bold text-sm text-[#2A7844] hover:bg-[#2A7844]/10 rounded-xl transition-colors">
                <span class="material-symbols-outlined text-[18px]">download</span> Unduh File
            </a>
            <button type="button" onclick="document.getElementById('viewNotaModal').classList.add('hidden')" class="px-6 py-2 font-bold text-sm bg-slate-800 text-white hover:bg-slate-700 rounded-xl transition-all">Tutup</button>
        </div>
    </div>
</div>

<script>
    window.currentNetProfit = {{ $netProfit ?? 0 }};
    
    function openViewNotaModal(fileUrl, isPdf) {
        const modal = document.getElementById('viewNotaModal');
        const iframe = document.getElementById('notaIframe');
        const image = document.getElementById('notaImage');
        const downloadBtn = document.getElementById('downloadNotaBtn');

        modal.classList.remove('hidden');
        iframe.classList.add('hidden');
        image.classList.add('hidden');
        
        // Ensure URL is properly encoded for spaces
        const encodedUrl = encodeURI(fileUrl);
        downloadBtn.href = encodedUrl;

        // Ensure isPdf is correctly evaluated as boolean, or fallback to URL extension check (stripping query parameters)
        let isPdfBool = isPdf === true || isPdf === 'true';
        if (typeof isPdf === 'undefined' || isPdf === null) {
            const urlWithoutQuery = fileUrl.split('?')[0];
            isPdfBool = urlWithoutQuery.toLowerCase().endsWith('.pdf');
        }

        if (isPdfBool) {
            iframe.src = encodedUrl;
            iframe.classList.remove('hidden');
        } else {
            image.src = encodedUrl;
            image.classList.remove('hidden');
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const addForm = document.getElementById('addKeuanganForm');
        if (addForm) {
            addForm.addEventListener('submit', function(e) {
                const jenis = this.querySelector('[name="jenis_transaksi"]').value;
                const jumlah = parseFloat(this.querySelector('[name="jumlah"]').value) || 0;
                
                if (jenis === 'Pengeluaran' && jumlah > window.currentNetProfit) {
                    e.preventDefault();
                    if (window.showToast) {
                        window.showToast('Peringatan: Jumlah pengeluaran melebihi total laba bersih yang tersedia!', 'error');
                    } else {
                        alert('Peringatan: Jumlah pengeluaran melebihi total laba bersih yang tersedia!');
                    }
                }
            });
        }

        const editForm = document.getElementById('editKeuanganForm');
        if (editForm) {
            editForm.addEventListener('submit', function(e) {
                const jenis = this.querySelector('[name="jenis_transaksi"]').value;
                const jumlah = parseFloat(this.querySelector('[name="jumlah"]').value) || 0;
                
                if (jenis === 'Pengeluaran' && jumlah > window.currentNetProfit) {
                    e.preventDefault();
                    if (window.showToast) {
                        window.showToast('Peringatan: Jumlah pengeluaran melebihi total laba bersih yang tersedia!', 'error');
                    } else {
                        alert('Peringatan: Jumlah pengeluaran melebihi total laba bersih yang tersedia!');
                    }
                }
            });
        }
    });

    function openEditKeuanganModal(button) {
        const laporan = JSON.parse(button.getAttribute('data-laporan'));
        
        document.getElementById('editKeuanganForm').action = `/admin/keuangan/${laporan.id}`;
        document.getElementById('edit_tanggal').value = laporan.tanggal;
        document.getElementById('edit_keterangan').value = laporan.keterangan;
        document.getElementById('edit_jumlah').value = laporan.jumlah;
        
        const jenisSelect = document.getElementById('edit_jenis');
        jenisSelect.innerHTML = ''; // Clear options
        
        const tanggalInput = document.getElementById('edit_tanggal');
        const keteranganInput = document.getElementById('edit_keterangan');
        const jumlahInput = document.getElementById('edit_jumlah');
        
        if (laporan.pesanan_id !== null || ['Pemasukan', 'Pengiriman Kurir', 'Pesanan Sudah Sampai'].includes(laporan.jenis_transaksi)) {
            // It is an automatic order-linked transaction
            // Options: Pemasukan, Pengiriman Kurir, Pesanan Sudah Sampai
            const optPemasukan = document.createElement('option');
            optPemasukan.value = 'Pemasukan';
            optPemasukan.textContent = 'Pemasukan (Otomatis)';
            jenisSelect.appendChild(optPemasukan);

            const optKurir = document.createElement('option');
            optKurir.value = 'Pengiriman Kurir';
            optKurir.textContent = 'Pengiriman Kurir';
            jenisSelect.appendChild(optKurir);

            const optSampai = document.createElement('option');
            optSampai.value = 'Pesanan Sudah Sampai';
            optSampai.textContent = 'Pesanan Sudah Sampai';
            jenisSelect.appendChild(optSampai);
            
            jenisSelect.value = laporan.jenis_transaksi;
            
            // Set readonly for automatic fields
            tanggalInput.readOnly = true;
            keteranganInput.readOnly = true;
            jumlahInput.readOnly = true;
            
            // Add visual cue for readonly fields
            tanggalInput.classList.add('bg-slate-100', 'cursor-not-allowed');
            keteranganInput.classList.add('bg-slate-100', 'cursor-not-allowed');
            jumlahInput.classList.add('bg-slate-100', 'cursor-not-allowed');
        } else {
            // It is a manual transaction
            // Options: Pengeluaran only
            const optPengeluaran = document.createElement('option');
            optPengeluaran.value = 'Pengeluaran';
            optPengeluaran.textContent = 'Pengeluaran';
            jenisSelect.appendChild(optPengeluaran);
            
            jenisSelect.value = 'Pengeluaran';
            
            // Remove readonly for manual fields
            tanggalInput.readOnly = false;
            keteranganInput.readOnly = false;
            jumlahInput.readOnly = false;
            
            // Remove visual cue
            tanggalInput.classList.remove('bg-slate-100', 'cursor-not-allowed');
            keteranganInput.classList.remove('bg-slate-100', 'cursor-not-allowed');
            jumlahInput.classList.remove('bg-slate-100', 'cursor-not-allowed');
        }

        const viewNotaBtn = document.getElementById('edit_view_nota');
        const notaUrl = button.getAttribute('data-nota-url');
        const isPdf = button.getAttribute('data-nota-is-pdf') === 'true';
        if (laporan.nota_pembayaran) {
            viewNotaBtn.setAttribute('onclick', `openViewNotaModal('${notaUrl}', ${isPdf})`);
            viewNotaBtn.classList.remove('hidden');
            viewNotaBtn.classList.add('inline-flex');
        } else {
            viewNotaBtn.setAttribute('onclick', '');
            viewNotaBtn.classList.add('hidden');
            viewNotaBtn.classList.remove('inline-flex');
        }

        document.getElementById('editKeuanganModal').classList.remove('hidden');
    }

    function changeLaporanJenis(selectElement, laporanId) {
        const jenis = selectElement.value;
        
        // Show page loader
        document.getElementById('global-page-loader').style.display = 'flex';
        
        fetch(`/admin/keuangan/${laporanId}/update-jenis`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                jenis_transaksi: jenis
            })
        })
        .then(res => res.json())
        .then(data => {
            document.getElementById('global-page-loader').style.display = 'none';
            if (data.success) {
                window.showToast(data.message, 'success');
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                window.showToast(data.message || 'Gagal mengubah jenis transaksi.', 'error');
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            }
        })
        .catch(err => {
            document.getElementById('global-page-loader').style.display = 'none';
            window.showToast('Terjadi kesalahan jaringan.', 'error');
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        });
    }
</script>

@endsection
