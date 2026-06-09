@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="px-6 md:px-8 py-8 fade-in">
    <div class="max-w-[1280px] mx-auto space-y-8">

        {{-- Flash Messages --}}
        @if(session('success'))
        <div class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium" style="background:#DCFCE7;color:#166534;border:1px solid #bbf7d0;">
            <span class="material-symbols-outlined" style="font-size:18px;">check_circle</span>
            {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium" style="background:#FEE2E2;color:#991B1B;border:1px solid #fecaca;">
            <span class="material-symbols-outlined" style="font-size:18px;">error</span>
            {{ session('error') }}
        </div>
        @endif

        {{-- Page Header --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold" style="color:#051F20; letter-spacing:-.02em;">Ringkasan Dashboard</h1>
                <p class="text-sm mt-1" style="color:#64748B;">Selamat datang kembali. Berikut ringkasan aktivitas platform Goatin.</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 rounded-full" style="background:#DCFCE7;color:#166534;">
                    <span class="w-1.5 h-1.5 rounded-full inline-block" style="background:#2A7844;"></span>
                    Sistem Berjalan
                </span>
                <span class="text-xs" style="color:#94A3B8;">{{ now()->format('d M Y, H:i') }}</span>
            </div>
        </div>

        {{-- ── KPI Stat Cards ── --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">

            {{-- Card 1: Total Users --}}
            <a href="{{ route('admin.accounts.index') }}" class="card p-6 block group">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-11 h-11 rounded-xl flex items-center justify-center shadow-sm" style="background: linear-gradient(135deg, #2A7844 0%, #338f52 100%);">
                        <span class="material-symbols-outlined text-white" style="font-size:22px;">group</span>
                    </div>
                    <span class="text-xs font-semibold px-2 py-1 rounded-full" style="background:#DBEAFE;color:#1E40AF;">+12%</span>
                </div>
                <p class="text-3xl font-extrabold mb-1" style="color:#051F20;">{{ number_format($totalUsers) }}</p>
                <p class="text-sm font-medium" style="color:#64748B;">Total Pengguna</p>
                <div class="flex items-center gap-1 mt-3 text-xs font-semibold" style="color:#2A7844;">
                    Kelola Akun
                    <span class="material-symbols-outlined" style="font-size:14px;">arrow_forward</span>
                </div>
            </a>

            {{-- Card 2: Net Profit --}}
            <a href="{{ route('admin.keuangan.index') }}" class="card p-6 block group">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-11 h-11 rounded-xl flex items-center justify-center shadow-sm" style="background: linear-gradient(135deg, #D97706 0%, #F59E0B 100%);">
                        <span class="material-symbols-outlined text-white" style="font-size:22px;">payments</span>
                    </div>
                    <span class="text-xs font-semibold px-2 py-1 rounded-full {{ $labaBersih >= 0 ? '' : '' }}"
                          style="{{ $labaBersih >= 0 ? 'background:#DCFCE7;color:#166534;' : 'background:#FEE2E2;color:#991B1B;' }}">
                        {{ $labaBersih >= 0 ? 'Laba' : 'Rugi' }}
                    </span>
                </div>
                <p id="dashboard-laba-bersih-count" class="text-3xl font-extrabold mb-1 {{ $labaBersih < 0 ? 'text-[#DC2626]' : '' }}"
                   style="color:{{ $labaBersih < 0 ? '#DC2626' : '#051F20' }};">
                    Rp {{ number_format(abs($labaBersih), 0, ',', '.') }}
                </p>
                <p class="text-sm font-medium" style="color:#64748B;">Laba Bersih — {{ $currentMonth }}</p>
                <div class="flex items-center gap-1 mt-3 text-xs font-semibold" style="color:#2A7844;">
                    Lihat Laporan
                    <span class="material-symbols-outlined" style="font-size:14px;">arrow_forward</span>
                </div>
            </a>

            {{-- Card 3: Notifications --}}
            <button onclick="openNotificationsModal()" class="card p-6 text-left block w-full cursor-pointer">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-11 h-11 rounded-xl flex items-center justify-center shadow-sm" style="background: linear-gradient(135deg, #DC2626 0%, #EF4444 100%);">
                        <span class="material-symbols-outlined text-white" style="font-size:22px;">notifications</span>
                    </div>
                    <span id="dashboard-pending-orders-badge" class="text-xs font-bold px-2 py-1 rounded-full animate-pulse {{ $pendingOrders > 0 ? '' : 'hidden' }}" style="background:#FEE2E2;color:#991B1B;">Baru</span>
                </div>
                <p id="dashboard-pending-orders-count" class="text-3xl font-extrabold mb-1" style="color:#051F20;">{{ number_format($pendingOrders) }}</p>
                <p class="text-sm font-medium" style="color:#64748B;">Notifikasi Belum Dibaca</p>
                <div class="flex items-center gap-1 mt-3 text-xs font-semibold" style="color:#DC2626;" id="dashboard-pending-orders-action">
                    <span>{{ $pendingOrders > 0 ? 'Konfirmasi Sekarang' : 'Semua dibaca' }}</span>
                    <span class="material-symbols-outlined" style="font-size:14px;">arrow_forward</span>
                </div>
            </button>
        </div>

        {{-- ── Charts Section ── --}}
        <div class="grid grid-cols-1 gap-5">

            {{-- Sales Chart --}}
            <div class="card p-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="font-bold text-base" style="color:#051F20;">Pertumbuhan Penjualan</h3>
                        <p class="text-xs mt-0.5" style="color:#94A3B8;">6 bulan terakhir</p>
                    </div>
                    <select class="text-xs font-medium px-3 py-1.5 rounded-lg border focus:outline-none"
                            style="border-color:#E2E8F0;color:#64748B;background:#F8FAFC;">
                        <option>6 Bulan Terakhir</option>
                        <option>Tahun Ini</option>
                    </select>
                </div>
                {{-- SVG Chart --}}
                <div class="relative h-56 w-full">
                    <svg class="w-full h-full" viewBox="0 0 600 200" preserveAspectRatio="none">
                        <defs>
                            <linearGradient id="chartGrad" x1="0" y1="0" x2="0" y2="1">
                                <stop offset="0%" style="stop-color:#2A7844;stop-opacity:.18"/>
                                <stop offset="100%" style="stop-color:#2A7844;stop-opacity:0"/>
                            </linearGradient>
                        </defs>
                        {{-- Grid lines --}}
                        @foreach([40,80,120,160] as $y)
                        <line x1="0" y1="{{ $y }}" x2="600" y2="{{ $y }}" stroke="#E2E8F0" stroke-width="1"/>
                        @endforeach
                        {{-- Area --}}
                        <path d="M 30,160 C 80,150 130,130 180,110 S 280,75 360,60 S 470,35 570,20 L570,200 L30,200 Z"
                              fill="url(#chartGrad)"/>
                        {{-- Line --}}
                        <path d="M 30,160 C 80,150 130,130 180,110 S 280,75 360,60 S 470,35 570,20"
                              fill="none" stroke="#2A7844" stroke-width="2.5" stroke-linecap="round"/>
                        {{-- Data points --}}
                        @foreach([[30,160,'Jan'],[150,115,'Feb'],[270,85,'Mar'],[390,62,'Apr'],[480,40,'Mei'],[570,20,'Jun']] as [$x,$y,$label])
                        <circle cx="{{ $x }}" cy="{{ $y }}" r="4" fill="#2A7844" stroke="#fff" stroke-width="2"/>
                        <text x="{{ $x }}" y="188" text-anchor="middle" font-size="11" fill="#94A3B8" font-family="'Plus Jakarta Sans',sans-serif">{{ $label }}</text>
                        @endforeach
                    </svg>
                </div>
            </div>
        </div>

        {{-- ── Recent Activity / Quick Access ── --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            {{-- Quick Links --}}
            <div class="card p-6">
                <h3 class="font-bold text-base mb-4" style="color:#051F20;">Akses Cepat</h3>
                <div class="grid grid-cols-2 gap-3">
                    @foreach([
                        ['route'=>'admin.inventaris.index','icon'=>'inventory_2','label'=>'Inventaris','color'=>'#051F20','bg'=>'linear-gradient(135deg, #051F20 0%, #0B2B26 100%)'],
                        ['route'=>'admin.rekam-medis.index','icon'=>'medical_services','label'=>'Rekam Medis','color'=>'#2A7844','bg'=>'linear-gradient(135deg, #2A7844 0%, #338f52 100%)'],
                        ['route'=>'admin.artikel.index','icon'=>'description','label'=>'Artikel','color'=>'#D97706','bg'=>'linear-gradient(135deg, #D97706 0%, #F59E0B 100%)'],
                        ['route'=>'admin.katalog.index','icon'=>'menu_book','label'=>'Katalog','color'=>'#7C3AED','bg'=>'linear-gradient(135deg, #7C3AED 0%, #8B5CF6 100%)'],
                    ] as $item)
                    <a href="{{ route($item['route']) }}"
                       class="flex items-center gap-3 p-3 rounded-xl transition-all duration-200 group"
                       style="border:1px solid #E2E8F0;"
                       onmouseover="this.style.background='#F8FAFC';this.style.borderColor='{{ $item['color'] }}';"
                       onmouseout="this.style.background='transparent';this.style.borderColor='#E2E8F0';">
                        <div class="w-9 h-9 rounded-lg flex items-center justify-center shrink-0 shadow-sm"
                             style="background:{{ $item['bg'] }};">
                            <span class="material-symbols-outlined text-white" style="font-size:18px;">{{ $item['icon'] }}</span>
                        </div>
                        <span class="text-sm font-semibold" style="color:#1E293B;">{{ $item['label'] }}</span>
                    </a>
                    @endforeach
                </div>
            </div>

            {{-- Recent Notifications --}}
            <div class="card p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-bold text-base" style="color:#051F20;">Notifikasi Terbaru</h3>
                    <div id="dashboard-lihat-semua-container" class="{{ $pendingOrders > 0 ? '' : 'hidden' }}">
                        <button onclick="openNotificationsModal()"
                                class="text-xs font-semibold px-3 py-1 rounded-full transition-colors"
                                style="background:#DCFCE7;color:#166534;"
                                onmouseover="this.style.background='#bbf7d0';" onmouseout="this.style.background='#DCFCE7';">
                            Lihat Semua
                        </button>
                    </div>
                </div>
                <div class="space-y-3" id="dashboard-recent-notifications-list">
                    @forelse($unreadNotifications->take(4) as $notif)
                    <div class="flex items-start gap-3 p-3 rounded-xl" style="background:#F8FAFC;" id="dashboard-recent-notif-item-{{ $notif->id }}">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center shrink-0" style="background:#FEE2E2;">
                            <span class="material-symbols-outlined" style="font-size:16px;color:#DC2626;">notifications</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-semibold truncate" style="color:#051F20;">{{ $notif->title }}</p>
                            <p class="text-xs mt-0.5 line-clamp-1" style="color:#94A3B8;">{{ $notif->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-6 empty-placeholder">
                        <span class="material-symbols-outlined text-3xl" style="color:#CBD5E1;">done_all</span>
                        <p class="text-xs mt-2" style="color:#94A3B8;">Semua notifikasi sudah dibaca</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ── MODAL: Notifikasi Pesanan ── --}}
<div id="notificationsModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4"
     style="background:rgba(5,31,32,.5);backdrop-filter:blur(4px);">
    <div class="bg-white rounded-2xl w-full max-w-2xl max-h-[80vh] flex flex-col shadow-2xl"
         style="border:1px solid #E2E8F0;">
        <div class="flex items-center justify-between px-6 py-4 sticky top-0 bg-white rounded-t-2xl"
             style="border-bottom:1px solid #E2E8F0;">
            <h3 class="font-bold text-base flex items-center gap-2" style="color:#051F20;">
                <span class="material-symbols-outlined" style="color:#DC2626;">notifications</span>
                Notifikasi Pesanan
            </h3>
            <button onclick="closeNotificationsModal()" class="p-1 rounded-lg hover:bg-slate-100 transition-colors">
                <span class="material-symbols-outlined" style="color:#64748B;">close</span>
            </button>
        </div>
        <div class="overflow-y-auto flex-1 p-6 space-y-3" id="modal-notifications-list">
            @forelse($unreadNotifications as $notif)
            <div class="p-4 rounded-xl flex flex-col md:flex-row gap-4 items-start md:items-center"
                 style="background:#F8FAFC;border:1px solid #E2E8F0;" id="modal-notif-item-{{ $notif->id }}">
                <div class="flex-1 space-y-1">
                    <div class="flex items-center gap-2">
                        <div class="w-2 h-2 rounded-full" style="background:#DC2626;"></div>
                        <h4 class="text-sm font-bold" style="color:#051F20;">{{ $notif->title }}</h4>
                    </div>
                    <p class="text-xs" style="color:#94A3B8;">{{ $notif->created_at->diffForHumans() }}</p>
                    <p class="text-xs leading-relaxed mt-1" style="color:#64748B;">{{ $notif->message }}</p>
                </div>
                <button onclick="openConfirmOrderModal({{ json_encode($notif) }})"
                        class="btn-cta shrink-0 text-xs">
                    <span class="material-symbols-outlined" style="font-size:16px;">check_circle</span>
                    Konfirmasi
                </button>
            </div>
            @empty
            <div class="text-center py-12 empty-placeholder">
                <span class="material-symbols-outlined text-5xl" style="color:#CBD5E1;">notifications_none</span>
                <p class="text-sm mt-3" style="color:#94A3B8;">Tidak ada notifikasi baru</p>
            </div>
            @endforelse
        </div>
        <div class="flex justify-between items-center px-6 py-4 rounded-b-2xl"
             style="border-top:1px solid #E2E8F0;background:#F8FAFC;" id="modal-notifications-footer">
            <div id="modal-read-all-container" class="{{ count($unreadNotifications) > 0 ? '' : 'hidden' }}">
                <form action="{{ route('admin.notifications.read-all') }}" method="POST" id="readAllNotificationsForm">
                    @csrf
                    <button type="submit" class="text-xs font-semibold transition-colors" style="color:#DC2626;">
                        Tandai Semua Dibaca
                    </button>
                </form>
            </div>
            <button onclick="closeNotificationsModal()"
                    class="text-xs font-semibold px-4 py-2 rounded-lg transition-colors"
                    style="background:#F1F5F9;color:#64748B;"
                    onmouseover="this.style.background='#E2E8F0';" onmouseout="this.style.background='#F1F5F9';">
                Tutup
            </button>
        </div>
    </div>
</div>

{{-- ── MODAL: Konfirmasi & Edit Pesanan ── --}}
<div id="confirmOrderModal" class="fixed inset-0 z-[60] hidden items-center justify-center p-4"
     style="background:rgba(5,31,32,.6);backdrop-filter:blur(4px);">
    <div class="bg-white rounded-2xl w-full max-w-xl shadow-2xl" style="border:1px solid #E2E8F0;">
        <div class="flex items-center justify-between px-6 py-4" style="border-bottom:1px solid #E2E8F0;">
            <h3 class="font-bold text-base flex items-center gap-2" style="color:#051F20;">
                <span class="material-symbols-outlined" style="color:#2A7844;">edit_note</span>
                Konfirmasi & Edit Pesanan
            </h3>
            <button onclick="closeConfirmOrderModal()" class="p-1 rounded-lg hover:bg-slate-100 transition-colors">
                <span class="material-symbols-outlined" style="color:#64748B;">close</span>
            </button>
        </div>
        <form id="confirmOrderForm" method="POST" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold mb-1.5" style="color:#64748B;">Judul Notifikasi</label>
                <input type="text" name="title" id="confirm_title" required
                       class="w-full px-3 py-2.5 rounded-xl text-sm" style="border:1px solid #E2E8F0;background:#F8FAFC;color:#1E293B;">
            </div>
            <div>
                <label class="block text-xs font-semibold mb-1.5" style="color:#64748B;">Harga Jual (Rp)</label>
                <input type="number" name="harga_jual" id="confirm_harga_jual" required min="0"
                       class="w-full px-3 py-2.5 rounded-xl text-sm" style="border:1px solid #E2E8F0;background:#F8FAFC;color:#1E293B;">
            </div>
            <div>
                <label class="block text-xs font-semibold mb-1.5" style="color:#64748B;">Isi Data Pesanan</label>
                <textarea name="message" id="confirm_message" rows="6" required
                          class="w-full px-3 py-2.5 rounded-xl text-sm font-mono leading-relaxed"
                          style="border:1px solid #E2E8F0;background:#F8FAFC;color:#1E293B;"></textarea>
                <p class="text-xs mt-1" style="color:#94A3B8;">Anda dapat menyesuaikan rincian sebelum menyetujui.</p>
            </div>
            <div class="flex justify-end gap-3 pt-2" style="border-top:1px solid #E2E8F0;">
                <button type="button" onclick="closeConfirmOrderModal()"
                        class="text-sm font-semibold px-4 py-2 rounded-xl transition-colors"
                        style="background:#F1F5F9;color:#64748B;"
                        onmouseover="this.style.background='#E2E8F0';" onmouseout="this.style.background='#F1F5F9';">
                    Batal
                </button>
                <button type="submit" class="btn-cta">
                    <span class="material-symbols-outlined" style="font-size:16px;">check</span>
                    Setujui & Tandai Dibaca
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openNotificationsModal()  { const m=document.getElementById('notificationsModal'); m.classList.remove('hidden'); m.classList.add('flex'); }
    function closeNotificationsModal() { const m=document.getElementById('notificationsModal'); m.classList.add('hidden'); m.classList.remove('flex'); }
    function openConfirmOrderModal(n) {
        document.getElementById('confirmOrderForm').action = `/admin/notifications/${n.id}/confirm`;
        document.getElementById('confirmOrderForm').dataset.notifId = n.id;
        document.getElementById('confirm_title').value = n.title;
        document.getElementById('confirm_message').value = n.message;
        document.getElementById('confirm_harga_jual').value = Math.round(n.pesanan ? n.pesanan.harga_jual : 0);
        const m=document.getElementById('confirmOrderModal'); m.classList.remove('hidden'); m.classList.add('flex');
    }
    function closeConfirmOrderModal() { const m=document.getElementById('confirmOrderModal'); m.classList.add('hidden'); m.classList.remove('flex'); }

    document.addEventListener('DOMContentLoaded', function() {
        // AJAX-ify Tandai Semua Dibaca in Notifications Modal
        const readAllForm = document.getElementById('readAllNotificationsForm');
        if (readAllForm) {
            readAllForm.addEventListener('submit', function(e) {
                e.preventDefault();
                document.getElementById('global-page-loader').style.display = 'flex';
                
                fetch(readAllForm.getAttribute('action'), {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    document.getElementById('global-page-loader').style.display = 'none';
                    if (data.success) {
                        window.showToast(data.message, 'success');
                        
                        // Clear list in modal
                        const list = document.getElementById('modal-notifications-list');
                        if (list) {
                            list.innerHTML = `
                                <div class="text-center py-12 empty-placeholder">
                                    <span class="material-symbols-outlined text-5xl" style="color:#CBD5E1;">notifications_none</span>
                                    <p class="text-sm mt-3" style="color:#94A3B8;">Tidak ada notifikasi baru</p>
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
                    document.getElementById('global-page-loader').style.display = 'none';
                    window.showToast('Terjadi kesalahan jaringan.', 'error');
                });
            });
        }

        // AJAX-ify Confirm Order Form Submission
        const confirmOrderForm = document.getElementById('confirmOrderForm');
        if (confirmOrderForm) {
            confirmOrderForm.addEventListener('submit', function(e) {
                e.preventDefault();
                document.getElementById('global-page-loader').style.display = 'flex';
                
                const notifId = confirmOrderForm.dataset.notifId;
                const formData = new FormData(confirmOrderForm);
                
                fetch(confirmOrderForm.getAttribute('action'), {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    document.getElementById('global-page-loader').style.display = 'none';
                    if (data.success) {
                        window.showToast(data.message, 'success');
                        window.closeConfirmOrderModal();
                        
                        // Remove item from recent notifications list
                        const recentItem = document.getElementById(`dashboard-recent-notif-item-${notifId}`);
                        if (recentItem) recentItem.remove();
                        
                        // Remove item from notifications modal list
                        const modalItem = document.getElementById(`modal-notif-item-${notifId}`);
                        if (modalItem) modalItem.remove();
                        
                        // Remove item from navbar notification dropdown list
                        const navbarItem = document.getElementById(`navbar-notif-item-${notifId}`);
                        if (navbarItem) navbarItem.remove();
                        
                        // Update profit count card
                        const profitCount = document.getElementById('dashboard-laba-bersih-count');
                        if (profitCount) profitCount.textContent = `Rp ${data.labaBersih}`;
                        
                        // Update global counts
                        if (window.updateGlobalPendingCounts) {
                            window.updateGlobalPendingCounts(data.pendingOrders);
                        }
                        
                        // Check if lists are now empty and render empty states
                        checkEmptyNotificationStates(data.pendingOrders);
                    }
                })
                .catch(err => {
                    document.getElementById('global-page-loader').style.display = 'none';
                    window.showToast('Terjadi kesalahan jaringan.', 'error');
                });
            });
        }

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
