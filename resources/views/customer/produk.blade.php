@extends('layouts.customer')

@section('title', 'Katalog Produk')

@section('content')
<main class="flex-grow pt-[88px] pb-stack-xl px-margin-mobile md:px-margin-desktop max-w-container-max mx-auto w-full">
    <!-- Header Section -->
    <header class="mb-stack-lg flex flex-col md:flex-row md:items-end justify-between gap-stack-md mt-stack-md">
        <div>
            <h1 class="font-h1 text-h1 text-primary mb-stack-xs">Katalog Ternak</h1>
            <p class="font-body-lg text-body-lg text-on-surface-variant">Pilih bibit kambing dan domba berkualitas tinggi untuk kebutuhan peternakan Anda.</p>
        </div>
        <!-- Filters & Search -->
        <div class="flex gap-stack-sm flex-wrap">
            <div class="relative">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline">search</span>
                <input class="pl-10 pr-4 py-2 rounded-lg bg-surface-container-lowest border border-outline-variant focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all font-body-md text-body-md text-on-surface w-full md:w-64" placeholder="Cari kambing..." type="text"/>
            </div>
            <button class="flex items-center gap-2 px-4 py-2 rounded-lg bg-surface-container-lowest border border-outline-variant hover:ambient-shadow transition-all text-on-surface font-label-sm text-label-sm">
                <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 0;">filter_list</span>
                Filter
            </button>
        </div>
    </header>

    <!-- Categories / Chips -->
    <div class="flex gap-stack-sm overflow-x-auto pb-stack-sm mb-stack-lg scrollbar-hide">
        <button class="whitespace-nowrap px-4 py-2 rounded-full bg-primary-container text-on-primary-container font-label-sm text-label-sm transition-all">Semua Jenis</button>
        <button class="whitespace-nowrap px-4 py-2 rounded-full bg-surface-container-highest text-on-surface-variant hover:bg-surface-variant font-label-sm text-label-sm transition-all border border-outline-variant">Kambing Etawa</button>
        <button class="whitespace-nowrap px-4 py-2 rounded-full bg-surface-container-highest text-on-surface-variant hover:bg-surface-variant font-label-sm text-label-sm transition-all border border-outline-variant">Kambing Kibas</button>
        <button class="whitespace-nowrap px-4 py-2 rounded-full bg-surface-container-highest text-on-surface-variant hover:bg-surface-variant font-label-sm text-label-sm transition-all border border-outline-variant">Domba</button>
    </div>

    <!-- Product Grid (Bento Style approach) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-gutter">
        @forelse ($produks as $produk)
        <div class="group bg-surface-container-lowest rounded-xl border border-surface-variant hover:ambient-shadow transition-all duration-300 overflow-hidden flex flex-col">
            <div class="h-48 bg-surface-variant relative overflow-hidden flex items-center justify-center">
                @if($produk->foto)
                    <img alt="{{ $produk->nama_produk }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" src="{{ asset('storage/' . $produk->foto) }}"/>
                @else
                    <span class="material-symbols-outlined text-4xl text-on-surface-variant">image</span>
                @endif
            </div>
            <div class="p-4 flex flex-col flex-grow">
                @if($produk->inventaris)
                    <span class="font-caption text-caption text-secondary mb-1">{{ $produk->inventaris->gender }} • {{ $produk->inventaris->umur }} Bulan</span>
                @else
                    <span class="font-caption text-caption text-secondary mb-1">Produk Umum</span>
                @endif
                <h3 class="font-body-lg text-body-lg text-on-surface font-semibold mb-2 line-clamp-2">{{ $produk->nama_produk }}</h3>
                <p class="font-body-sm text-body-sm text-on-surface-variant line-clamp-2 mb-4">{{ $produk->spesifikasi }}</p>
                <div class="mt-auto flex items-center justify-between">
                    <span class="font-h3 text-h3 text-tertiary">Rp {{ number_format($produk->harga, 0, ',', '.') }}</span>
                    <button
                        type="button"
                        aria-label="Tambah ke keranjang"
                        class="open-product-modal p-2 bg-surface-container-high hover:bg-tertiary hover:text-on-tertiary text-on-surface rounded-lg transition-colors"
                        data-product-name="{{ $produk->nama_produk }}"
                        data-product-specs="{{ $produk->spesifikasi }}"
                        data-product-price="Rp {{ number_format($produk->harga, 0, ',', '.') }}"
                        data-product-gender="{{ $produk->inventaris?->gender ?? 'N/A' }}"
                        data-product-age="{{ $produk->inventaris?->umur ? $produk->inventaris->umur . ' Bulan' : '-' }}"
                    >
                        <span class="material-symbols-outlined">add_shopping_cart</span>
                    </button>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full py-12 text-center bg-surface-container-lowest rounded-xl border border-surface-variant">
            <span class="material-symbols-outlined text-4xl text-on-surface-variant mb-2">storefront</span>
            <p class="font-body-md text-body-md text-on-surface-variant">Belum ada produk yang tersedia.</p>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="flex justify-center pt-stack-md">
        {{ $produks->links() }}
    </div>

    <!-- Product Detail Modal -->
    <div id="productModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 p-4 backdrop-blur-sm">
        <div class="w-full max-w-2xl rounded-[28px] bg-surface-container-lowest shadow-2xl overflow-hidden border border-surface-variant">
            <div class="p-6">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="font-caption text-caption text-secondary mb-2">Detail Produk & Penjelasan</p>
                        <h2 id="modalProductName" class="font-h2 text-h2 text-on-surface"></h2>
                    </div>
                    <button type="button" id="closeProductModal" class="inline-flex h-11 w-11 items-center justify-center rounded-full bg-surface-container-highest text-on-surface hover:bg-surface-variant transition-colors">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>
                <p id="modalProductSpecs" class="mt-4 font-body-md text-body-md text-on-surface-variant leading-7"></p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-6">
                    <div class="rounded-3xl bg-surface p-4 border border-outline">
                        <p class="font-caption text-caption text-on-surface-variant">Jenis Hewan</p>
                        <p id="modalProductGender" class="font-body-md text-body-md text-on-surface mt-1"></p>
                    </div>
                    <div class="rounded-3xl bg-surface p-4 border border-outline">
                        <p class="font-caption text-caption text-on-surface-variant">Umur</p>
                        <p id="modalProductAge" class="font-body-md text-body-md text-on-surface mt-1"></p>
                    </div>
                </div>
                <div class="mt-6 flex flex-col gap-3">
                    <div>
                        <p class="font-caption text-caption text-on-surface-variant">Harga</p>
                        <p id="modalProductPrice" class="font-h3 text-h3 text-tertiary mt-1"></p>
                    </div>
                    <a
                        id="modalBuyButton"
                        href="#"
                        target="_blank"
                        class="inline-flex w-full items-center justify-center rounded-full bg-primary text-on-primary px-6 py-3 font-label-sm text-label-sm font-semibold transition-colors hover:bg-primary-fixed"
                    >Beli via WhatsApp</a>
                    <button
                        type="button"
                        id="modalCancelButton"
                        class="inline-flex w-full items-center justify-center rounded-full border border-outline bg-surface text-on-surface px-6 py-3 font-label-sm text-label-sm transition-colors hover:bg-surface-variant"
                    >Kembali ke katalog</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const modal = document.getElementById('productModal');
            const openButtons = Array.from(document.querySelectorAll('.open-product-modal'));
            const closeButton = document.getElementById('closeProductModal');
            const cancelButton = document.getElementById('modalCancelButton');
            const productName = document.getElementById('modalProductName');
            const productSpecs = document.getElementById('modalProductSpecs');
            const productGender = document.getElementById('modalProductGender');
            const productAge = document.getElementById('modalProductAge');
            const productPrice = document.getElementById('modalProductPrice');
            const buyButton = document.getElementById('modalBuyButton');
            const phoneNumber = '{{ config('app.whatsapp_number') }}';

            function openModal(button) {
                productName.textContent = button.dataset.productName || 'Produk';
                productSpecs.textContent = button.dataset.productSpecs || 'Tidak ada detail tambahan untuk produk ini.';
                productGender.textContent = button.dataset.productGender || '-';
                productAge.textContent = button.dataset.productAge || '-';
                productPrice.textContent = button.dataset.productPrice || '-';

                const message = encodeURIComponent(
                    `Halo, saya ingin membeli ternak ${button.dataset.productName}.\n` +
                    `Jenis: ${button.dataset.productGender}.\n` +
                    `Umur: ${button.dataset.productAge}.\n` +
                    `Detail ternak: ${button.dataset.productSpecs}.\n` +
                    `Harga: ${button.dataset.productPrice}.\n` +
                    `Mohon bantuannya untuk proses pembelian dan konfirmasi ketersediaan.`
                );
                buyButton.href = `https://wa.me/${phoneNumber}?text=${message}`;

                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }

            function closeModal() {
                modal.classList.remove('flex');
                modal.classList.add('hidden');
            }

            openButtons.forEach(button => {
                button.addEventListener('click', function () {
                    openModal(this);
                });
            });

            closeButton.addEventListener('click', closeModal);
            cancelButton.addEventListener('click', closeModal);
            modal.addEventListener('click', function (event) {
                if (event.target === modal) {
                    closeModal();
                }
            });
        });
    </script>
</main>
@endsection
