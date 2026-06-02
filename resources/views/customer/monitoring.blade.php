@extends('layouts.customer')

@section('title', 'Monitoring Pesanan')

@section('content')
<main class="max-w-[1200px] mx-auto px-6 py-10 space-y-12">

    {{-- ── Header Section ── --}}
    <header class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4 pb-6 border-b border-slate-100">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="w-2.5 h-2.5 rounded-full" style="background:#2A7844;"></span>
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Pelacakan Ternak Anda</span>
            </div>
            <h1 class="text-3xl font-extrabold tracking-tight" style="color:#0E3247; letter-spacing:-0.02em;">Monitoring & Pengiriman</h1>
            <p class="text-sm mt-1" style="color:#64748B;">Pantau status kesehatan, persiapan kandang, dan tahapan pengiriman pesanan ternak aktif Anda.</p>
        </div>
    </header>

    {{-- ── Active Monitoring Tracker ── --}}
    <div class="space-y-8">
        @forelse($pesanans as $pesanan)
        <div class="glass-card p-6 md:p-8 space-y-6">
            
            {{-- Top Info Row --}}
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-4 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0" style="background:#f0faf3;">
                        <span class="material-symbols-outlined text-emerald-700" style="font-size:22px;">pets</span>
                    </div>
                    <div>
                        <div class="text-[10px] font-bold text-slate-400 uppercase leading-none">ORDER ID</div>
                        <h3 class="text-sm font-extrabold text-slate-800 mt-1">
                            #GTN-{{ str_pad($pesanan->id, 3, '0', STR_PAD_LEFT) }}
                        </h3>
                    </div>
                </div>

                <div class="grid grid-cols-2 md:flex md:items-center gap-4 md:gap-8">
                    <div>
                        <span class="text-[9px] font-bold text-slate-400 uppercase block">Nama Ternak</span>
                        <span class="text-xs font-extrabold text-slate-800">{{ $pesanan->produk->nama_produk ?? 'Ternak Unggulan' }}</span>
                    </div>
                    <div>
                        <span class="text-[9px] font-bold text-slate-400 uppercase block">Harga Beli</span>
                        <span class="text-xs font-extrabold text-emerald-700">Rp {{ number_format($pesanan->harga_jual, 0, ',', '.') }}</span>
                    </div>
                    <div>
                        <span class="text-[9px] font-bold text-slate-400 uppercase block">Tanggal Pembelian</span>
                        <span class="text-xs font-bold text-slate-600">{{ $pesanan->updated_at->translatedFormat('d M Y') }}</span>
                    </div>
                    <div>
                        <span class="text-[9px] font-bold text-slate-400 uppercase block">Status</span>
                        <span class="badge-premium-green py-0.5 px-2.5 text-[10px]">{{ $pesanan->status }}</span>
                    </div>
                </div>
            </div>

            {{-- ── 2026 SaaS Milestone Progress Tracker ── --}}
            <div class="space-y-4">
                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400">Tahapan Proses & Logistik</h4>
                
                {{-- Tracker bar --}}
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 pt-4 relative">
                    
                    {{-- Connecting lines for desktop --}}
                    <div class="absolute top-[37px] left-[12%] right-[12%] h-0.5 bg-slate-100 hidden md:block z-0">
                        <div class="h-full bg-emerald-500 transition-all duration-500" style="width: 66%;"></div>
                    </div>

                    {{-- Step 1: Pesanan Masuk --}}
                    <div class="flex items-center md:flex-col md:text-center gap-3 relative z-10">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center border-2 border-emerald-500 bg-white shadow-md text-emerald-600 shrink-0">
                            <span class="material-symbols-outlined text-base">check</span>
                        </div>
                        <div class="md:mt-2">
                            <div class="text-xs font-extrabold text-slate-800">Pesanan Masuk</div>
                            <p class="text-[10px] text-slate-400 leading-snug">Berhasil didaftarkan</p>
                        </div>
                    </div>

                    {{-- Step 2: Konfirmasi Admin --}}
                    <div class="flex items-center md:flex-col md:text-center gap-3 relative z-10">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center border-2 border-emerald-500 bg-white shadow-md text-emerald-600 shrink-0">
                            <span class="material-symbols-outlined text-base">approval</span>
                        </div>
                        <div class="md:mt-2">
                            <div class="text-xs font-extrabold text-slate-800">Disetujui Admin</div>
                            <p class="text-[10px] text-slate-400 leading-snug">Harga & transaksi sah</p>
                        </div>
                    </div>

                    {{-- Step 3: Karantina & Pengkondisian --}}
                    <div class="flex items-center md:flex-col md:text-center gap-3 relative z-10">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center border-2 border-emerald-500 bg-white shadow-md text-emerald-600 shrink-0 animate-pulse">
                            <span class="material-symbols-outlined text-base text-emerald-600">health_and_safety</span>
                        </div>
                        <div class="md:mt-2">
                            <div class="text-xs font-extrabold text-slate-800">Karantina & Vitamin</div>
                            <p class="text-[10px] text-slate-500 leading-snug font-semibold text-emerald-700">Dalam Proses</p>
                        </div>
                    </div>

                    {{-- Step 4: Pengiriman & Tiba --}}
                    <div class="flex items-center md:flex-col md:text-center gap-3 relative z-10">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center border bg-slate-50 border-slate-200 text-slate-400 shrink-0">
                            <span class="material-symbols-outlined text-base">local_shipping</span>
                        </div>
                        <div class="md:mt-2">
                            <div class="text-xs font-extrabold text-slate-400">Pengiriman Kurir</div>
                            <p class="text-[10px] text-slate-400 leading-snug">Ternak siap dikirim</p>
                        </div>
                    </div>

                </div>
            </div>

            {{-- Animal Detail Drawer --}}
            @if($pesanan->produk && $pesanan->produk->inventaris)
            <div class="bg-slate-50/50 p-4 rounded-xl border border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-4 text-xs font-medium text-slate-600">
                <span class="flex items-center gap-1.5 font-bold" style="color:#0E3247;">
                    <span class="material-symbols-outlined text-emerald-600" style="font-size:16px;">health_and_safety</span>
                    Spesifikasi Genetika Ternak:
                </span>
                <div class="flex flex-wrap items-center gap-x-6 gap-y-2">
                    <span>Jenis: <strong>{{ $pesanan->produk->inventaris->jenis ?? '-' }}</strong></span>
                    <span>Gender: <strong>{{ $pesanan->produk->inventaris->gender ?? '-' }}</strong></span>
                    <span>Umur: <strong>{{ $pesanan->produk->inventaris->umur ?? '-' }} Bulan</strong></span>
                    <span>Berat: <strong>{{ $pesanan->produk->inventaris->berat ?? '-' }} Kg</strong></span>
                </div>
            </div>
            @endif

        </div>
        @empty
        <div class="text-center py-20 bg-white rounded-3xl border border-slate-100 shadow-sm max-w-xl mx-auto">
            <div class="w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4" style="background:#f0faf3;">
                <span class="material-symbols-outlined text-3xl" style="color:#2A7844;">local_shipping</span>
            </div>
            <h3 class="font-bold text-lg mb-1" style="color:#0E3247;">Tidak Ada Pesanan Aktif</h3>
            <p class="text-xs max-w-sm mx-auto" style="color:#94A3B8;">
                Pesanan ternak yang disetujui oleh admin akan otomatis tertera di sini untuk dipantau proses kesehatannya.
            </p>
        </div>
        @endforelse
    </div>

    {{-- ── Order Transaction History ── --}}
    <section class="space-y-6 pt-4">
        <div class="flex items-center gap-2">
            <div class="w-1.5 h-5 rounded-full" style="background:#2A7844;"></div>
            <h2 class="text-xs font-bold uppercase tracking-wider" style="color:#64748B;">Arsip Riwayat Pembelian</h2>
        </div>

        <div class="bg-white border border-slate-100 rounded-2xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100">
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-400">Order ID</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-400">Nama Ternak</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-400">Tanggal Transaksi</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-400">Total Harga</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-400">Status Pembelian</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($pesanans as $pesanan)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4 text-xs font-extrabold text-slate-800">
                                #GTN-{{ str_pad($pesanan->id, 3, '0', STR_PAD_LEFT) }}
                            </td>
                            <td class="px-6 py-4 text-xs font-bold text-slate-700">
                                {{ $pesanan->produk->nama_produk ?? 'Ternak Pilihan' }}
                            </td>
                            <td class="px-6 py-4 text-xs font-semibold text-slate-500">
                                {{ $pesanan->updated_at->translatedFormat('d M Y') }}
                            </td>
                            <td class="px-6 py-4 text-xs font-extrabold text-emerald-700">
                                Rp {{ number_format($pesanan->harga_jual, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="badge-premium-green py-0.5 px-2.5 text-[10px]">
                                    Selesai / Aktif
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-xs font-semibold text-slate-400">
                                Belum ada riwayat transaksi terarsip.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>

</main>
@endsection
