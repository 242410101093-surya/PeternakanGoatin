@extends('layouts.customer')

@section('title', 'Katalog Produk')

@section('content')
<main class="max-w-[1200px] mx-auto px-6 py-8 space-y-10">

    {{-- ── Page Header ── --}}
    <header class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6 pb-6 border-b border-slate-100" data-aos="fade-down">
        <div>
            <div class="flex items-center gap-2 mb-1.5">
                <span class="w-2.5 h-2.5 rounded-full" style="background:#2A7844;"></span>
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Etalase Ternak Pilihan</span>
            </div>
            <h1 class="text-3xl font-extrabold tracking-tight" style="color:#051F20; letter-spacing:-0.02em;">Katalog Ternak Unggulan</h1>
            <p class="text-sm mt-1" style="color:#64748B;">Temukan bibit kambing & domba unggul dengan kualitas genetik prima dan rekam medis terjamin.</p>
        </div>

        {{-- Filter trigger button --}}
        <button type="button" onclick="toggleFilterPanel()"
                class="btn-premium-secondary shrink-0 text-xs flex items-center gap-2">
            <span class="material-symbols-outlined" style="font-size:16px;">tune</span>
            Penyaringan Ternak
        </button>
    </header>

    {{-- ── Elegant Sliding Filter Drawer ── --}}
    <div id="filterPanel" class="hidden transition-all duration-300 ease-in-out p-6 rounded-2xl border"
         style="background:rgba(255, 255, 255, 0.9); border-color: rgba(226, 232, 240, 0.8); shadow: 0 10px 30px rgba(5, 31, 32, 0.04);">
        <div class="flex items-center justify-between mb-5">
            <h2 class="text-sm font-bold uppercase tracking-wider flex items-center gap-2" style="color:#051F20;">
                <span class="material-symbols-outlined text-emerald-600" style="font-size:18px;">manage_search</span>
                Sesuaikan Kriteria Ternak
            </h2>
            <button onclick="toggleFilterPanel()" class="text-slate-400 hover:text-slate-600 transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        <form method="GET" action="{{ route('customer.produk') }}" class="space-y-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-5">
                {{-- Jenis Ternak --}}
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Jenis Ternak</label>
                    <select name="jenis" class="premium-input text-xs font-medium">
                        <option value="">Semua Jenis</option>
                        <option value="Domba" {{ request('jenis') == 'Domba' ? 'selected' : '' }}>Domba</option>
                        <option value="Kambing Etawa" {{ request('jenis') == 'Kambing Etawa' ? 'selected' : '' }}>Kambing Etawa</option>
                        <option value="Kambing Gibas" {{ request('jenis') == 'Kambing Gibas' ? 'selected' : '' }}>Kambing Gibas</option>
                    </select>
                </div>
                {{-- Umur Min --}}
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Umur Min (Bulan)</label>
                    <input type="number" name="min_umur" placeholder="Contoh: 3" class="premium-input text-xs" value="{{ request('min_umur') }}">
                </div>
                {{-- Umur Max --}}
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Umur Max (Bulan)</label>
                    <input type="number" name="max_umur" placeholder="Contoh: 24" class="premium-input text-xs" value="{{ request('max_umur') }}">
                </div>
                {{-- Berat Min --}}
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Berat Min (Kg)</label>
                    <input type="number" step="0.1" name="min_berat" placeholder="Contoh: 15" class="premium-input text-xs" value="{{ request('min_berat') }}">
                </div>
                {{-- Berat Max --}}
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Berat Max (Kg)</label>
                    <input type="number" step="0.1" name="max_berat" placeholder="Contoh: 60" class="premium-input text-xs" value="{{ request('max_berat') }}">
                </div>
            </div>

            <div class="flex justify-end items-center gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('customer.produk') }}" class="text-xs font-bold text-slate-500 hover:text-slate-800 transition-colors">
                    Atur Ulang Filter
                </a>
                <button type="submit" class="btn-premium text-xs py-2.5 px-6">
                    <span class="material-symbols-outlined" style="font-size:16px;">search</span>
                    Terapkan Filter
                </button>
            </div>
        </form>
    </div>

    {{-- ── Products Card Grid ── --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($produks as $produk)
        <div class="group flex flex-col glass-card overflow-hidden" data-aos="fade-up" data-aos-delay="{{ $loop->index * 50 }}">
            
            {{-- Header Image / Stylized Vector illustration if null --}}
            <div class="h-52 relative overflow-hidden shrink-0 flex items-center justify-center bg-gradient-to-br"
                 style="background: linear-gradient(135deg, #051F20 0%, #0B2B26 100%);">
                
                @if($produk->foto)
                    <img src="{{ asset('storage/' . $produk->foto) }}" alt="{{ $produk->nama_produk }}"
                         class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                @else
                    {{-- Luxurious Stylized Vector representation of livestock --}}
                    <div class="absolute inset-0 flex flex-col items-center justify-center p-4">
                        <div class="absolute -right-8 -top-8 w-24 h-24 rounded-full filter blur-xl opacity-20" style="background:#2A7844;"></div>
                        <div class="w-16 h-16 rounded-full flex items-center justify-center mb-2" style="background:rgba(255,255,255,0.06); border:1px solid rgba(255,255,255,0.12);">
                            <span class="material-symbols-outlined text-white text-3xl font-light">pets</span>
                        </div>
                        <span class="text-[10px] font-bold tracking-widest text-emerald-400 uppercase">Goatin Prime</span>
                    </div>
                @endif

                {{-- Status Tags (Hover trigger) --}}
                @if($produk->inventaris)
                <div class="absolute top-3 left-3 z-10">
                    <span class="px-2.5 py-1 rounded-lg text-[10px] font-extrabold shadow-sm flex items-center gap-1"
                          style="background:rgba(255, 255, 255, 0.95); color:#2A7844; border:1px solid rgba(42, 120, 68, 0.15);">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        {{ $produk->inventaris->gender }}
                    </span>
                </div>
                @endif
            </div>

            {{-- Body Info --}}
            <div class="p-5 flex flex-col flex-grow space-y-3">
                <h3 class="font-bold text-base leading-snug group-hover:text-emerald-700 transition-colors line-clamp-2" style="color:#051F20;">
                    {{ $produk->nama_produk }}
                </h3>
                <p class="text-xs leading-relaxed line-clamp-2" style="color:#64748B;">
                    {{ $produk->spesifikasi }}
                </p>

                {{-- Bottom Metadata, Price & Action --}}
                <div class="mt-auto pt-4 flex flex-col">
                    @if($produk->inventaris)
                    <div class="text-[12px] font-medium mb-3 text-slate-500">
                        Berat: {{ $produk->inventaris->berat }} kg | Umur: {{ $produk->inventaris->umur }} bulan
                    </div>
                    @else
                    <div class="text-[12px] font-medium mb-3 text-slate-400">
                        Produk Umum
                    </div>
                    @endif

                    <div class="pt-4 flex items-center justify-between border-t border-slate-100">
                        <div class="flex flex-col">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Harga Ternak</span>
                            <span class="text-base font-extrabold" style="color:#2A7844;">
                                Rp {{ number_format($produk->harga, 0, ',', '.') }}
                            </span>
                        </div>

                        <button type="button"
                                class="open-product-modal w-9 h-9 rounded-xl flex items-center justify-center transition-all shadow-md shrink-0 cursor-pointer"
                                style="background:linear-gradient(135deg,#2A7844 0%,#1e5c33 100%);color:#fff;"
                                onmouseover="this.style.boxShadow='0 4px 12px rgba(42,120,68,0.3)';"
                                onmouseout="this.style.boxShadow='0 2px 6px rgba(0,0,0,0.02)';"
                                data-product-id="{{ $produk->id }}"
                                data-product-name="{{ $produk->nama_produk }}"
                                data-product-specs="{{ $produk->spesifikasi }}"
                                data-product-price="Rp {{ number_format($produk->harga, 0, ',', '.') }}"
                                data-product-gender="{{ $produk->inventaris?->jenis ?? '-' }}"
                                data-product-age="{{ $produk->inventaris?->umur ? $produk->inventaris->umur . ' Bulan' : '-' }}"
                                data-product-berat="{{ $produk->inventaris?->berat ? $produk->inventaris->berat . ' Kg' : '-' }}"
                                data-product-image="{{ $produk->foto ? asset('storage/' . $produk->foto) : '' }}"
                                data-product-rekam-medis="{{ json_encode($produk->inventaris?->rekamMedis ?? []) }}">
                            <span class="material-symbols-outlined" style="font-size:18px;">shopping_basket</span>
                        </button>
                    </div>
                </div>
            </div>

        </div>
        @empty
        <div class="col-span-full py-16 px-8 text-center bg-white/30 backdrop-blur-md rounded-3xl border border-emerald-800/10 shadow-[0_8px_32px_rgba(5,31,32,0.02)] max-w-md mx-auto relative overflow-hidden group transition-all duration-300 hover:border-emerald-600/20 hover:shadow-[0_12px_40px_rgba(5,31,32,0.05)]" data-aos="zoom-in">
            <!-- Glowing background effects -->
            <div class="absolute -top-10 -left-10 w-24 h-24 bg-emerald-600/5 rounded-full blur-2xl pointer-events-none"></div>
            <div class="absolute -bottom-10 -right-10 w-24 h-24 bg-emerald-600/5 rounded-full blur-2xl pointer-events-none"></div>

            <!-- Floating Icon Container -->
            <div class="w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4 bg-emerald-600/10 border border-emerald-600/20 shadow-[0_8px_16px_rgba(42,120,68,0.04)] relative transition-all duration-300 group-hover:scale-110 group-hover:bg-emerald-600/15">
                <span class="material-symbols-outlined text-3xl text-emerald-800">storefront</span>
            </div>
            
            <!-- Content -->
            <h3 class="font-bold text-base mb-1.5" style="color:#051F20;">Belum Ada Bibit Ternak</h3>
            <p class="text-xs max-w-xs mx-auto leading-relaxed" style="color:#64748B;">
                Bibit ternak berkualitas tinggi akan segera tersedia untuk dibeli. Silakan periksa kembali nanti.
            </p>
        </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="flex justify-center pt-6">
        {{ $produks->links() }}
    </div>

    {{-- ── PREMIUM MODAL: Detail Ternak & Rekam Medis ── --}}
    <div id="productModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4"
         style="background-color: rgba(0,0,0,0.45); backdrop-filter: blur(8px);">
        <div class="w-full max-w-3xl rounded-[28px] bg-white shadow-2xl overflow-hidden border border-slate-100 max-h-[88vh] flex flex-col relative">
            
            {{-- Top accent bar --}}
            <div class="absolute top-0 left-0 right-0 h-1" style="background: linear-gradient(90deg, #2A7844 0%, #8EB69B 50%, #2A7844 100%);"></div>

            {{-- Modal Header --}}
            <div class="p-6 pt-7 border-b border-slate-100 flex items-start justify-between gap-4 sticky top-0 bg-white z-10">
                <div>
                    <span class="text-[10px] font-bold text-emerald-600 uppercase tracking-widest">Detail Spesifikasi Ternak</span>
                    <h2 id="modalProductName" class="text-xl font-extrabold text-slate-800 leading-tight"></h2>
                </div>
                <button type="button" id="closeProductModal" class="p-1.5 rounded-xl hover:bg-slate-100 transition-colors shrink-0">
                    <span class="material-symbols-outlined" style="color:#94A3B8; font-size:20px;">close</span>
                </button>
            </div>

            {{-- Modal Content (Scrollable) --}}
            <div class="p-6 overflow-y-auto space-y-6 flex-1">
                {{-- Product Image Container --}}
                <div id="modalProductImageContainer" class="w-full rounded-2xl overflow-hidden bg-slate-50 border border-slate-200/60 relative shrink-0 shadow-sm flex items-center justify-center">
                    <!-- Main image containing the full product image -->
                    <img id="modalProductImage" src="" alt="Foto Kambing" class="w-full h-auto max-h-[450px] object-cover">
                    
                    {{-- Gender Badge overlay --}}
                    <div id="modalProductImageBadge" class="absolute top-4 left-4 z-20">
                        <span class="px-3 py-1.5 rounded-full text-xs font-bold shadow-md flex items-center gap-1.5 bg-white text-emerald-800">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            <span id="modalProductImageBadgeText">Jantan</span>
                        </span>
                    </div>
                </div>

                <div>
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Penjelasan Produk</h3>
                    <p id="modalProductSpecs" class="text-sm text-slate-600 leading-relaxed font-medium bg-slate-50 p-4 rounded-xl"></p>
                </div>

                {{-- Specs Matrix --}}
                <div class="grid grid-cols-3 gap-4">
                    <div class="rounded-xl p-3 border border-slate-100 bg-slate-50/50 flex flex-col justify-center">
                        <span class="text-[10px] font-bold text-slate-400 uppercase">Jenis Ternak</span>
                        <p id="modalProductGender" class="text-sm font-extrabold text-slate-800 mt-0.5"></p>
                    </div>
                    <div class="rounded-xl p-3 border border-slate-100 bg-slate-50/50 flex flex-col justify-center">
                        <span class="text-[10px] font-bold text-slate-400 uppercase">Estimasi Umur</span>
                        <p id="modalProductAge" class="text-sm font-extrabold text-slate-800 mt-0.5"></p>
                    </div>
                    <div class="rounded-xl p-3 border border-slate-100 bg-slate-50/50 flex flex-col justify-center">
                        <span class="text-[10px] font-bold text-slate-400 uppercase">Berat Tubuh</span>
                        <p id="modalProductBerat" class="text-sm font-extrabold text-slate-800 mt-0.5"></p>
                    </div>
                </div>

                {{-- Medical History Timeline --}}
                <div class="border border-slate-100 rounded-xl overflow-hidden shadow-sm">
                    <div class="bg-slate-50 px-4 py-3 border-b border-slate-100 flex items-center gap-2">
                        <span class="material-symbols-outlined text-emerald-600 text-[20px]">medical_services</span>
                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-800">Timeline Riwayat Kesehatan</h4>
                    </div>
                    <div id="modalRekamMedisContent" class="p-4">
                        {{-- Populated dynamically via JS --}}
                    </div>
                </div>
            </div>

            {{-- Modal Footer --}}
            <div class="p-6 border-t border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="text-center sm:text-left shrink-0">
                    <span class="text-[10px] font-bold text-slate-400 uppercase block leading-none">Harga Ternak</span>
                    <span id="modalProductPrice" class="text-xl font-black text-emerald-700 leading-none mt-1 inline-block"></span>
                </div>

                <div class="flex items-center gap-3 w-full sm:w-auto">
                    <button type="button" id="modalCancelButton" class="w-full sm:w-auto text-xs font-bold px-5 py-3 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-100 transition-colors">
                        Kembali
                    </button>
                    <a id="modalBuyButton" href="#" target="_blank" class="w-full sm:w-auto btn-premium text-xs text-center justify-center shrink-0">
                        <span class="material-symbols-outlined" style="font-size:16px;">chat</span>
                        Beli via WhatsApp
                    </a>
                </div>
            </div>

        </div>
    </div>

</main>

<script>
    function toggleFilterPanel() {
        const panel = document.getElementById('filterPanel');
        panel.classList.toggle('hidden');
    }

    document.addEventListener('DOMContentLoaded', function () {
        const modal = document.getElementById('productModal');
        const openButtons = Array.from(document.querySelectorAll('.open-product-modal'));
        const closeButton = document.getElementById('closeProductModal');
        const cancelButton = document.getElementById('modalCancelButton');
        const productName = document.getElementById('modalProductName');
        const productImage = document.getElementById('modalProductImage');
        const productImageBadge = document.getElementById('modalProductImageBadge');
        const productImageBadgeText = document.getElementById('modalProductImageBadgeText');
        const productSpecs = document.getElementById('modalProductSpecs');
        const productGender = document.getElementById('modalProductGender');
        const productAge = document.getElementById('modalProductAge');
        const productBerat = document.getElementById('modalProductBerat');
        const productPrice = document.getElementById('modalProductPrice');
        const rekamMedisContent = document.getElementById('modalRekamMedisContent');
        const buyButton = document.getElementById('modalBuyButton');
        const phoneNumber = '{{ config('app.whatsapp_number') }}';
        let activeProductId = null;

        function formatDate(dateStr) {
            if (!dateStr) return '-';
            const d = new Date(dateStr);
            const months = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agt','Sep','Okt','Nov','Des'];
            return `${String(d.getDate()).padStart(2,'0')} ${months[d.getMonth()]} ${d.getFullYear()}`;
        }

        function getStatusBadge(status) {
            const s = (status || '').toLowerCase();
            if (s === 'sehat') {
                return `<span class="badge-premium-green text-[10px] py-0.5 px-2.5">Sehat</span>`;
            }
            if (s.includes('pemulihan')) {
                return `<span class="badge-premium-amber text-[10px] py-0.5 px-2.5">Masa Pemulihan</span>`;
            }
            if (s === 'sakit') {
                return `<span class="badge-premium-red text-[10px] py-0.5 px-2.5">Sakit</span>`;
            }
            return `<span class="badge-premium-blue text-[10px] py-0.5 px-2.5">${status || '-'}</span>`;
        }

        function renderRekamMedis(data) {
            if (!data || data.length === 0) {
                return `<div class="text-center py-6">
                    <span class="material-symbols-outlined text-3xl text-slate-300 mb-2">medical_services</span>
                    <p class="text-xs text-slate-400">Belum ada riwayat medis tercatat.</p>
                </div>`;
            }

            const sorted = [...data].sort((a, b) => new Date(b.tanggal) - new Date(a.tanggal));

            let html = `<div class="overflow-x-auto rounded-lg border border-slate-100"><table class="w-full text-left text-xs border-collapse">
                <thead><tr style="background: linear-gradient(135deg, #051F20 0%, #0B2B26 100%);">
                    <th class="px-4 py-3 font-bold text-slate-200 uppercase text-[10px] tracking-wider">Tanggal</th>
                    <th class="px-4 py-3 font-bold text-slate-200 uppercase text-[10px] tracking-wider">Dokter</th>
                    <th class="px-4 py-3 font-bold text-slate-200 uppercase text-[10px] tracking-wider">Diagnosa</th>
                    <th class="px-4 py-3 font-bold text-slate-200 uppercase text-[10px] tracking-wider">Tindakan</th>
                    <th class="px-4 py-3 font-bold text-slate-200 uppercase text-[10px] tracking-wider">Status</th>
                </tr></thead><tbody>`;

            sorted.forEach(rm => {
                html += `<tr class="border-b border-slate-50 last:border-0 hover:bg-slate-50/50">
                    <td class="px-4 py-2.5 text-slate-800 whitespace-nowrap font-semibold">${formatDate(rm.tanggal)}</td>
                    <td class="px-4 py-2.5 text-slate-600 font-medium">${rm.dokter_hewan || '-'}</td>
                    <td class="px-4 py-2.5 text-slate-600 font-medium">${rm.diagnosa || '-'}</td>
                    <td class="px-4 py-2.5 text-slate-600 font-medium">${rm.tindakan || '-'}</td>
                    <td class="px-4 py-2.5">${getStatusBadge(rm.status)}</td>
                </tr>`;
            });

            html += `</tbody></table></div>`;
            return html;
        }

        function openModal(button) {
            activeProductId = button.dataset.productId;
            productName.textContent = button.dataset.productName || 'Produk';
            
            // Set goat/product image with unsplash fallback
            const imgUrl = button.dataset.productImage || 'https://images.unsplash.com/photo-1524413840807-0c3cb6fa808d?auto=format&fit=crop&w=1200&q=80';
            productImage.src = imgUrl;

            // Set dynamic gender badge
            const gender = button.dataset.productGender || '';
            if (gender && gender !== '-') {
                productImageBadgeText.textContent = gender;
                productImageBadge.classList.remove('hidden');
            } else {
                productImageBadge.classList.add('hidden');
            }

            productSpecs.textContent = button.dataset.productSpecs || 'Tidak ada spesifikasi tambahan.';
            productGender.textContent = button.dataset.productGender || '-';
            productAge.textContent = button.dataset.productAge || '-';
            productBerat.textContent = button.dataset.productBerat || '-';
            productPrice.textContent = button.dataset.productPrice || '-';

            try {
                const rekamMedisData = JSON.parse(button.dataset.productRekamMedis || '[]');
                rekamMedisContent.innerHTML = renderRekamMedis(rekamMedisData);
            } catch(e) {
                rekamMedisContent.innerHTML = renderRekamMedis([]);
            }

            const message = encodeURIComponent(
                `Halo Admin Goatin, saya berminat membeli ternak berikut:\n` +
                `Nama Produk: ${button.dataset.productName}\n` +
                `Jenis: ${button.dataset.productGender}\n` +
                `Umur: ${button.dataset.productAge}\n` +
                `Harga: ${button.dataset.productPrice}\n\n` +
                `Mohon dikonfirmasi ketersediaan dan detail pengirimannya.`
            );
            buyButton.href = `https://wa.me/${phoneNumber}?text=${message}`;

            window.openModal('productModal');
        }

        function closeModal() {
            window.closeModal('productModal');
            activeProductId = null;
        }

        openButtons.forEach(button => {
            button.addEventListener('click', function () {
                openModal(this);
            });
        });

        buyButton.addEventListener('click', function() {
            if (activeProductId) {
                fetch(`/customer/produk/${activeProductId}/beli`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });
            }
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
@endsection
