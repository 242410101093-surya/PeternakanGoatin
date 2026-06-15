@extends('layouts.customer')

@section('title', 'Katalog Produk')

@section('content')
<main class="max-w-[1600px] mx-auto px-6 py-8 space-y-10">

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

    {{-- ── Products Card Grid Container ── --}}
    <div class="relative min-h-[400px]">
        {{-- Modern Localized Loader --}}
        <div id="produkGridLoader" class="absolute inset-0 z-20 hidden flex-col items-center justify-center bg-white/30 backdrop-blur-[2px] rounded-[28px] transition-all duration-300">
            @include('partials.modern_loader')
        </div>
        
        <div id="produkGridContainer" class="transition-opacity duration-300">
            @include('customer.partials.produk_grid')
        </div>
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

    let activeProductId = null;
    const phoneNumber = '{{ config("app.whatsapp_number") }}';

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

    window.openProductModal = function(button) {
        activeProductId = button.dataset.productId;
        document.getElementById('modalProductName').textContent = button.dataset.productName || 'Produk';
        
        const imgUrl = button.dataset.productImage || 'https://images.unsplash.com/photo-1524413840807-0c3cb6fa808d?auto=format&fit=crop&w=1200&q=80';
        document.getElementById('modalProductImage').src = imgUrl;

        const gender = button.dataset.productGender || '';
        const badge = document.getElementById('modalProductImageBadge');
        if (gender && gender !== '-') {
            document.getElementById('modalProductImageBadgeText').textContent = gender;
            badge.classList.remove('hidden');
        } else {
            badge.classList.add('hidden');
        }

        document.getElementById('modalProductSpecs').textContent = button.dataset.productSpecs || 'Tidak ada spesifikasi tambahan.';
        document.getElementById('modalProductGender').textContent = button.dataset.productGender || '-';
        document.getElementById('modalProductAge').textContent = button.dataset.productAge || '-';
        document.getElementById('modalProductBerat').textContent = button.dataset.productBerat || '-';
        document.getElementById('modalProductPrice').textContent = button.dataset.productPrice || '-';

        const rekamMedisContent = document.getElementById('modalRekamMedisContent');
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
        document.getElementById('modalBuyButton').href = `https://wa.me/${phoneNumber}?text=${message}`;

        window.openModal('productModal');
    };

    document.addEventListener('DOMContentLoaded', function () {
        const modal = document.getElementById('productModal');
        const closeButton = document.getElementById('closeProductModal');
        const cancelButton = document.getElementById('modalCancelButton');
        const buyButton = document.getElementById('modalBuyButton');

        function closeModal() {
            window.closeModal('productModal');
            activeProductId = null;
        }

        buyButton.addEventListener('click', function() {
            if (activeProductId) {
                // Tampilkan loading / disabled status sejenak
                buyButton.style.opacity = '0.7';
                buyButton.style.pointerEvents = 'none';

                fetch(`/customer/produk/${activeProductId}/beli`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        // Tutup modal
                        closeModal();
                        
                        // Tampilkan toast
                        if (window.showToast) {
                            window.showToast('Pesanan berhasil dibuat. Silakan lanjutkan di WhatsApp!', 'success');
                        }
                        
                        // Kembalikan tombol beli
                        buyButton.style.opacity = '1';
                        buyButton.style.pointerEvents = 'auto';

                        // Refresh katalog (silently update grid)
                        setTimeout(() => {
                            fetchProducts(window.location.href, true);
                        }, 500);
                    }
                })
                .catch(err => {
                    buyButton.style.opacity = '1';
                    buyButton.style.pointerEvents = 'auto';
                    console.error('Beli error:', err);
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

        // AJAX Filtering Logic
        const filterForm = document.querySelector('form[action="{{ route("customer.produk") }}"]');
        const gridContainer = document.getElementById('produkGridContainer');

        function fetchProducts(url = null, silent = false) {
            let fetchUrl = url;
            if (!fetchUrl) {
                const formData = new FormData(filterForm);
                const params = new URLSearchParams(formData);
                fetchUrl = filterForm.action + '?' + params.toString();
            }

            if (!silent) {
                const loader = document.getElementById('produkGridLoader');
                if (loader) {
                    loader.classList.remove('hidden');
                    loader.classList.add('flex');
                }
                gridContainer.style.opacity = '0.4';
                gridContainer.style.pointerEvents = 'none';
            }

            fetch(fetchUrl, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'text/html'
                }
            })
            .then(res => res.text())
            .then(html => {
                if (silent) {
                    // Update silently without disturbing the user if HTML changed
                    if (gridContainer.innerHTML !== html) {
                        gridContainer.innerHTML = html;
                        if (typeof AOS !== 'undefined') {
                            AOS.refreshHard();
                        }
                    }
                } else {
                    const loader = document.getElementById('produkGridLoader');
                    if (loader) {
                        loader.classList.add('hidden');
                        loader.classList.remove('flex');
                    }
                    
                    gridContainer.innerHTML = html;
                    gridContainer.style.opacity = '1';
                    gridContainer.style.pointerEvents = 'auto';
                    
                    if (typeof AOS !== 'undefined') {
                        AOS.refreshHard();
                    }
                    window.history.pushState({}, '', fetchUrl);
                }
            })
            .catch(err => {
                console.error('AJAX Filter Error:', err);
                if (!silent) {
                    const loader = document.getElementById('produkGridLoader');
                    if (loader) {
                        loader.classList.add('hidden');
                        loader.classList.remove('flex');
                    }
                    gridContainer.style.opacity = '1';
                    gridContainer.style.pointerEvents = 'auto';
                }
            });
        }

        // Real-time polling every 15 seconds to update catalog automatically
        setInterval(() => {
            fetchProducts(window.location.href, true);
        }, 15000);

        if (filterForm) {
            filterForm.addEventListener('submit', function(e) {
                e.preventDefault();
                fetchProducts();
            });

            // Auto-submit on change for selects and inputs
            const inputs = filterForm.querySelectorAll('select, input');
            inputs.forEach(input => {
                input.addEventListener('change', () => {
                    fetchProducts();
                });
            });
        }

        // Intercept Pagination Links
        document.addEventListener('click', function(e) {
            const link = e.target.closest('#produkGridContainer a[href*="page="]');
            if (link) {
                e.preventDefault();
                fetchProducts(link.href);
                // Scroll to top of grid
                window.scrollTo({
                    top: gridContainer.offsetTop - 100,
                    behavior: 'smooth'
                });
            }
        });
    });
</script>
@endsection
