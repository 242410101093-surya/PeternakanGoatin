@extends('layouts.customer')

@section('title', 'Monitoring Pesanan')

@section('content')
<main class="max-w-[1200px] mx-auto px-6 py-10 space-y-12">

    {{-- ── Header Section ── --}}
    <header class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4 pb-6 border-b border-slate-100" data-aos="fade-down">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="w-2.5 h-2.5 rounded-full" style="background:#2A7844;"></span>
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Pelacakan Ternak Anda</span>
            </div>
            <h1 class="text-3xl font-extrabold tracking-tight" style="color:#051F20; letter-spacing:-0.02em;">Monitoring & Pengiriman</h1>
            <p class="text-sm mt-1" style="color:#64748B;">Pantau status kesehatan, persiapan kandang, dan tahapan pengiriman pesanan ternak aktif Anda.</p>
        </div>
    </header>

    {{-- ── Active Monitoring Tracker ── --}}
    <div class="space-y-8">
        @forelse($pesanans as $pesanan)
            @php
                $status = $pesanan->status;
                
                // Timezone calculation based on address keywords
                $alamat = $pesanan->alamat ?? ($pesanan->user->alamat ?? '');
                $alamatLower = strtolower($alamat);
                
                $timezoneName = 'Asia/Jakarta';
                $timezoneSuffix = 'WIB';
                
                $witKeywords = ['papua', 'maluku', 'halmahera', 'ambon', 'tual', 'ternate', 'tidore', 'jayapura', 'sorong', 'merauke', 'mimika', 'manokwari', 'biak'];
                $witaKeywords = ['bali', 'ntb', 'ntt', 'nusa tenggara', 'lombok', 'sumbawa', 'flores', 'kupang', 'sulawesi', 'gorontalo', 'makassar', 'manado', 'palu', 'kendari', 'bitung', 'poso', 'kalimantan timur', 'kalimantan selatan', 'kalimantan utara', 'kaltim', 'kalsel', 'kalut', 'samarinda', 'balikpapan', 'banjarmasin', 'tarakan'];
                
                foreach ($witKeywords as $keyword) {
                    if (str_contains($alamatLower, $keyword)) {
                        $timezoneName = 'Asia/Jayapura';
                        $timezoneSuffix = 'WIT';
                        break;
                    }
                }
                
                if ($timezoneSuffix === 'WIB') {
                    foreach ($witaKeywords as $keyword) {
                        if (str_contains($alamatLower, $keyword)) {
                            $timezoneName = 'Asia/Makassar';
                            $timezoneSuffix = 'WITA';
                            break;
                        }
                    }
                }
                
                // Convert created_at timezone dynamically
                $orderTime = $pesanan->created_at ? $pesanan->created_at->setTimezone($timezoneName) : now()->setTimezone($timezoneName);
                $formattedTime = $orderTime->translatedFormat('H:i') . ' ' . $timezoneSuffix;

                // Step 3 (Karantina & Vitamin) - active during Disetujui
                $step3_class = "border-2 border-emerald-500 bg-white shadow-md text-emerald-600";
                $step3_icon = "health_and_safety";
                $step3_desc = "Dalam Proses";
                $step3_desc_class = "text-emerald-700 font-semibold";
                $step3_pulse = "animate-pulse";
                
                // Step 4 (Pengiriman Kurir) - pending during Disetujui
                $step4_class = "border border-slate-200 bg-slate-50 text-slate-400";
                $step4_icon = "local_shipping";
                $step4_desc = "Ternak siap dikirim";
                $step4_desc_class = "text-slate-400";
                $step4_pulse = "";
                $step4_title_class = "text-slate-400";
                
                // Step 5 (Pesanan Sudah Sampai) - pending during Disetujui / Pengiriman Kurir
                $step5_class = "border border-slate-200 bg-slate-50 text-slate-400";
                $step5_icon = "check_circle";
                $step5_desc = "Menunggu tiba";
                $step5_desc_class = "text-slate-400";
                $step5_pulse = "";
                $step5_title_class = "text-slate-400";
                
                $line_width = "50%";
                
                if ($status === 'Pengiriman Kurir') {
                    // Step 3 is completed
                    $step3_icon = "check";
                    $step3_desc = "Selesai";
                    $step3_pulse = "";
                    
                    // Step 4 is active
                    $step4_class = "border-2 border-emerald-500 bg-white shadow-md text-emerald-600";
                    $step4_desc = "Sedang Dikirim";
                    $step4_desc_class = "text-emerald-700 font-semibold animate-pulse";
                    $step4_pulse = "animate-pulse";
                    $step4_title_class = "text-slate-800";
                    
                    $line_width = "75%";
                } elseif ($status === 'Pesanan Sudah Sampai') {
                    // Step 3 is completed
                    $step3_icon = "check";
                    $step3_desc = "Selesai";
                    $step3_pulse = "";
                    
                    // Step 4 is completed
                    $step4_class = "border-2 border-emerald-500 bg-white shadow-md text-emerald-600";
                    $step4_icon = "check";
                    $step4_desc = "Selesai";
                    $step4_pulse = "";
                    $step4_title_class = "text-slate-800";
                    
                    // Step 5 is completed
                    $step5_class = "border-2 border-emerald-500 bg-white shadow-md text-emerald-600";
                    $step5_icon = "check";
                    $step5_desc = "Sudah Sampai";
                    $step5_desc_class = "text-emerald-700 font-semibold";
                    $step5_pulse = "";
                    $step5_title_class = "text-slate-800";
                    
                    $line_width = "100%";
                }
            @endphp
        <div class="glass-card p-6 md:p-8 space-y-6" data-aos="fade-right" data-aos-delay="{{ $loop->index * 100 }}">
            
            {{-- Top Info Row --}}
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-4 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 shadow-sm" style="background:#f0faf3;">
                        <span class="material-symbols-outlined text-emerald-700 font-bold" style="font-size:22px;">receipt_long</span>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="text-[9px] font-extrabold text-slate-400 uppercase leading-none tracking-wider">ORDER ID</span>
                            <span class="badge-premium-green py-0.5 px-2 text-[9px] font-extrabold">{{ $pesanan->status }}</span>
                        </div>
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
                        <span class="text-[9px] font-bold text-slate-400 uppercase block">Waktu Transaksi</span>
                        <span class="text-xs font-bold text-slate-600 flex items-center gap-1.5 mt-0.5">
                            <span class="material-symbols-outlined text-emerald-600" style="font-size: 15px;">calendar_month</span>
                            <span>{{ $pesanan->created_at ? $pesanan->created_at->translatedFormat('d M Y') : '-' }}</span>
                            <span class="text-slate-300">•</span>
                            <span class="material-symbols-outlined text-emerald-600" style="font-size: 15px;">schedule</span>
                            <span>{{ $formattedTime }}</span>
                        </span>
                    </div>
                </div>
            </div>

            {{-- ── 2026 SaaS Milestone Progress Tracker ── --}}
            <div class="py-2.5 px-4 bg-slate-50/45 rounded-2xl border border-slate-100/60 space-y-3">
                <div class="flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-emerald-600 text-sm">route</span>
                    <h4 class="text-xs font-bold uppercase tracking-wider text-slate-500">Tahapan Proses & Logistik</h4>
                </div>
                
                {{-- Tracker bar --}}
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4 pt-1 pb-2 relative">
                    
                    {{-- Connecting lines for desktop --}}
                    <div class="absolute top-[26px] left-[10%] right-[10%] h-[3px] bg-slate-100 hidden md:block z-0">
                        <div class="h-full bg-emerald-500 transition-all duration-500" style="width: {{ $line_width }};"></div>
                    </div>

                    {{-- Step 1: Pesanan Masuk --}}
                    <div class="flex items-center md:flex-col md:text-center gap-3 relative z-10">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center border border-emerald-500 bg-white shadow-sm text-emerald-600 shrink-0">
                            <span class="material-symbols-outlined text-sm font-bold">check</span>
                        </div>
                        <div class="md:mt-1">
                            <div class="text-[11px] font-extrabold text-slate-800">Pesanan Masuk</div>
                            <p class="text-[9px] text-slate-400 leading-none mt-0.5">Berhasil didaftarkan</p>
                        </div>
                    </div>

                    {{-- Step 2: Konfirmasi Admin --}}
                    <div class="flex items-center md:flex-col md:text-center gap-3 relative z-10">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center border border-emerald-500 bg-white shadow-sm text-emerald-600 shrink-0">
                            <span class="material-symbols-outlined text-sm font-bold">approval</span>
                        </div>
                        <div class="md:mt-1">
                            <div class="text-[11px] font-extrabold text-slate-800">Disetujui Admin</div>
                            <p class="text-[9px] text-slate-400 leading-none mt-0.5">Transaksi sah</p>
                        </div>
                    </div>

                    {{-- Step 3: Karantina & Pengkondisian --}}
                    <div class="flex items-center md:flex-col md:text-center gap-3 relative z-10">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center {{ $step3_class }} {{ $step3_pulse }} shrink-0">
                            <span class="material-symbols-outlined text-sm font-bold">{{ $step3_icon }}</span>
                        </div>
                        <div class="md:mt-1">
                            <div class="text-[11px] font-extrabold text-slate-800">Karantina & Vitamin</div>
                            <p class="text-[9px] leading-none mt-0.5 {{ $step3_desc_class }}">{{ $step3_desc }}</p>
                        </div>
                    </div>

                    {{-- Step 4: Pengiriman Kurir --}}
                    <div class="flex items-center md:flex-col md:text-center gap-3 relative z-10">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center {{ $step4_class }} {{ $step4_pulse }} shrink-0">
                            <span class="material-symbols-outlined text-sm font-bold">{{ $step4_icon }}</span>
                        </div>
                        <div class="md:mt-1">
                            <div class="text-[11px] font-extrabold {{ $step4_title_class }}">Pengiriman Kurir</div>
                            <p class="text-[9px] leading-none mt-0.5 {{ $step4_desc_class }}">{{ $step4_desc }}</p>
                        </div>
                    </div>

                    {{-- Step 5: Pesanan Sudah Sampai --}}
                    <div class="flex items-center md:flex-col md:text-center gap-3 relative z-10">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center {{ $step5_class }} {{ $step5_pulse }} shrink-0">
                            <span class="material-symbols-outlined text-sm font-bold">{{ $step5_icon }}</span>
                        </div>
                        <div class="md:mt-1">
                            <div class="text-[11px] font-extrabold {{ $step5_title_class }}">Pesanan Sudah Sampai</div>
                            <p class="text-[9px] leading-none mt-0.5 {{ $step5_desc_class }}">{{ $step5_desc }}</p>
                        </div>
                    </div>

                </div>
            </div>

            {{-- Redesigned Side-by-Side Grid (Compact & Modern) --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 pt-4 border-t border-slate-100">
                
                {{-- Column 1: Info Ternak & Kesehatan --}}
                <div class="space-y-4">
                    
                    {{-- Animal Detail Drawer --}}
                    @if($pesanan->produk && $pesanan->produk->inventaris)
                    <div class="bg-slate-50/50 p-4 rounded-xl border border-slate-100 space-y-3">
                        <span class="flex items-center gap-1.5 font-bold text-xs" style="color:#051F20;">
                            <span class="material-symbols-outlined text-emerald-600" style="font-size:16px;">health_and_safety</span>
                            Spesifikasi Genetika Ternak:
                        </span>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 text-xs font-semibold text-slate-600">
                            <div class="bg-white p-2 rounded-lg border border-slate-100/80 shadow-sm text-center">
                                <span class="text-[9px] text-slate-400 uppercase block leading-none mb-1">Jenis</span>
                                <strong class="text-slate-800 text-[11px]">{{ $pesanan->produk->inventaris->jenis ?? '-' }}</strong>
                            </div>
                            <div class="bg-white p-2 rounded-lg border border-slate-100/80 shadow-sm text-center">
                                <span class="text-[9px] text-slate-400 uppercase block leading-none mb-1">Gender</span>
                                <strong class="text-slate-800 text-[11px]">{{ $pesanan->produk->inventaris->gender ?? '-' }}</strong>
                            </div>
                            <div class="bg-white p-2 rounded-lg border border-slate-100/80 shadow-sm text-center">
                                <span class="text-[9px] text-slate-400 uppercase block leading-none mb-1">Umur</span>
                                <strong class="text-slate-800 text-[11px]">{{ $pesanan->produk->inventaris->umur ?? '-' }} Bln</strong>
                            </div>
                            <div class="bg-white p-2 rounded-lg border border-slate-100/80 shadow-sm text-center">
                                <span class="text-[9px] text-slate-400 uppercase block leading-none mb-1">Berat</span>
                                <strong class="text-slate-800 text-[11px]">{{ $pesanan->produk->inventaris->berat ?? '-' }} Kg</strong>
                            </div>
                        </div>
                    </div>

                    {{-- Riwayat Kesehatan Section (Compact Timeline) --}}
                    <div class="bg-slate-50/30 p-4 rounded-xl border border-slate-100/80 space-y-3">
                        <div class="flex items-center gap-1.5 font-bold text-xs" style="color:#051F20;">
                            <span class="material-symbols-outlined text-emerald-600 animate-pulse" style="font-size:18px;">medical_services</span>
                            Riwayat Medis & Kesehatan Ternak:
                        </div>
                        
                        @php
                            $medicalRecords = $pesanan->produk->inventaris->rekamMedis()->orderBy('tanggal', 'desc')->get();
                        @endphp

                        @if($medicalRecords->count() > 0)
                            <div class="border-l-2 border-slate-200 pl-4 space-y-4 py-1 ml-2">
                                @foreach($medicalRecords as $record)
                                    <div class="relative">
                                        <div class="absolute -left-[22px] top-1.5 w-2 h-2 rounded-full bg-emerald-500 border border-white"></div>
                                        <div class="flex justify-between items-center text-[9px] text-slate-400 font-bold uppercase leading-none">
                                            <span>{{ \Carbon\Carbon::parse($record->tanggal)->translatedFormat('d M Y') }}</span>
                                            <span class="badge-premium-green py-0.5 px-1.5 text-[8px] uppercase tracking-wider font-extrabold">{{ $record->status }}</span>
                                        </div>
                                        <h5 class="text-xs font-bold text-slate-800 mt-1">{{ $record->diagnosa }}</h5>
                                        <p class="text-[11px] text-slate-500 leading-normal mt-0.5">
                                            <span class="font-semibold text-slate-600">Tindakan:</span> {{ $record->tindakan }} 
                                            @if($record->dokter_hewan)
                                                <span class="text-slate-300 mx-1">•</span> <span class="font-semibold text-slate-600">Vet:</span> {{ $record->dokter_hewan }}
                                            @endif
                                        </p>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="flex items-center gap-2 text-[11px] text-slate-400 italic bg-white p-3 rounded-lg border border-slate-100 shadow-sm">
                                <span class="material-symbols-outlined text-emerald-600" style="font-size: 16px;">check_circle</span>
                                <span>Belum ada riwayat rekam medis tercatat. Kondisi dinyatakan sehat & bugar.</span>
                            </div>
                        @endif
                    </div>
                    @endif
                </div>

                {{-- Column 2: Rute & Alamat --}}
                <div class="space-y-4">
                    
                    {{-- Receiver Address --}}
                    @php
                        $tipeAlamat = $pesanan->tipe_alamat ?? ($pesanan->user->tipe_alamat ?? '');
                        $lat = $pesanan->latitude ?? ($pesanan->user->latitude ?? '');
                        $lng = $pesanan->longitude ?? ($pesanan->user->longitude ?? '');
                    @endphp
                    
                    <div class="bg-white p-4 rounded-xl border border-slate-100 shadow-sm space-y-3 relative overflow-hidden group hover:border-emerald-600/20 transition-all duration-300">
                        <!-- Top header of Receiver block -->
                        <div class="flex items-center justify-between flex-wrap gap-2 pb-2 border-b border-slate-50">
                            <div class="flex items-center gap-1.5 font-extrabold text-xs text-slate-800">
                                <span class="material-symbols-outlined text-emerald-700" style="font-size:18px;">call_received</span>
                                Alamat Penerima Ternak
                            </div>
                            @if($tipeAlamat)
                                <span class="badge-premium-blue py-0.5 px-2 text-[9px] font-extrabold inline-flex items-center">
                                    <span class="material-symbols-outlined text-[10px] mr-1">
                                        {{ $tipeAlamat == 'Rumah' ? 'home' : 'corporate_fare' }}
                                    </span>
                                    {{ $tipeAlamat }}
                                </span>
                            @endif
                        </div>

                        @if($alamat)
                            <div class="space-y-2">
                                <p class="text-xs text-slate-600 leading-relaxed font-medium">
                                    {{ $alamat }}
                                </p>
                                @if($lat && $lng)
                                    <div class="flex items-center justify-between flex-wrap gap-2 pt-2 border-t border-slate-50">
                                        <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">
                                            Koord: {{ number_format($lat, 6) }}, {{ number_format($lng, 6) }}
                                        </span>
                                        <a href="https://www.google.com/maps/search/?api=1&query={{ $lat }},{{ $lng }}" 
                                           target="_blank" 
                                           class="inline-flex items-center gap-1 text-[10px] font-extrabold text-emerald-700 hover:text-emerald-800 transition-colors">
                                            <span class="material-symbols-outlined text-[13px]">map</span>
                                            Buka di Google Maps
                                        </a>
                                    </div>
                                @endif
                            </div>
                        @else
                            <p class="text-[11px] text-slate-400 italic">
                                Alamat penerima belum ditentukan. Silakan perbarui di <a href="{{ route('customer.profile') }}" class="text-emerald-700 font-bold underline">Profil Anda</a>.
                            </p>
                        @endif
                    </div>

                    {{-- Sender Address --}}
                    <div class="bg-white p-4 rounded-xl border border-slate-100 shadow-sm space-y-3 relative overflow-hidden group hover:border-emerald-600/20 transition-all duration-300">
                        <!-- Top header of Sender block -->
                        <div class="flex items-center justify-between flex-wrap gap-2 pb-2 border-b border-slate-50">
                            <div class="flex items-center gap-1.5 font-extrabold text-xs text-slate-800">
                                <span class="material-symbols-outlined text-emerald-700" style="font-size:18px;">call_made</span>
                                Alamat Pengirim Ternak
                            </div>
                            <span class="badge-premium-green py-0.5 px-2 text-[9px] font-extrabold inline-flex items-center">
                                <span class="material-symbols-outlined text-[10px] mr-1">store</span>
                                Peternakan Utama
                            </span>
                        </div>

                        <div class="space-y-2">
                            <p class="text-xs text-slate-600 leading-relaxed font-medium">
                                Sidoarjo, Jawa Timur, Indonesia
                            </p>
                            <div class="flex items-center justify-between flex-wrap gap-2 pt-2 border-t border-slate-50">
                                <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">
                                    Koord: -7.452278, 112.708992
                                </span>
                                <a href="https://www.google.com/maps/search/?api=1&query=-7.452278,112.708992" 
                                   target="_blank" 
                                   class="inline-flex items-center gap-1 text-[10px] font-extrabold text-emerald-700 hover:text-emerald-800 transition-colors">
                                    <span class="material-symbols-outlined text-[13px]">map</span>
                                    Buka di Google Maps
                                </a>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        @empty
        <div class="text-center py-16 px-8 bg-white/30 backdrop-blur-md rounded-3xl border border-emerald-800/10 shadow-[0_8px_32px_rgba(5,31,32,0.02)] max-w-xl mx-auto relative overflow-hidden group transition-all duration-300 hover:border-emerald-600/20 hover:shadow-[0_12px_40px_rgba(5,31,32,0.05)]" data-aos="zoom-in">
            <!-- Glowing background effects -->
            <div class="absolute -top-10 -left-10 w-32 h-32 bg-emerald-600/5 rounded-full blur-2xl pointer-events-none"></div>
            <div class="absolute -bottom-10 -right-10 w-32 h-32 bg-emerald-600/5 rounded-full blur-2xl pointer-events-none"></div>

            <!-- Floating Icon Container -->
            <div class="w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4 bg-emerald-600/10 border border-emerald-600/20 shadow-[0_8px_16px_rgba(42,120,68,0.04)] relative transition-all duration-300 group-hover:scale-110 group-hover:bg-emerald-600/15">
                <span class="material-symbols-outlined text-3xl text-emerald-800">local_shipping</span>
            </div>
            
            <!-- Content -->
            <h3 class="font-bold text-base mb-1.5" style="color:#051F20;">Tidak Ada Pesanan Aktif</h3>
            <p class="text-xs max-w-sm mx-auto leading-relaxed" style="color:#64748B;">
                Pesanan ternak yang disetujui oleh admin akan otomatis tertera di sini untuk dipantau proses kesehatannya.
            </p>
        </div>
        @endforelse
    </div>



</main>
@endsection
