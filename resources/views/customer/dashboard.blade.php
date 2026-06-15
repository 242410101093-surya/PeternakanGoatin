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

    {{-- ── Hero Welcome Banner Section ── --}}
    <section class="relative overflow-hidden rounded-[32px] p-8 md:p-12 lg:p-14 text-white border border-emerald-900/40 shadow-2xl"
             style="background: linear-gradient(135deg, #062b25 0%, #0c4a3d 50%, #126b56 100%);"
             data-aos="fade-down">
        
        {{-- Futuristic background glows --}}
        <div class="absolute right-0 top-0 w-[500px] h-[500px] rounded-full filter blur-[120px] opacity-40 pointer-events-none" style="background: radial-gradient(circle, #20c997 0%, transparent 70%);"></div>
        <div class="absolute left-[-10%] bottom-[-20%] w-[400px] h-[400px] rounded-full filter blur-[100px] opacity-30 pointer-events-none" style="background: radial-gradient(circle, #10b981 0%, transparent 70%);"></div>
        <div class="absolute right-[25%] bottom-[-30%] w-[350px] h-[350px] rounded-full filter blur-[120px] opacity-25 pointer-events-none" style="background: radial-gradient(circle, #34d399 0%, transparent 70%);"></div>
        
        {{-- Abstract Grid Overlay --}}
        <div class="absolute inset-0 bg-[linear-gradient(rgba(255,255,255,0.03)_1px,transparent_1px),linear-gradient(90deg,rgba(255,255,255,0.03)_1px,transparent_1px)] bg-[size:32px_32px] opacity-50 pointer-events-none"></div>

        <div class="relative z-10 grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
            <div class="lg:col-span-8 space-y-5">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-bold tracking-wider uppercase"
                     style="background: rgba(255, 255, 255, 0.1); border: 1px solid rgba(255, 255, 255, 0.2); backdrop-filter: blur(8px);">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-300 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-300"></span>
                    </span>
                    <span class="text-emerald-100">Goatin Digital Panel</span>
                </div>
                
                <h1 class="text-3xl md:text-5xl lg:text-6xl font-extrabold leading-tight tracking-tight">
                    Halo, <span class="text-transparent bg-clip-text bg-gradient-to-r from-teal-200 via-emerald-200 to-green-100">{{ auth()->user()->name }}</span>!
                </h1>
                
                <p class="text-sm md:text-base text-emerald-50/90 max-w-3xl leading-relaxed font-medium">
                    Selamat datang di dasbor utama Goatin. Di sini Anda dapat memantau kesehatan ternak secara real-time, memesan hewan kualitas super, dan membaca jurnal peternakan modern teruji.
                </p>

                <div class="flex flex-wrap gap-4 pt-4">
                    <a href="{{ route('customer.produk') }}" 
                       class="inline-flex items-center gap-2 py-3 px-7 rounded-full text-xs font-extrabold text-[#062b25] uppercase tracking-widest transition-all duration-300 bg-gradient-to-r from-emerald-300 to-emerald-400 hover:from-emerald-200 hover:to-emerald-300 shadow-lg hover:shadow-xl active:scale-95 group">
                        <span>Pesan Ternak Baru</span>
                        <span class="material-symbols-outlined text-sm transition-transform duration-200 group-hover:translate-x-0.5">storefront</span>
                    </a>
                    <a href="{{ route('customer.monitoring') }}" 
                       class="inline-flex items-center gap-2 py-3 px-7 rounded-full text-xs font-extrabold text-white border border-white/30 uppercase tracking-widest transition-all duration-300 bg-white/10 hover:bg-white/20 active:scale-95 group">
                        <span>Pantau Ternak</span>
                        <span class="material-symbols-outlined text-sm">analytics</span>
                    </a>
                </div>
            </div>

            <div class="lg:col-span-4 flex justify-center lg:justify-end">
                <div class="relative w-full max-w-[340px]">
                    <div class="absolute inset-0 bg-gradient-to-tr from-teal-300/30 to-emerald-400/20 blur-3xl rounded-[32px] opacity-70"></div>
                    
                    {{-- Premium Glass Widget Card --}}
                    <div class="relative z-10 w-full p-7 rounded-3xl border border-white/20 shadow-[0_8px_32px_0_rgba(0,0,0,0.2)] flex flex-col gap-5 text-left transition-all duration-500 hover:scale-[1.02] hover:border-white/40"
                         style="background: rgba(255, 255, 255, 0.12); backdrop-filter: blur(24px); -webkit-backdrop-filter: blur(24px);">
                        
                        <div class="flex items-center justify-between pb-3.5 border-b border-white/15">
                            <span class="text-[11px] font-bold text-emerald-100 uppercase tracking-widest">Status Akun</span>
                            <span class="flex h-2.5 w-2.5 rounded-full {{ $isEmailVerified ? 'bg-emerald-400 shadow-[0_0_10px_rgba(52,211,153,0.8)]' : 'bg-amber-400 animate-pulse shadow-[0_0_10px_rgba(251,191,36,0.8)]' }}"></span>
                        </div>

                        <div class="space-y-4">
                            <div class="flex items-center gap-3.5">
                                <div class="w-10 h-10 rounded-xl flex items-center justify-center text-emerald-100 bg-white/10 border border-white/20 shadow-inner">
                                    <span class="material-symbols-outlined" style="font-size: 20px;">badge</span>
                                </div>
                                <div>
                                    <div class="text-[10px] text-emerald-100/70 font-bold uppercase tracking-widest mb-0.5">Tipe Member</div>
                                    <div class="text-sm font-black text-white">Pembeli Mitra</div>
                                </div>
                            </div>
                            
                            <div class="flex items-center gap-3.5">
                                <div class="w-10 h-10 rounded-xl flex items-center justify-center text-teal-200 bg-teal-400/20 border border-teal-300/30 shadow-inner">
                                    <span class="material-symbols-outlined" style="font-size: 20px;">verified_user</span>
                                </div>
                                <div>
                                    <div class="text-[10px] text-emerald-100/70 font-bold uppercase tracking-widest mb-0.5">Verifikasi</div>
                                    <div class="text-sm font-black text-white">{{ $isEmailVerified ? 'Terverifikasi ✓' : 'Belum Verifikasi' }}</div>
                                </div>
                            </div>

                            <div class="flex items-center gap-3.5">
                                <div class="w-10 h-10 rounded-xl flex items-center justify-center text-amber-200 bg-amber-400/20 border border-amber-300/30 shadow-inner">
                                    <span class="material-symbols-outlined" style="font-size: 20px;">schedule</span>
                                </div>
                                <div>
                                    <div class="text-[10px] text-emerald-100/70 font-bold uppercase tracking-widest mb-0.5">Bergabung</div>
                                    <div class="text-sm font-black text-white">{{ auth()->user()->created_at->format('d M Y') }}</div>
                                </div>
                            </div>
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
                <img src="{{ $featured->foto ? asset('storage/' . $featured->foto) : asset('images/default-artikel.jpg') }}" 
                     alt="{{ $featured->judul }}" 
                     class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
                     onerror="this.src='https://images.unsplash.com/photo-1524413840807-0c3cb6fa808d?auto=format&fit=crop&w=1200&q=80'">
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
                    <img src="{{ $artikel->foto ? asset('storage/'.$artikel->foto) : asset('images/default-artikel.jpg') }}" 
                         alt="{{ $artikel->judul }}"
                         class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                         onerror="this.src='https://images.unsplash.com/photo-1484557985045-edf25e08da73?auto=format&fit=crop&w=600&q=80'">
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
