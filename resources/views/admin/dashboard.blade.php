@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<style>
    /* ── Premium Admin Dashboard Styles ── */
    .admin-card {
        background: rgba(255, 255, 255, 0.75);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.5);
        border-radius: 24px;
        box-shadow: 0 10px 30px -10px rgba(5, 31, 32, 0.03);
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .admin-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 40px -15px rgba(5, 31, 32, 0.08);
        border-color: rgba(42, 120, 68, 0.2);
    }
    .hover-scale-btn {
        transition: all 0.2s ease-in-out;
    }
    .hover-scale-btn:hover {
        transform: scale(1.02);
    }
</style>

<div class="px-6 md:px-8 py-8 fade-in">
    <div class="max-w-container-max mx-auto space-y-8">

        {{-- Flash Messages --}}
        @if(session('success'))
        <div class="flex items-center gap-3 px-5 py-4 rounded-2xl text-sm font-semibold shadow-sm" style="background:#DCFCE7;color:#166534;border:1px solid #bbf7d0;">
            <span class="material-symbols-outlined text-emerald-600" style="font-size:20px;">check_circle</span>
            {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div class="flex items-center gap-3 px-5 py-4 rounded-2xl text-sm font-semibold shadow-sm" style="background:#FEE2E2;color:#991B1B;border:1px solid #fecaca;">
            <span class="material-symbols-outlined text-red-600" style="font-size:20px;">error</span>
            {{ session('error') }}
        </div>
        @endif

        {{-- Page Header --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black" style="color:#051F20; letter-spacing:-.025em; text-transform: uppercase;">Ringkasan Dashboard</h1>
                <p class="text-xs md:text-sm mt-1 font-semibold" style="color:#64748B;">Selamat datang kembali, Admin. Berikut ringkasan aktivitas peternakan Goatin.</p>
            </div>
            <div class="flex items-center gap-3">
                <span class="flex items-center gap-1.5 text-xs font-bold px-3.5 py-1.5 rounded-full border border-[#bbf7d0]" style="background:#DCFCE7;color:#166534;">
                    <span class="w-2 h-2 rounded-full inline-block bg-[#2A7844] animate-pulse"></span>
                    Sistem Aktif & Normal
                </span>
                <span class="text-xs font-bold" style="color:#94A3B8;">{{ now()->format('d M Y, H:i') }}</span>
            </div>
        </div>

        {{-- ── KPI Stat Cards ── --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

            {{-- Card 1: Total Users --}}
            <a href="{{ route('admin.accounts.index') }}" class="admin-card p-6 block group">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center shadow-md bg-gradient-to-tr from-goatin-green to-emerald-500 text-white">
                        <span class="material-symbols-outlined" style="font-size:24px;">group</span>
                    </div>
                    <span class="text-[10px] font-black tracking-wider uppercase px-2.5 py-1 rounded-lg bg-blue-100 text-blue-800 border border-blue-200">Mitra Aktif</span>
                </div>
                <p class="text-3xl font-black mb-1 text-primary-dark tracking-tight">{{ number_format($totalUsers) }}</p>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Pengguna Terdaftar</p>
                <div class="flex items-center gap-1 mt-4 text-xs font-bold text-goatin-green group-hover:translate-x-1 transition-transform">
                    <span>Kelola Akun Mitra</span>
                    <span class="material-symbols-outlined text-sm font-bold">arrow_forward</span>
                </div>
            </a>

            {{-- Card 2: Net Profit --}}
            <a href="{{ route('admin.keuangan.index') }}" class="admin-card p-6 block group">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center shadow-md bg-gradient-to-tr from-amber-600 to-amber-400 text-white">
                        <span class="material-symbols-outlined" style="font-size:24px;">payments</span>
                    </div>
                    <span class="text-[10px] font-black tracking-wider uppercase px-2.5 py-1 rounded-lg {{ $labaBersih >= 0 ? 'bg-emerald-100 text-emerald-800 border-emerald-200' : 'bg-red-100 text-red-800 border-red-200' }} border">
                        {{ $labaBersih >= 0 ? 'Laba Bersih' : 'Rugi Bersih' }}
                    </span>
                </div>
                <p id="dashboard-laba-bersih-count" class="text-3xl font-black mb-1 tracking-tight"
                   style="color:{{ $labaBersih < 0 ? '#DC2626' : '#051F20' }};">
                    Rp {{ number_format(abs($labaBersih), 0, ',', '.') }}
                </p>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Laba Bersih — {{ $currentMonth }}</p>
                <div class="flex items-center gap-1 mt-4 text-xs font-bold text-goatin-green group-hover:translate-x-1 transition-transform">
                    <span>Lihat Laporan Keuangan</span>
                    <span class="material-symbols-outlined text-sm font-bold">arrow_forward</span>
                </div>
            </a>

            {{-- Card 3: Notifications --}}
            <button onclick="openNotificationsModal()" class="admin-card p-6 text-left block w-full cursor-pointer group">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center shadow-md bg-gradient-to-tr from-red-600 to-red-400 text-white">
                        <span class="material-symbols-outlined" style="font-size:24px;">notifications</span>
                    </div>
                    <span id="dashboard-pending-orders-badge" class="text-[10px] font-black tracking-wider uppercase px-2.5 py-1 rounded-lg bg-red-100 text-red-800 border border-red-200 animate-pulse {{ $pendingOrders > 0 ? '' : 'hidden' }}">Baru</span>
                </div>
                <p id="dashboard-pending-orders-count" class="text-3xl font-black mb-1 text-primary-dark tracking-tight">{{ number_format($pendingOrders) }}</p>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Notifikasi Belum Dibaca</p>
                <div class="flex items-center gap-1 mt-4 text-xs font-bold group-hover:translate-x-1 transition-transform" style="color:{{ $pendingOrders > 0 ? '#DC2626' : '#2A7844' }};" id="dashboard-pending-orders-action">
                    <span>{{ $pendingOrders > 0 ? 'Konfirmasi Sekarang' : 'Semua sudah dibaca' }}</span>
                    <span class="material-symbols-outlined text-sm font-bold">arrow_forward</span>
                </div>
            </button>
        </div>

        {{-- ── Charts Section ── --}}
        <div class="grid grid-cols-1 gap-5">
            {{-- Sales Chart --}}
            <div class="admin-card p-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="font-black text-sm md:text-base uppercase tracking-wider text-primary-dark">Pertumbuhan Penjualan</h3>
                        <p class="text-[10px] md:text-xs mt-0.5 font-bold text-slate-400 uppercase tracking-wide">Analisis Tren 6 Bulan Terakhir</p>
                    </div>
                    <select class="text-xs font-bold px-3.5 py-2 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-goatin-green bg-slate-50 text-slate-600">
                        <option>6 Bulan Terakhir</option>
                        <option>Tahun Ini</option>
                    </select>
                </div>
                {{-- SVG Chart --}}
                <div class="relative h-60 w-full">
                    <svg class="w-full h-full" viewBox="0 0 600 200" preserveAspectRatio="none">
                        <defs>
                            <linearGradient id="chartGrad" x1="0" y1="0" x2="0" y2="1">
                                <stop offset="0%" style="stop-color:#2A7844;stop-opacity:.25"/>
                                <stop offset="100%" style="stop-color:#2A7844;stop-opacity:0"/>
                            </linearGradient>
                        </defs>
                        {{-- Grid lines --}}
                        @foreach([40,80,120,160] as $y)
                        <line x1="0" y1="{{ $y }}" x2="600" y2="{{ $y }}" stroke="#F1F5F9" stroke-width="1.5"/>
                        @endforeach
                        {{-- Area --}}
                        @php
                            $pathD = "M 30,200 ";
                            $lineD = "";
                            if(isset($svgPoints) && count($svgPoints) > 0) {
                                $lineD = "M {$svgPoints[0][0]},{$svgPoints[0][1]} ";
                                foreach($svgPoints as $index => $pt) {
                                    $pathD .= "L {$pt[0]},{$pt[1]} ";
                                    if($index > 0) $lineD .= "L {$pt[0]},{$pt[1]} ";
                                }
                            } else {
                                $pathD .= "L 30,160 L 570,20 L 570,200 ";
                                $lineD = "M 30,160 L 570,20";
                            }
                            $pathD .= "L 570,200 Z";
                        @endphp
                        <path d="{{ $pathD }}" fill="url(#chartGrad)"/>
                        {{-- Line --}}
                        <path d="{{ $lineD }}" fill="none" stroke="#2A7844" stroke-width="3.5" stroke-linecap="round"/>
                        {{-- Data points --}}
                        @foreach($svgPoints ?? [[30,160,'Jan'],[570,20,'Jun']] as [$x,$y,$label])
                        <circle cx="{{ $x }}" cy="{{ $y }}" r="5" fill="#2A7844" stroke="#fff" stroke-width="2.5" class="shadow-sm"/>
                        <text x="{{ $x }}" y="190" text-anchor="middle" font-size="10" font-weight="800" fill="#94A3B8" font-family="'Plus Jakarta Sans',sans-serif">{{ $label }}</text>
                        @endforeach
                    </svg>
                </div>
            </div>
        </div>

        {{-- ── Recent Activity / Quick Access ── --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Quick Links --}}
            <div class="admin-card p-6">
                <h3 class="font-black text-sm md:text-base uppercase tracking-wider text-primary-dark mb-5">Akses Cepat Panel</h3>
                <div class="grid grid-cols-2 gap-4">
                    @foreach([
                        ['route'=>'admin.inventaris.index','icon'=>'inventory_2','label'=>'Inventaris','color'=>'#051F20','bg'=>'linear-gradient(135deg, #051F20 0%, #0B2B26 100%)'],
                        ['route'=>'admin.rekam-medis.index','icon'=>'medical_services','label'=>'Rekam Medis','color'=>'#2A7844','bg'=>'linear-gradient(135deg, #2A7844 0%, #338f52 100%)'],
                        ['route'=>'admin.artikel.index','icon'=>'description','label'=>'Artikel','color'=>'#D97706','bg'=>'linear-gradient(135deg, #D97706 0%, #F59E0B 100%)'],
                        ['route'=>'admin.katalog.index','icon'=>'menu_book','label'=>'Katalog','color'=>'#7C3AED','bg'=>'linear-gradient(135deg, #7C3AED 0%, #8B5CF6 100%)'],
                    ] as $item)
                    <a href="{{ route($item['route']) }}"
                       class="flex items-center gap-3.5 p-3.5 rounded-2xl transition-all duration-300 group hover-scale-btn"
                       style="border:1px solid rgba(226, 232, 240, 0.8); background: rgba(255,255,255,0.4);"
                       onmouseover="this.style.background='rgba(255,255,255,0.9)';this.style.borderColor='{{ $item['color'] }}';"
                       onmouseout="this.style.background='rgba(255,255,255,0.4)';this.style.borderColor='rgba(226, 232, 240, 0.8)';">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 shadow-sm"
                             style="background:{{ $item['bg'] }};">
                            <span class="material-symbols-outlined text-white text-[20px]">{{ $item['icon'] }}</span>
                        </div>
                        <span class="text-xs font-black text-slate-700 uppercase tracking-wide">{{ $item['label'] }}</span>
                    </a>
                    @endforeach
                </div>
            </div>

            {{-- Recent Notifications --}}
            <div class="admin-card p-6">
                <div class="flex items-center justify-between mb-5">
                    <h3 class="font-black text-sm md:text-base uppercase tracking-wider text-primary-dark">Notifikasi Terbaru</h3>
                    <div id="dashboard-lihat-semua-container" class="{{ $pendingOrders > 0 ? '' : 'hidden' }}">
                        <button onclick="openNotificationsModal()"
                                class="text-[10px] font-black uppercase tracking-wider px-3.5 py-1.5 rounded-xl transition-all hover:bg-emerald-200 cursor-pointer shadow-sm"
                                style="background:#DCFCE7;color:#166534;border:1px solid #bbf7d0;">
                            Lihat Semua
                        </button>
                    </div>
                </div>
                <div class="space-y-3.5" id="dashboard-recent-notifications-list">
                    @include('admin.partials.notifications_dashboard_list')
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ── MODAL: Notifikasi Pesanan ── --}}
@include('admin.partials.notifications_order_modal')

<script>
    function openNotificationsModal()  { const m=document.getElementById('notificationsModal'); if(m) { m.classList.remove('hidden'); m.classList.add('flex'); } }
    function closeNotificationsModal() { const m=document.getElementById('notificationsModal'); if(m) { m.classList.add('hidden'); m.classList.remove('flex'); } }
    
    function focusNotificationInModal(notifId) {
        openNotificationsModal();
        setTimeout(() => {
            const item = document.getElementById(`modal-notif-item-${notifId}`);
            if (item) {
                item.scrollIntoView({ behavior: 'smooth', block: 'center' });
                item.style.transition = 'all 0.3s ease';
                item.style.borderColor = '#10B981';
                item.style.boxShadow = '0 0 0 4px rgba(16, 185, 129, 0.25)';
                setTimeout(() => {
                    item.style.borderColor = 'rgba(42,120,68,0.1)';
                    item.style.boxShadow = '';
                }, 2500);
            }
        }, 300);
    }
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-focus notification from url parameter
        const urlParams = new URLSearchParams(window.location.search);
        const notifId = urlParams.get('notif_id');
        if (notifId) {
            const cleanUrl = window.location.protocol + "//" + window.location.host + window.location.pathname;
            window.history.replaceState({ path: cleanUrl }, '', cleanUrl);
            setTimeout(() => {
                focusNotificationInModal(notifId);
            }, 600);
        }

        // AJAX-ify Tandai Semua Dibaca in Notifications Modal
        const readAllForm = document.getElementById('readAllNotificationsForm');
        if (readAllForm) {
            readAllForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const loader = document.getElementById('global-page-loader');
                if(loader) loader.style.display = 'flex';
                
                fetch(readAllForm.getAttribute('action'), {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if(loader) loader.style.display = 'none';
                    if (data.success) {
                        if(window.showToast) window.showToast(data.message, 'success');
                        
                        // Clear list in modal
                        const list = document.getElementById('modal-notifications-list');
                        if (list) {
                            list.innerHTML = `
                                <div class="text-center py-16 empty-placeholder">
                                    <div class="w-20 h-20 rounded-full mx-auto flex items-center justify-center mb-4" style="background:#F1F5F9;">
                                        <span class="material-symbols-outlined text-5xl" style="color:#CBD5E1;">notifications_none</span>
                                    </div>
                                    <p class="text-sm font-black" style="color:#94A3B8;">Tidak Ada Notifikasi Baru</p>
                                    <p class="text-xs mt-1" style="color:#CBD5E1;">Semua pesanan sudah ditangani</p>
                                </div>`;
                        }
                        
                        // Clear list in recent panel
                        const recentList = document.getElementById('dashboard-recent-notifications-list');
                        if (recentList) {
                            recentList.innerHTML = `
                                <div class="text-center py-6 empty-placeholder">
                                    <span class="material-symbols-outlined text-3xl" style="color:#CBD5E1;">done_all</span>
                                    <p class="text-xs mt-2" style="color:#94A3B8;">Semua notifikasi sudah dibaca</p>
                                </div>`;
                        }
                        
                        // Hide "Lihat Semua"
                        const lsContainer = document.getElementById('dashboard-lihat-semua-container');
                        if (lsContainer) lsContainer.classList.add('hidden');
                        
                        // Hide modal footer read all container
                        const raContainer = document.getElementById('modal-read-all-container');
                        if (raContainer) raContainer.classList.add('hidden');
                        
                        // Update navbar notifications lists via layout helper
                        if (window.updateGlobalPendingCounts) {
                            window.updateGlobalPendingCounts(0);
                        }
                        
                        // Clear unread items styles and buttons in navbar dropdown
                        document.querySelectorAll('[id^="navbar-notif-item-"]').forEach(item => {
                            item.classList.remove('bg-emerald-50/20');
                        });
                        document.querySelectorAll('.navbar-mark-read-form').forEach(f => f.remove());
                        const navbarReadAllForm = document.getElementById('navbar-read-all-form');
                        if (navbarReadAllForm) navbarReadAllForm.remove();
                    }
                })
                .catch(err => {
                    if(loader) loader.style.display = 'none';
                    if(window.showToast) window.showToast('Terjadi kesalahan jaringan.', 'error');
                });
            });
        }

        // AJAX-ify Confirm Order Form Submission
                window.confirmOrderDirect = function(notifId, user, idTernak, jenis, ras) {
            const input = document.querySelector(`.notif-harga-input[data-notif-id="${notifId}"]`);
            let harga = 0;
            if (input) {
                harga = input.value;
            }
            
            const formattedHarga = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(harga);
            
            const loader = document.getElementById('global-page-loader');
            if(loader) loader.style.display = 'flex';
            
            const formData = new FormData();
            formData.append('harga_jual', harga);
            // Include default title and message to bypass validation if needed by backend
            formData.append('title', 'Pesanan Dikonfirmasi');
            formData.append('message', `Pesanan untuk ${jenis} ${ras} telah dikonfirmasi dengan harga ${formattedHarga}.`);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').content : '{{ csrf_token() }}');
            
            fetch(`/admin/notifications/${notifId}/confirm`, {
                method: 'POST',
                body: formData,
                headers: {
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if(loader) loader.style.display = 'none';
                if (data.success) {
                    if(window.showToast) window.showToast(data.message, 'success');
                    
                    const recentItem = document.getElementById(`dashboard-recent-notif-item-${notifId}`);
                    if (recentItem) recentItem.remove();
                    
                    const modalItem = document.getElementById(`modal-notif-item-${notifId}`);
                    if (modalItem) modalItem.remove();
                    
                    const navbarItem = document.getElementById(`navbar-notif-item-${notifId}`);
                    if (navbarItem) navbarItem.remove();
                    
                    const profitCount = document.getElementById('dashboard-laba-bersih-count');
                    if (profitCount && data.labaBersih !== undefined) profitCount.textContent = `Rp ${data.labaBersih}`;
                    
                    if (window.updateGlobalPendingCounts && data.pendingOrders !== undefined) {
                        window.updateGlobalPendingCounts(data.pendingOrders);
                    }
                    if (typeof checkEmptyNotificationStates === 'function') {
                        checkEmptyNotificationStates(data.pendingOrders);
                    }
                } else {
                    if(window.showToast) window.showToast(data.message || 'Gagal mengonfirmasi', 'error');
                }
            })
            .catch(err => {
                if(loader) loader.style.display = 'none';
                if(window.showToast) window.showToast('Terjadi kesalahan jaringan.', 'error');
            });
        }

        window.rejectOrder = function(directId = null) {
            let notifId = directId;
            if (!notifId) return;
            
            const loader = document.getElementById('global-page-loader');
            if(loader) loader.style.display = 'flex';
            
            fetch(`/admin/notifications/${notifId}/reject`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if(loader) loader.style.display = 'none';
                if (data.success) {
                    if(window.showToast) window.showToast(data.message, 'success');
                    if(window.closeConfirmOrderModal) window.closeConfirmOrderModal();
                    
                    // Remove item from lists
                    const recentItem = document.getElementById(`dashboard-recent-notif-item-${notifId}`);
                    if (recentItem) recentItem.remove();
                    const modalItem = document.getElementById(`modal-notif-item-${notifId}`);
                    if (modalItem) modalItem.remove();
                    const navbarItem = document.getElementById(`navbar-notif-item-${notifId}`);
                    if (navbarItem) navbarItem.remove();
                    
                    // Update global counts
                    if (window.updateGlobalPendingCounts) {
                        window.updateGlobalPendingCounts(data.pendingOrders);
                    }
                    
                    checkEmptyNotificationStates(data.pendingOrders);
                }
            })
            .catch(err => {
                if(loader) loader.style.display = 'none';
                if(window.showToast) window.showToast('Terjadi kesalahan jaringan.', 'error');
            });
        };

        function checkEmptyNotificationStates(count) {
            if (count === 0) {
                // Modal List
                const list = document.getElementById('modal-notifications-list');
                if (list) {
                    list.innerHTML = `
                        <div class="text-center py-12 empty-placeholder">
                            <span class="material-symbols-outlined text-5xl" style="color:#CBD5E1;">notifications_none</span>
                            <p class="text-sm mt-3" style="color:#94A3B8;">Tidak ada notifikasi baru</p>
                        </div>`;
                }
                
                // Recent List
                const recentList = document.getElementById('dashboard-recent-notifications-list');
                if (recentList) {
                    recentList.innerHTML = `
                        <div class="text-center py-6 empty-placeholder">
                            <span class="material-symbols-outlined text-3xl" style="color:#CBD5E1;">done_all</span>
                            <p class="text-xs mt-2" style="color:#94A3B8;">Semua notifikasi sudah dibaca</p>
                        </div>`;
                }
                
                // Hide elements
                const lsContainer = document.getElementById('dashboard-lihat-semua-container');
                if (lsContainer) lsContainer.classList.add('hidden');
                
                const raContainer = document.getElementById('modal-read-all-container');
                if (raContainer) raContainer.classList.add('hidden');
                
                const navbarReadAllForm = document.getElementById('navbar-read-all-form');
                if (navbarReadAllForm) navbarReadAllForm.remove();
            }
        }
    });
</script>
@endsection
