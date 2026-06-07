@extends('layouts.customer')

@section('title', $artikel->judul)

@section('content')
<main class="max-w-[860px] mx-auto px-6 py-12 space-y-8 mt-4">
    
    {{-- Breadcrumb & Back button --}}
    <div class="flex items-center justify-between">
        <a href="{{ route('customer.dashboard') }}" 
           class="group inline-flex items-center gap-2 text-xs font-bold transition-all"
            style="color: #235347;"
            onmouseover="this.style.color='#051F20';" onmouseout="this.style.color='#235347';">
            <span class="material-symbols-outlined transition-transform group-hover:-translate-x-1" style="font-size: 16px;">arrow_back</span>
            Kembali ke Dashboard
        </a>
        
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-widest text-teal-700 bg-teal-50 border border-teal-100/60">
            <span class="w-1.5 h-1.5 rounded-full bg-teal-500 animate-pulse"></span>
            Edukasi Ternak
        </span>
    </div>

    {{-- Article Header Info --}}
    <div class="space-y-4 text-left">
        <span class="inline-block px-3 py-1.5 rounded-lg text-xs font-bold text-emerald-800"
              style="background: #f0faf3; border: 1px solid rgba(42, 120, 68, 0.15);">
            {{ $artikel->kategori ?: 'Artikel Umum' }}
        </span>
        
        <h1 class="text-3xl md:text-5xl font-black leading-tight tracking-tight" style="color: #051F20;">
            {{ $artikel->judul }}
        </h1>
        
        <div class="flex flex-wrap items-center gap-4 text-xs font-semibold pt-2" style="color: #64748B;">
            <div class="flex items-center gap-1.5">
                <span class="material-symbols-outlined text-teal-500" style="font-size: 16px;">calendar_today</span>
                <span>{{ $artikel->created_at->format('d M Y') }}</span>
            </div>
            <span class="w-1 h-1 rounded-full bg-slate-300"></span>
            <div class="flex items-center gap-1.5">
                <span class="material-symbols-outlined text-teal-500" style="font-size: 16px;">schedule</span>
                <span>{{ $artikel->created_at->diffForHumans() }}</span>
            </div>
            <span class="w-1 h-1 rounded-full bg-slate-300"></span>
            <div class="flex items-center gap-1.5">
                <span class="material-symbols-outlined text-teal-500" style="font-size: 16px;">menu_book</span>
                <span>3 Menit Baca</span>
            </div>
        </div>
    </div>

    {{-- Article Hero Image --}}
    <div class="relative overflow-hidden rounded-[28px] shadow-lg border border-slate-200/60 bg-slate-100" style="height: 420px;">
        <img alt="{{ $artikel->judul }}" 
             class="w-full h-full object-cover transition-transform duration-700 hover:scale-[1.02]" 
             src="{{ $artikel->foto ? asset('storage/' . $artikel->foto) : asset('images/default-artikel.jpg') }}"
             onerror="this.src='https://images.unsplash.com/photo-1524413840807-0c3cb6fa808d?auto=format&fit=crop&w=1200&q=80'"/>
    </div>

    {{-- Article Content Card --}}
    <div class="glass-card p-6 md:p-10 text-left space-y-6" style="background: rgba(255, 255, 255, 0.95); border-radius: 28px;">
        <div class="prose prose-slate max-w-none text-sm md:text-base leading-relaxed text-slate-800" style="font-family: inherit;">
            <p class="first-letter:text-5xl first-letter:font-black first-letter:text-teal-700 first-letter:mr-2 first-letter:float-left">
                {!! nl2br(e($artikel->konten)) !!}
            </p>
        </div>
    </div>

    {{-- Related/Back Bottom --}}
    <div class="flex justify-center pt-4">
        <a href="{{ route('customer.dashboard') }}" 
           class="inline-flex items-center gap-2 px-6 py-3 rounded-xl font-bold text-sm shadow-md transition-all border border-slate-200/80"
           style="background: #ffffff; color: #051F20;"
           onmouseover="this.style.background='#f8fafc'; this.style.borderColor='#cbd5e1'; this.style.transform='translateY(-1px)';"
           onmouseout="this.style.background='#ffffff'; this.style.borderColor='rgba(226,232,240,0.8)'; this.style.transform='none';">
            <span class="material-symbols-outlined" style="font-size: 18px;">grid_view</span>
            Jelajahi Artikel Lainnya
        </a>
    </div>

</main>
@endsection
