@extends('layouts.customer')

@section('title', 'Dashboard Artikel Perawatan')

@section('content')
<main class="flex-1 mt-[80px] max-w-container-max mx-auto w-full px-margin-mobile md:px-margin-desktop py-stack-lg">
    <!-- Header Section -->
    <header class="mb-stack-xl text-center max-w-3xl mx-auto">
        <h1 class="font-h1 text-h1 text-primary mb-stack-sm">Panduan Perawatan Cerdas</h1>
        <p class="font-body-lg text-body-lg text-on-surface-variant">Temukan artikel dan tips terbaru untuk menjaga kesehatan dan produktivitas ternak kambing Anda dengan metode stewardship alami.</p>
    </header>

    @php
        $featured = $artikels->first() ?? null;
        $otherArticles = $artikels->skip(1)->take(3);
    @endphp

    <!-- Featured Article -->
    <section class="grid grid-cols-1 lg:grid-cols-3 gap-gutter mb-stack-xl">
        @if($featured)
            <a href="{{ route('customer.artikel.show', $featured) }}" class="col-span-1 lg:col-span-2 block relative rounded-xl overflow-hidden group shadow-[0_4px_20px_rgba(74,124,89,0.1)] transition-all duration-300 hover:shadow-[0_8px_30px_rgba(74,124,89,0.2)] bg-surface-container-lowest border border-surface-variant h-[400px]">
                <div class="absolute inset-0 z-0">
                    <img alt="{{ $featured->judul }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" src="{{ $featured->foto ? asset('storage/' . $featured->foto) : 'https://images.unsplash.com/photo-1524063220888-eb2fcbd1160a?auto=format&fit=crop&w=800&q=80' }}"/>
                    <div class="absolute inset-0 bg-gradient-to-t from-on-secondary-fixed/90 via-on-secondary-fixed/40 to-transparent"></div>
                </div>
                <div class="absolute bottom-0 left-0 p-gutter z-10 w-full">
                    <span class="inline-block px-3 py-1 rounded-full bg-primary-container/90 text-on-primary-container font-caption text-caption mb-stack-sm backdrop-blur-sm">Artikel Unggulan</span>
                    <h2 class="font-h2 text-h2 text-on-primary mb-stack-xs">{{ $featured->judul }}</h2>
                    <p class="font-body-md text-body-md text-surface-container-low/90 max-w-2xl line-clamp-2">{{ \Illuminate\Support\Str::limit($featured->konten, 120) }}</p>
                </div>
            </a>

            <div class="col-span-1 flex flex-col gap-gutter">
                @foreach($otherArticles as $artikel)
                    <a href="{{ route('customer.artikel.show', $artikel) }}" class="flex-1 rounded-xl p-gutter bg-surface-container-lowest border border-surface-variant shadow-[0_4px_20px_rgba(74,124,89,0.05)] hover:shadow-[0_4px_20px_rgba(74,124,89,0.15)] transition-all duration-300 flex flex-col relative overflow-hidden group">
                        <div class="h-48 overflow-hidden relative">
                            <img alt="{{ $artikel->judul }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" src="{{ $artikel->foto ? asset('storage/' . $artikel->foto) : 'https://images.unsplash.com/photo-1511216113906-8f56bb201b13?auto=format&fit=crop&w=800&q=80' }}"/>
                            <div class="absolute top-3 left-3">
                                <span class="px-2 py-1 bg-surface/90 text-primary font-caption text-caption rounded-full backdrop-blur-sm border border-surface-variant">{{ $artikel->kategori ?: 'Umum' }}</span>
                            </div>
                        </div>
                        <div class="p-4 flex flex-col flex-1">
                            <h3 class="font-h3 text-h3 text-on-surface mb-stack-xs text-lg line-clamp-2">{{ $artikel->judul }}</h3>
                            <p class="font-body-md text-body-md text-on-surface-variant text-sm mb-stack-md line-clamp-3 flex-1">{{ \Illuminate\Support\Str::limit($artikel->konten, 100) }}</p>
                            <div class="flex items-center justify-between mt-auto pt-4 border-t border-surface-variant">
                                <span class="font-caption text-caption text-outline">{{ $artikel->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </a>
                @endforeach

                @if($otherArticles->isEmpty())
                    <div class="rounded-xl p-gutter bg-surface-container-lowest border border-surface-variant shadow-[0_4px_20px_rgba(74,124,89,0.05)] text-on-surface-variant">
                        Belum ada artikel lain. Silakan kunjungi bagian artikel untuk menambahkan konten baru.
                    </div>
                @endif
            </div>
        @else
            <div class="col-span-full rounded-xl overflow-hidden group shadow-[0_4px_20px_rgba(74,124,89,0.1)] transition-all duration-300 hover:shadow-[0_8px_30px_rgba(74,124,89,0.2)] bg-surface-container-lowest border border-surface-variant p-gutter">
                <div class="mb-stack-md">
                    <span class="inline-block px-3 py-1 rounded-full bg-primary-container/90 text-on-primary-container font-caption text-caption mb-stack-sm backdrop-blur-sm">Artikel Unggulan</span>
                    <h2 class="font-h2 text-h2 text-on-primary mb-stack-xs">Belum ada artikel</h2>
                    <p class="font-body-md text-body-md text-surface-container-low/90 max-w-2xl">Artikel akan muncul di sini setelah admin menambahkan konten di halaman manajemen artikel.</p>
                </div>
            </div>
        @endif
    </section>

</main>
@endsection
