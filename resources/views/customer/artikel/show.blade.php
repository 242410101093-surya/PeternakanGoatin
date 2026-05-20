@extends('layouts.customer')

@section('title', $artikel->judul)

@section('content')
<main class="flex-1 mt-[80px] max-w-container-max mx-auto w-full px-margin-mobile md:px-margin-desktop py-stack-lg">
    <div class="max-w-4xl mx-auto bg-surface-container-lowest border border-surface-variant rounded-3xl overflow-hidden shadow-[0_4px_20px_rgba(74,124,89,0.08)]">
        <div class="relative h-80 overflow-hidden">
            <img alt="{{ $artikel->judul }}" class="w-full h-full object-cover" src="{{ $artikel->foto ? asset('storage/' . $artikel->foto) : 'https://images.unsplash.com/photo-1524063220888-eb2fcbd1160a?auto=format&fit=crop&w=1200&q=80' }}"/>
            <div class="absolute inset-0 bg-gradient-to-t from-surface/80 via-surface/30 to-transparent"></div>
            <div class="absolute bottom-0 left-0 p-6 text-on-surface">
                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-primary-container/90 text-on-primary-container font-caption text-caption">{{ $artikel->kategori ?: 'Umum' }}</span>
                <h1 class="mt-4 font-h1 text-h1 text-on-primary leading-tight">{{ $artikel->judul }}</h1>
                <div class="mt-3 flex flex-wrap items-center gap-3 text-sm text-on-surface-variant font-caption">
                    <span>{{ $artikel->created_at->format('d M Y') }}</span>
                    <span class="inline-block px-2 py-1 rounded-full bg-surface/90">{{ $artikel->created_at->diffForHumans() }}</span>
                </div>
            </div>
        </div>

        <div class="p-gutter md:p-10">
            <div class="prose prose-slate max-w-none text-on-surface leading-relaxed">
                {!! nl2br(e($artikel->konten)) !!}
            </div>

            <div class="mt-10">
                <a href="{{ route('customer.dashboard') }}" class="inline-flex items-center gap-2 font-label-sm text-label-sm text-primary hover:text-primary-container transition-colors">
                    <span class="material-symbols-outlined">arrow_back</span>
                    Kembali ke Dashboard
                </a>
            </div>
        </div>
    </div>
</main>
@endsection
