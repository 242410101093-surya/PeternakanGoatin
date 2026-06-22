@extends('layouts.customer')

@section('title', 'Dashboard Artikel')

@section('content')
@php
    $featured = $artikels->first();
    $otherArticles = $artikels->skip(1);
    

    // 4. Security Status
    $isEmailVerified = !empty(auth()->user()->email_verified_at);
@endphp

<main class="max-w-[1600px] mx-auto px-6 py-10 space-y-12">

    {{-- ── Hero Banner Section ── --}}
    <section class="relative overflow-hidden rounded-[32px] p-8 md:p-12 lg:p-14 text-white border border-slate-700/30 shadow-2xl"
             style="background: linear-gradient(135deg, #051F20 0%, #0B2B26 45%, #163832 85%, #235347 100%);"
             data-aos="fade-down">
        
        {{-- Futuristic background glows --}}
        <div class="absolute right-0 top-0 w-[450px] h-[450px] rounded-full filter blur-[100px] opacity-35 pointer-events-none" style="background: radial-gradient(circle, #235347 0%, transparent 70%);"></div>
        <div class="absolute left-[-10%] bottom-[-20%] w-[350px] h-[350px] rounded-full filter blur-[80px] opacity-25 pointer-events-none" style="background: radial-gradient(circle, #2A7844 0%, transparent 70%);"></div>
        <div class="absolute right-[20%] bottom-[-30%] w-[300px] h-[300px] rounded-full filter blur-[100px] opacity-20 pointer-events-none" style="background: radial-gradient(circle, #8EB69B 0%, transparent 70%);"></div>
        
        {{-- Abstract Grid Overlay --}}
        <div class="absolute inset-0 bg-[linear-gradient(rgba(255,255,255,0.02)_1px,transparent_1px),linear-gradient(90deg,rgba(255,255,255,0.02)_1px,transparent_1px)] bg-[size:32px_32px] opacity-30 pointer-events-none"></div>

        <div class="relative z-10 grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
            <div class="lg:col-span-8 space-y-4">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-bold tracking-wider uppercase animate-pulse"
                     style="background: rgba(35, 83, 71, 0.15); border: 1px solid rgba(35, 83, 71, 0.3); backdrop-filter: blur(8px);">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#8EB69B] opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-[#8EB69B]"></span>
                    </span>
                    <span class="text-[#8EB69B]">Wawasan & Riset Peternakan</span>
                </div>
                
                <h1 class="text-3xl md:text-5xl lg:text-6xl font-extrabold leading-tight tracking-tight">
                    Temukan Pengetahuan <br class="hidden md:inline">
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-teal-300 via-emerald-300 to-green-200">
                        Ternak Modern
                    </span> Anda
                </h1>
                
                <p class="text-sm md:text-base text-slate-200/90 max-w-xl leading-relaxed font-medium">
                    Akses wawasan, riset mutakhir, dan panduan praktis yang didesain khusus untuk mengoptimalkan kesehatan, pertumbuhan, serta profitabilitas peternakan kambing & domba Anda.
                </p>

                <div class="flex flex-wrap gap-4 pt-2">
                    <div class="flex items-center gap-3 px-4 py-2.5 rounded-xl border border-white/10 transition-all hover:bg-white/10" style="background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(4px);">
                        <span class="material-symbols-outlined text-[#8EB69B]" style="font-size: 20px;">science</span>
                        <div class="text-left">
                            <p class="text-[10px] text-slate-300 font-medium">Riset Ilmiah</p>
                            <p class="text-xs font-bold text-white">Teruji & Praktis</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 px-4 py-2.5 rounded-xl border border-white/10 transition-all hover:bg-white/10" style="background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(4px);">
                        <span class="material-symbols-outlined text-emerald-400" style="font-size: 20px;">clinical_notes</span>
                        <div class="text-left">
                            <p class="text-[10px] text-slate-300 font-medium">Panduan Medis</p>
                            <p class="text-xs font-bold text-white">Kambing & Domba</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-4 flex justify-center lg:justify-end">
                <!-- Outer soft glowing box -->
                <div class="relative w-full max-w-[280px]">
                    <div class="absolute inset-0 bg-gradient-to-tr from-teal-400/20 to-transparent blur-2xl rounded-3xl opacity-60"></div>
                    
                    <!-- Premium Glass Widget Card -->
                    <div class="relative z-10 w-full p-6 rounded-2xl border border-white/15 shadow-2xl flex flex-col gap-4 text-left transition-all duration-500 hover:scale-[1.02] hover:border-white/25"
                         style="background: rgba(255, 255, 255, 0.07); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);">
                        
                        <div class="flex items-center justify-between pb-3 border-b border-white/10">
                            <span class="text-[10px] font-bold text-[#8EB69B] uppercase tracking-widest">Goatin Intelligence</span>
                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        </div>

                        <div class="space-y-4">
                            <!-- Item 1 -->
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center text-[#8EB69B] bg-[#8EB69B]/10 border border-[#8EB69B]/20">
                                    <span class="material-symbols-outlined" style="font-size: 16px;">library_books</span>
                                </div>
                                <div>
                                    <div class="text-[10px] text-slate-300">Wawasan Tersedia</div>
                                    <div class="text-xs font-bold text-white">Riset & Tips</div>
                                </div>
                            </div>
                            
                            <!-- Item 2 -->
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center text-emerald-300 bg-emerald-500/10 border border-emerald-500/20">
                                    <span class="material-symbols-outlined" style="font-size: 16px;">verified</span>
                                </div>
                                <div>
                                    <div class="text-[10px] text-slate-300">Validasi Wawasan</div>
                                    <div class="text-xs font-bold text-white">Diulas Dokter Hewan</div>
                                </div>
                            </div>

                            <!-- Item 3 -->
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center text-[#8EB69B] bg-[#8EB69B]/10 border border-[#8EB69B]/20">
                                    <span class="material-symbols-outlined" style="font-size: 16px;">trending_up</span>
                                </div>
                                <div>
                                    <div class="text-[10px] text-slate-300">Fokus Utama</div>
                                    <div class="text-xs font-bold text-white">Optimasi Profit</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="pt-2 text-center border-t border-white/5">
                            <span class="text-[9px] text-slate-400 font-medium">Diperbarui secara berkala</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>



    {{-- ── Featured Article Section ── --}}
    @if($featured)
    <section class="space-y-6" data-aos="fade-up">
        <div class="flex items-center gap-2">
            <div class="w-1.5 h-5 rounded-full" style="background:#2A7844;"></div>
            <h2 class="text-xs font-bold uppercase tracking-wider" style="color:#64748B;">Sorotan Utama Hari Ini</h2>
        </div>

        <a href="{{ route('customer.artikel.show', $featured) }}" 
           class="group block relative rounded-[20px] overflow-hidden shadow-md transition-all duration-300"
           style="height: 380px; border: 1px solid rgba(226, 232, 240, 0.8);"
           onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 12px 32px rgba(5, 31, 32, 0.08)';"
           onmouseout="this.style.transform='none'; this.style.boxShadow='0 4px 6px rgba(0,0,0,0.02)';"
           id="featured-article-card">
            
            {{-- Background Featured Image --}}
            <div class="absolute inset-0 w-full h-full">
                @if(config('app.env') === 'production')
                    <img src="{{ $featured->foto ? env('SUPABASE_URL') . '/storage/v1/object/public/' . env('SUPABASE_BUCKET') . '/' . $featured->foto . '?render=image' : asset('images/default-artikel.jpg') }}" 
                          alt="{{ $featured->judul }}" 
                          class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
                          onerror="this.onerror=null; this.src='{{ asset('images/default-artikel.jpg') }}';">
                @else
                    <img src="{{ $featured->foto ? asset('storage/' . $featured->foto) : asset('images/default-artikel.jpg') }}" 
                          alt="{{ $featured->judul }}" 
                          class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
                          onerror="this.onerror=null; this.src='{{ asset('images/default-artikel.jpg') }}';">
                @endif
                {{-- Gradient Overlay --}}
                <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/45 to-transparent"></div>
            </div>

            {{-- Text Info (Bottom) --}}
            <div class="absolute bottom-0 left-0 right-0 p-6 md:p-8 space-y-3 z-10">
                <span class="px-2.5 py-1 rounded-md text-[10px] font-bold text-white uppercase tracking-wider"
                      style="background:#2A7844; border: 1px solid rgba(255,255,255,0.15);">
                    {{ $featured->kategori ?: 'Artikel Unggulan' }}
                </span>
                <h3 class="text-xl md:text-3xl font-bold text-white leading-snug group-hover:text-emerald-300 transition-colors">
                    {{ $featured->judul }}
                </h3>
                <p class="text-xs md:text-sm line-clamp-2 max-w-3xl" style="color: rgba(255, 255, 255, 0.7);">
                    {{ \Illuminate\Support\Str::limit($featured->konten, 180) }}
                </p>
                <div class="flex items-center gap-4 pt-2">
                    <span class="flex items-center gap-1.5 text-xs font-bold text-white">
                        Baca Artikel
                        <span class="material-symbols-outlined transition-transform group-hover:translate-x-1" style="font-size:16px;">arrow_forward</span>
                    </span>
                    <span class="text-[11px]" style="color: rgba(255, 255, 255, 0.45);">
                        {{ $featured->created_at->diffForHumans() }}
                    </span>
                </div>
            </div>
        </a>
    </section>
    @endif

    {{-- ── Other Articles Grid ── --}}
    @if($otherArticles->isNotEmpty())
    <section class="space-y-6" data-aos="fade-up" data-aos-delay="100">
        <div class="flex items-center gap-2">
            <div class="w-1.5 h-5 rounded-full" style="background:#2A7844;"></div>
            <h2 class="text-xs font-bold uppercase tracking-wider" style="color:#64748B;">Artikel Perawatan Lainnya</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($otherArticles as $artikel)
            <a href="{{ route('customer.artikel.show', $artikel) }}"
               class="group flex flex-col glass-card overflow-hidden h-full rounded-2xl border border-white/60 hover-lift hover-glow shadow-sm"
               style="background: rgba(255,255,255,0.75);">

                {{-- Image Box --}}
                <div class="relative overflow-hidden h-48 bg-slate-100 shrink-0">
                    @if(config('app.env') === 'production')
                        <img src="{{ $artikel->foto ? env('SUPABASE_URL') . '/storage/v1/object/public/' . env('SUPABASE_BUCKET') . '/' . $artikel->foto . '?render=image' : asset('images/default-artikel.jpg') }}" 
                             alt="{{ $artikel->judul }}"
                             class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                             onerror="this.onerror=null; this.src='{{ asset('images/default-artikel.jpg') }}';">
                    @else
                        <img src="{{ $artikel->foto ? asset('storage/' . $artikel->foto) : asset('images/default-artikel.jpg') }}" 
                             alt="{{ $artikel->judul }}"
                             class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                             onerror="this.onerror=null; this.src='{{ asset('images/default-artikel.jpg') }}';">
                    @endif
                    <div class="absolute top-3 left-3 z-10">
                        <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold shadow-sm"
                              style="background:rgba(255, 255, 255, 0.92); color:#2A7844; backdrop-filter:blur(4px); border:1px solid rgba(42, 120, 68, 0.15);">
                            {{ $artikel->kategori ?: 'Kambing' }}
                        </span>
                    </div>
                </div>

                {{-- Info Box --}}
                <div class="p-5 flex flex-col flex-grow space-y-2">
                    <span class="text-[10px] font-semibold" style="color:#94A3B8;">
                        Dipublikasikan {{ $artikel->created_at->diffForHumans() }}
                    </span>
                    <h3 class="font-bold text-base leading-snug group-hover:text-emerald-700 transition-colors line-clamp-2" style="color:#051F20;">
                        {{ $artikel->judul }}
                    </h3>
                    <p class="text-xs leading-relaxed line-clamp-3" style="color:#64748B;">
                        {{ \Illuminate\Support\Str::limit($artikel->konten, 130) }}
                    </p>
                    
                    {{-- Footer Inside --}}
                    <div class="pt-4 mt-auto flex items-center justify-between border-t border-slate-100">
                        <span class="text-xs font-bold transition-colors inline-flex items-center gap-1" style="color:#2A7844;">
                            Baca Selengkapnya
                            <span class="material-symbols-outlined transition-transform group-hover:translate-x-1" style="font-size:14px;">arrow_right_alt</span>
                        </span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </section>
    @endif

    {{-- ── Empty State ── --}}
    @if(!$featured && $otherArticles->isEmpty())
    <section class="text-center py-16 px-8 bg-white/30 backdrop-blur-md rounded-3xl border border-emerald-800/10 shadow-[0_8px_32px_rgba(5,31,32,0.02)] max-w-xl mx-auto relative overflow-hidden group transition-all duration-300 hover:border-emerald-600/20 hover:shadow-[0_12px_40px_rgba(5,31,32,0.05)]" data-aos="zoom-in">
        {{-- Glowing background effects --}}
        <div class="absolute -top-10 -left-10 w-32 h-32 bg-emerald-600/5 rounded-full blur-2xl pointer-events-none"></div>
        <div class="absolute -bottom-10 -right-10 w-32 h-32 bg-emerald-600/5 rounded-full blur-2xl pointer-events-none"></div>

        {{-- Floating Icon Container --}}
        <div class="w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4 bg-emerald-600/10 border border-emerald-600/20 shadow-[0_8px_16px_rgba(42,120,68,0.04)] relative transition-all duration-300 group-hover:scale-110 group-hover:bg-emerald-600/15">
            <span class="material-symbols-outlined text-3xl text-emerald-800">article</span>
        </div>
        
        {{-- Typography --}}
        <h3 class="font-bold text-base mb-1.5" style="color:#051F20;">Belum Ada Panduan Perawatan</h3>
        <p class="text-xs max-w-sm mx-auto leading-relaxed" style="color:#64748B;">
            Artikel dan tips kesehatan ternak akan segera diunggah oleh admin kami. Kembali lagi nanti untuk informasi terbaru.
        </p>
    </section>
    @endif

</main>
@endsection
