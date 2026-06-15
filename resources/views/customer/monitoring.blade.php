@extends('layouts.customer')

@section('title', 'Monitoring Pesanan')

@section('content')
<main class="max-w-[1600px] mx-auto px-6 py-10 space-y-12">

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

                // Define steps dynamically for modern rendering
                $steps = [
                    [
                        'title' => 'Pesanan Masuk',
                        'desc' => 'Berhasil didaftarkan',
                        'icon' => 'check',
                        'state' => 'completed',
                    ],
                    [
                        'title' => 'Disetujui Admin',
                        'desc' => 'Transaksi sah',
                        'icon' => 'approval',
                        'state' => 'completed',
                    ],
                    [
                        'title' => 'Karantina & Vitamin',
                        'desc' => ($status === 'Disetujui') ? 'Sedang Diproses' : 'Selesai',
                        'icon' => 'health_and_safety',
                        'state' => ($status === 'Pengiriman Kurir' || $status === 'Pesanan Sudah Sampai') ? 'completed' : 'active',
                    ],
                    [
                        'title' => 'Pengiriman Kurir',
                        'desc' => ($status === 'Pesanan Sudah Sampai') ? 'Selesai' : (($status === 'Pengiriman Kurir') ? 'Sedang Dikirim' : 'Ternak siap dikirim'),
                        'icon' => 'local_shipping',
                        'state' => ($status === 'Pesanan Sudah Sampai') ? 'completed' : (($status === 'Pengiriman Kurir') ? 'active' : 'pending'),
                    ],
                    [
                        'title' => 'Pesanan Sampai',
                        'desc' => ($status === 'Pesanan Sudah Sampai') ? 'Sudah Sampai' : 'Menunggu tiba',
                        'icon' => 'check_circle',
                        'state' => ($status === 'Pesanan Sudah Sampai') ? 'completed' : 'pending',
                    ],
                ];

                // Progress line width
                $line_width = '50%';
                if ($status === 'Pengiriman Kurir') {
                    $line_width = '75%';
                } elseif ($status === 'Pesanan Sudah Sampai') {
                    $line_width = '100%';
                }
            @endphp
        
        <style>
            .stepper-line-glow {
                box-shadow: 0 0 10px rgba(52, 211, 153, 0.6), 0 0 4px rgba(52, 211, 153, 0.4);
            }
            .stepper-circle-active {
                box-shadow: 0 0 0 4px rgba(167, 243, 208, 0.8), 0 0 15px rgba(52, 211, 153, 0.5);
            }
            .stepper-circle-pulse {
                animation: stepper-pulse-ring 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
            }
            @keyframes stepper-pulse-ring {
                0%, 100% {
                    transform: scale(1);
                    opacity: 0.8;
                }
                50% {
                    transform: scale(1.3);
                    opacity: 0.2;
                }
            }
        </style>

        <div class="glass-card p-4 md:p-6 space-y-4 shadow-[0_8px_30px_rgba(5,31,32,0.02)]" data-aos="fade-right" data-aos-delay="{{ $loop->index * 100 }}">
            
            {{-- Top Info Row --}}
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-4 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    @if($pesanan->produk && $pesanan->produk->foto)
                        <img src="{{ (function($p){ try { return $p ? Storage::disk(config('filesystems.default'))->url($p) : asset('images/placeholder.png'); } catch(\Exception $e) { return asset('images/placeholder.png'); } })($pesanan->produk->foto) }}" alt="{{ $pesanan->produk->nama_produk }}" class="w-11 h-11 rounded-xl object-cover shrink-0 border border-slate-100 shadow-sm">
                    @else
                        <div class="w-11 h-11 rounded-xl flex items-center justify-center shrink-0 shadow-sm bg-slate-50 border border-slate-100">
                            <span class="material-symbols-outlined text-slate-400" style="font-size: 20px;">pets</span>
                        </div>
                    @endif
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

            {{-- ── 2026 SaaS Milestone Progress Tracker (Modernized) ── --}}
            <div class="py-4 px-5 bg-gradient-to-r from-emerald-50/20 to-slate-50/45 rounded-2xl border border-slate-100 space-y-4">
                <div class="flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-emerald-600 text-sm">route</span>
                    <h4 class="text-xs font-bold uppercase tracking-wider text-slate-500">Tahapan Proses & Logistik</h4>
                </div>
                
                {{-- Tracker bar --}}
                <div class="grid grid-cols-1 md:grid-cols-5 gap-6 md:gap-4 pt-2 pb-2 relative">
                    
                    {{-- Connecting lines for desktop --}}
                    <div class="absolute top-[22px] left-[10%] right-[10%] h-[4px] bg-slate-100 hidden md:block z-0 rounded-full">
                        <div class="h-full bg-gradient-to-r from-emerald-500 via-green-400 to-emerald-400 stepper-line-glow transition-all duration-500 rounded-full" style="width: {{ $line_width }};"></div>
                    </div>

                    @foreach($steps as $index => $step)
                        @php
                            $state = $step['state'];
                            
                            // Class mapping based on state
                            if ($state === 'completed') {
                                $circleClass = "bg-emerald-600 text-white border-2 border-emerald-600 shadow-md";
                                $icon = "check";
                                $titleClass = "text-slate-800 font-bold";
                                $descClass = "text-emerald-600 font-semibold";
                                $hasPulse = false;
                            } elseif ($state === 'active') {
                                $circleClass = "bg-emerald-50 text-emerald-600 border-2 border-emerald-400 font-extrabold stepper-circle-active";
                                $icon = $step['icon'];
                                $titleClass = "text-emerald-800 font-extrabold";
                                $descClass = "text-emerald-500 font-extrabold animate-pulse";
                                $hasPulse = true;
                            } else { // pending
                                $circleClass = "bg-slate-50 text-slate-400 border border-slate-200";
                                $icon = $step['icon'];
                                $titleClass = "text-slate-400 font-medium";
                                $descClass = "text-slate-450";
                                $hasPulse = false;
                            }
                        @endphp
                        <div class="flex items-center md:flex-col md:text-center gap-3.5 relative z-10">
                            <div class="relative flex items-center justify-center shrink-0">
                                @if($hasPulse)
                                    <div class="absolute w-8 h-8 rounded-full bg-emerald-300/40 stepper-circle-pulse"></div>
                                @endif
                                <div class="w-8 h-8 rounded-full flex items-center justify-center {{ $circleClass }} z-10 transition-all duration-300">
                                    <span class="material-symbols-outlined text-sm font-bold">{{ $icon }}</span>
                                </div>
                            </div>
                            <div class="md:mt-1">
                                <div class="text-[11px] font-extrabold {{ $titleClass }} leading-tight">{{ $step['title'] }}</div>
                                <p class="text-[9px] leading-normal mt-0.5 {{ $descClass }}">{{ $step['desc'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Redesigned Ultra-Modern Grid --}}
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 pt-6 border-t border-slate-100">
                
                {{-- Left: Animal Profile (4 columns) --}}
                <div class="lg:col-span-4">
                    @if($pesanan->produk && $pesanan->produk->inventaris)
                    <div class="bg-white/80 p-5 rounded-3xl border border-slate-150 shadow-[0_4px_20px_rgba(0,0,0,0.02)] flex flex-col group hover:border-emerald-500/25 transition-all duration-350 h-full">
                        <div class="flex items-center gap-2 pb-3 mb-4 border-b border-slate-100">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-emerald-50 text-emerald-700">
                                <span class="material-symbols-outlined text-lg">pets</span>
                            </div>
                            <div>
                                <h4 class="text-xs font-extrabold text-slate-800">Spesifikasi Ternak</h4>
                                <p class="text-[9px] text-slate-400 uppercase tracking-wider font-semibold">Genetika & Fisik</p>
                            </div>
                        </div>
                        
                        <div class="flex flex-col gap-5">
                            @if($pesanan->produk && $pesanan->produk->foto)
                                <div class="w-full rounded-2xl overflow-hidden shadow-[0_2px_12px_rgba(0,0,0,0.03)] border border-slate-100 bg-white relative group-hover:shadow-[0_8px_24px_rgba(0,0,0,0.06)] transition-shadow duration-300">
                                    <img src="{{ (function($p){ try { return $p ? Storage::disk(config('filesystems.default'))->url($p) : asset('images/placeholder.png'); } catch(\Exception $e) { return asset('images/placeholder.png'); } })($pesanan->produk->foto) }}" alt="{{ $pesanan->produk->nama_produk }}" class="w-full h-auto block transition-transform duration-500 hover:scale-[1.03]">
                                </div>
                            @endif

                            <div class="w-full mt-auto">
                                <div class="grid grid-cols-2 gap-3 text-xs font-semibold text-slate-600">
                                    <div class="bg-slate-50/70 p-3.5 rounded-2xl border border-slate-100 text-center transition-all hover:bg-white hover:shadow-[0_2px_10px_rgba(0,0,0,0.02)] hover:-translate-y-0.5">
                                        <span class="text-[9px] text-slate-400 uppercase block leading-none mb-2 font-bold tracking-wider">Jenis</span>
                                        <strong class="text-slate-800 text-xs font-extrabold">{{ $pesanan->produk->inventaris->jenis ?? '-' }}</strong>
                                    </div>
                                    <div class="bg-slate-50/70 p-3.5 rounded-2xl border border-slate-100 text-center transition-all hover:bg-white hover:shadow-[0_2px_10px_rgba(0,0,0,0.02)] hover:-translate-y-0.5">
                                        <span class="text-[9px] text-slate-400 uppercase block leading-none mb-2 font-bold tracking-wider">Gender</span>
                                        <strong class="text-slate-800 text-xs font-extrabold">{{ $pesanan->produk->inventaris->gender ?? '-' }}</strong>
                                    </div>
                                    <div class="bg-slate-50/70 p-3.5 rounded-2xl border border-slate-100 text-center transition-all hover:bg-white hover:shadow-[0_2px_10px_rgba(0,0,0,0.02)] hover:-translate-y-0.5">
                                        <span class="text-[9px] text-slate-400 uppercase block leading-none mb-2 font-bold tracking-wider">Umur</span>
                                        <strong class="text-slate-800 text-xs font-extrabold">{{ $pesanan->produk->inventaris->umur ?? '-' }} Bln</strong>
                                    </div>
                                    <div class="bg-slate-50/70 p-3.5 rounded-2xl border border-slate-100 text-center transition-all hover:bg-white hover:shadow-[0_2px_10px_rgba(0,0,0,0.02)] hover:-translate-y-0.5">
                                        <span class="text-[9px] text-slate-400 uppercase block leading-none mb-2 font-bold tracking-wider">Berat</span>
                                        <strong class="text-slate-800 text-xs font-extrabold">{{ $pesanan->produk->inventaris->berat ?? '-' }} Kg</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                {{-- Right: Logistics & Health (8 columns) --}}
                <div class="lg:col-span-8 flex flex-col gap-6">
                    
                    {{-- Rute Pengiriman (Horizontal Timeline) --}}
                    <div class="bg-white/80 p-6 rounded-3xl border border-slate-150 shadow-[0_4px_20px_rgba(0,0,0,0.02)] group hover:border-emerald-500/25 transition-all duration-350">
                        <div class="flex items-center gap-2 pb-3 mb-6 border-b border-slate-100">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-emerald-50 text-emerald-700">
                                <span class="material-symbols-outlined text-lg">local_shipping</span>
                            </div>
                            <div>
                                <h4 class="text-xs font-extrabold text-slate-800">Rute Pengiriman</h4>
                                <p class="text-[9px] text-slate-400 uppercase tracking-wider font-semibold">Alur Logistik Ternak</p>
                            </div>
                        </div>

                        <div class="relative flex flex-col md:flex-row items-center justify-between gap-4 px-2">
                            {{-- Connecting Line (Desktop) --}}
                            <div class="hidden md:block absolute top-6 left-[20%] right-[20%] h-[2px] bg-gradient-to-r from-emerald-400 to-blue-400 opacity-40 z-0 rounded-full"></div>

                            {{-- Sender Address --}}
                            <div class="w-full md:w-[45%] relative z-10 flex flex-col items-center text-center bg-slate-50/50 md:bg-transparent p-5 md:p-0 rounded-2xl md:rounded-none border md:border-transparent border-slate-100">
                                <div class="w-12 h-12 rounded-full bg-emerald-50 border-4 border-white shadow-md flex items-center justify-center mb-3">
                                    <span class="material-symbols-outlined text-emerald-600 text-xl">storefront</span>
                                </div>
                                <span class="badge-premium-green py-0.5 px-2.5 text-[9px] font-extrabold uppercase leading-none mb-2 shadow-sm">Pusat Asal</span>
                                <h5 class="text-xs font-bold text-slate-800">Peternakan Utama Goatin</h5>
                                <p class="text-[10px] text-slate-500 mt-1 leading-relaxed">Sidoarjo, Jawa Timur</p>
                                <a href="https://www.google.com/maps/search/?api=1&query=-7.452278,112.708992" target="_blank" class="mt-2.5 inline-flex items-center justify-center gap-1 bg-white border border-emerald-100 text-emerald-700 hover:bg-emerald-50 transition-colors py-1.5 px-3 rounded-full text-[9px] font-bold shadow-sm">
                                    <span class="material-symbols-outlined text-[11px]">map</span> Buka Maps
                                </a>
                            </div>

                            {{-- Arrow Indicator --}}
                            <div class="hidden md:flex items-center justify-center relative z-10">
                                <div class="w-8 h-8 rounded-full bg-white border border-slate-200 shadow-sm flex items-center justify-center text-slate-400">
                                    <span class="material-symbols-outlined text-sm">arrow_forward</span>
                                </div>
                            </div>
                            
                            {{-- Mobile Arrow Indicator --}}
                            <div class="md:hidden flex w-full justify-center -my-3 relative z-10">
                                <div class="w-7 h-7 rounded-full bg-white shadow-sm border border-slate-200 flex items-center justify-center text-slate-400">
                                    <span class="material-symbols-outlined text-[11px]">arrow_downward</span>
                                </div>
                            </div>

                            {{-- Receiver Address --}}
                            <div class="w-full md:w-[45%] relative z-10 flex flex-col items-center text-center bg-slate-50/50 md:bg-transparent p-5 md:p-0 rounded-2xl md:rounded-none border md:border-transparent border-slate-100">
                                @php
                                    $tipeAlamat = $pesanan->tipe_alamat ?? ($pesanan->user->tipe_alamat ?? '');
                                    $lat = $pesanan->latitude ?? ($pesanan->user->latitude ?? '');
                                    $lng = $pesanan->longitude ?? ($pesanan->user->longitude ?? '');
                                @endphp
                                <div class="w-12 h-12 rounded-full bg-blue-50 border-4 border-white shadow-md flex items-center justify-center mb-3">
                                    <span class="material-symbols-outlined text-blue-600 text-xl">{{ $tipeAlamat == 'Rumah' ? 'home' : 'corporate_fare' }}</span>
                                </div>
                                <span class="badge-premium-blue py-0.5 px-2.5 text-[9px] font-extrabold uppercase leading-none mb-2 shadow-sm inline-flex items-center gap-1">
                                    {{ $tipeAlamat ?: 'TUJUAN' }}
                                </span>
                                <h5 class="text-xs font-bold text-slate-800">{{ $pesanan->user->name ?? 'Pembeli' }}</h5>
                                @if($alamat)
                                    <p class="text-[10px] text-slate-500 mt-1 leading-relaxed line-clamp-2" title="{{ $alamat }}">{{ $alamat }}</p>
                                    @if($lat && $lng)
                                        <a href="https://www.google.com/maps/search/?api=1&query={{ $lat }},{{ $lng }}" target="_blank" class="mt-2.5 inline-flex items-center justify-center gap-1 bg-white border border-blue-100 text-blue-700 hover:bg-blue-50 transition-colors py-1.5 px-3 rounded-full text-[9px] font-bold shadow-sm">
                                            <span class="material-symbols-outlined text-[11px]">map</span> Buka Maps
                                        </a>
                                    @endif
                                @else
                                    <p class="text-[10px] text-slate-450 italic mt-1">Alamat belum ditentukan.</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Riwayat Kesehatan (Modern Grid Layout) --}}
                    @if($pesanan->produk && $pesanan->produk->inventaris)
                    <div class="bg-white/80 p-6 rounded-3xl border border-slate-150 shadow-[0_4px_20px_rgba(0,0,0,0.02)] group hover:border-emerald-500/25 transition-all duration-350 flex-grow flex flex-col">
                        <div class="flex items-center gap-2 pb-3 mb-5 border-b border-slate-100">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-emerald-50 text-emerald-700">
                                <span class="material-symbols-outlined text-lg">medical_services</span>
                            </div>
                            <div>
                                <h4 class="text-xs font-extrabold text-slate-800">Riwayat Medis</h4>
                                <p class="text-[9px] text-slate-400 uppercase tracking-wider font-semibold">Rekam Kesehatan Terakhir</p>
                            </div>
                        </div>
                        
                        @php
                            $medicalRecords = $pesanan->produk->inventaris->rekamMedis()->orderBy('tanggal', 'desc')->get();
                        @endphp

                        <div class="flex-grow">
                            @if($medicalRecords->count() > 0)
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @foreach($medicalRecords as $record)
                                        <div class="bg-slate-50/80 border border-slate-100 rounded-2xl p-4 flex gap-3.5 hover:shadow-[0_4px_12px_rgba(0,0,0,0.03)] hover:bg-white hover:-translate-y-0.5 transition-all duration-300">
                                            <div class="w-1.5 h-full bg-gradient-to-b from-emerald-400 to-emerald-200 rounded-full flex-shrink-0"></div>
                                            <div class="flex-grow">
                                                <div class="flex items-center justify-between mb-2">
                                                    <span class="text-[9px] text-slate-500 font-bold uppercase tracking-wider bg-slate-200/50 px-2 py-0.5 rounded-md">{{ \Carbon\Carbon::parse($record->tanggal)->translatedFormat('d M Y') }}</span>
                                                    <span class="badge-premium-green py-0.5 px-2 text-[8px] uppercase tracking-wider font-extrabold shadow-sm">{{ $record->status }}</span>
                                                </div>
                                                <h5 class="text-[11px] font-bold text-slate-800 leading-tight mb-1">{{ $record->diagnosa }}</h5>
                                                <p class="text-[10px] text-slate-500 leading-relaxed" title="{{ $record->tindakan }}">
                                                    {{ $record->tindakan }} 
                                                </p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="flex flex-col items-center justify-center text-center py-8 h-full bg-slate-50/50 rounded-2xl border border-dashed border-slate-200">
                                    <div class="w-12 h-12 rounded-full bg-emerald-50 flex items-center justify-center mb-3">
                                        <span class="material-symbols-outlined text-emerald-500 text-2xl">health_and_safety</span>
                                    </div>
                                    <h5 class="text-xs font-bold text-slate-700">Kondisi Sehat & Bugar</h5>
                                    <p class="text-[10px] text-slate-500 font-medium mt-1">Belum ada riwayat tercatat untuk ternak ini.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                    @endif
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

<script>
    function toggleMonitoringMedical(id) {
        const el = document.getElementById('mon-timeline-' + id);
        const chevron = document.getElementById('mon-chevron-' + id);
        const isHidden = el.style.maxHeight === '0px';
        
        if (isHidden) {
            el.style.maxHeight = el.scrollHeight + 'px';
            el.style.opacity = '1';
            chevron.style.transform = 'rotate(180deg)';
        } else {
            el.style.maxHeight = '0px';
            el.style.opacity = '0';
            chevron.style.transform = 'rotate(0deg)';
        }
    }
</script>
@endsection
