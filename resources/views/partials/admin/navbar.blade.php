@php
    $unreadNotifications = \App\Models\Notification::where('is_read', false)->latest()->get();
    $allNotifications    = \App\Models\Notification::latest()->take(5)->get();
@endphp

<!-- ===== ADMIN TOP NAVBAR — Goatin 2026 Premium ===== -->
<header class="sticky top-0 z-30 w-full flex items-center justify-between px-6 py-4 backdrop-blur-md bg-white/85 border-b border-slate-100 shadow-[0_1px_2px_rgba(0,0,0,0.02),0_4px_12px_rgba(0,0,0,0.01)] transition-all duration-300">

    <!-- Mobile: Toggle + Logo Group -->
    <div class="flex items-center gap-3 md:hidden">
        <button id="sidebarToggle" onclick="toggleSidebar()" class="p-2 rounded-xl text-slate-500 hover:bg-slate-100 hover:text-slate-700 transition-colors focus:outline-none">
            <span class="material-symbols-outlined flex items-center justify-center text-[24px]">menu</span>
        </button>
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 group">
            <img src="{{ asset('images/logo.png') }}" alt="Goatin Logo" class="h-8 w-auto">
            <span class="text-xs font-bold text-primary-dark">Goatin</span>
        </a>
    </div>

    <!-- Desktop: Page breadcrumb -->
    <div class="hidden md:flex items-center gap-2.5">
        <span class="text-slate-400 text-xs font-semibold tracking-wider uppercase">Goatin Admin</span>
        <span class="material-symbols-outlined text-slate-300 text-sm">chevron_right</span>
        <span class="text-primary-dark text-sm font-bold">@yield('title', 'Dashboard')</span>
    </div>

    <!-- Right: Notifications + Avatar -->
    <div class="flex items-center gap-4 relative">

        <!-- Notification Button -->
        <button id="notifBtn" onclick="toggleNotificationDropdown()"
                class="relative p-2.5 rounded-xl transition-all duration-200 hover:scale-105 border border-slate-100 hover:border-slate-200 bg-slate-50/50 hover:bg-slate-100 text-slate-500 hover:text-slate-700">
            <span class="material-symbols-outlined flex items-center justify-center text-[20px]">notifications</span>
            <span id="navbar-notif-dot" class="absolute top-1.5 right-1.5 w-2 h-2 rounded-full bg-red-500 ring-4 ring-white animate-pulse {{ $unreadNotifications->count() > 0 ? '' : 'hidden' }}"></span>
        </button>

        <!-- Notification Dropdown -->
        <div id="notificationDropdown"
             class="hidden absolute right-0 top-14 w-80 sm:w-[400px] rounded-2xl overflow-hidden bg-white border border-slate-100 shadow-[0_10px_30px_rgba(14,50,71,0.08),0_1px_3px_rgba(0,0,0,0.02)] z-50 transition-all">

            <!-- Header -->
            <div class="flex justify-between items-center px-5 py-4 border-b border-slate-50">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary-green" style="font-size:18px;">notifications</span>
                    <span class="font-bold text-sm text-primary-dark">Notifikasi</span>
                    <span id="navbar-notif-count-badge" class="text-[11px] font-extrabold px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 {{ $unreadNotifications->count() > 0 ? '' : 'hidden' }}">
                        {{ $unreadNotifications->count() }} Baru
                    </span>
                </div>
                @if($unreadNotifications->count() > 0)
                    <form action="{{ route('admin.notifications.read-all') }}" method="POST" class="inline" id="navbar-read-all-form">
                        @csrf
                        <button type="submit" class="text-xs font-bold text-primary-green hover:text-emerald-700 transition-colors">
                            Tandai semua dibaca
                        </button>
                    </form>
                @endif
            </div>

            <!-- Items -->
            <div class="max-h-80 overflow-y-auto divide-y divide-slate-100/60 scrollbar-thin">
                @forelse($allNotifications as $notif)
                    @php
                        $msg = $notif->message ?? '';
                        preg_match('/Pelanggan \*\*(.+?)\*\* \(WhatsApp: \*\*(.+?)\*\*\)/', $msg, $customerMatch);
                        preg_match('/ingin membeli produk \*\*(.+?)\*\*/', $msg, $produkMatch);
                        preg_match('/\*\*Harga:\*\* (.+?)(?:\n|$)/', $msg, $hargaMatch);
                        
                        $namaCustomer   = trim($customerMatch[1] ?? '');
                        $waCustomer     = trim($customerMatch[2] ?? '');
                        $namaProduk     = trim($produkMatch[1] ?? '');
                        $hargaTernak    = trim($hargaMatch[1] ?? '');
                        
                        $waDigits       = preg_replace('/[^0-9]/', '', $waCustomer);
                        $waLink         = $waDigits ? 'https://wa.me/' . $waDigits : '';
                    @endphp
                    <div class="px-5 py-4 transition-all duration-150 {{ !$notif->is_read ? 'bg-emerald-50/15' : '' }} hover:bg-slate-50 relative group/item" id="navbar-notif-item-{{ $notif->id }}">
                        <div onclick="handleNavbarNotificationClick({{ $notif->id }})" class="cursor-pointer">
                            <div class="flex justify-between items-start gap-2 mb-1">
                                <span class="text-xs font-black text-primary-dark uppercase tracking-wide group-hover/item:text-emerald-800 transition-colors">
                                    {{ $namaCustomer ?: $notif->title }}
                                </span>
                                <span class="text-[9px] font-bold text-slate-400 whitespace-nowrap">{{ $notif->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-[11px] leading-relaxed text-slate-500 font-semibold mt-1">
                                @if($namaProduk)
                                    Membeli: <span class="text-slate-800 font-bold">{{ $namaProduk }}</span>
                                    @if($hargaTernak)
                                        <br>
                                        <span class="text-amber-700 font-bold">{{ $hargaTernak }}</span>
                                    @endif
                                @else
                                    {{ \Illuminate\Support\Str::limit(strip_tags($msg), 80) }}
                                @endif
                            </p>
                        </div>
                        <div class="flex items-center gap-2 mt-2.5">
                            @if(!$notif->is_read)
                                <form action="{{ route('admin.notifications.read', $notif->id) }}" method="POST" class="navbar-mark-read-form" data-notif-id="{{ $notif->id }}">
                                    @csrf
                                    <button type="submit" class="text-[10px] font-black uppercase tracking-wider text-primary-green hover:text-emerald-800 flex items-center gap-1 transition-colors">
                                        <span class="material-symbols-outlined text-[13px] font-bold">done</span>
                                        Tandai dibaca
                                    </button>
                                </form>
                            @endif
                            @if($waLink)
                                @if(!$notif->is_read)
                                    <span class="text-slate-300 text-[10px]">•</span>
                                @endif
                                <a href="{{ $waLink }}" target="_blank" class="text-[10px] font-black uppercase tracking-wider text-emerald-600 hover:text-emerald-800 flex items-center gap-1 transition-colors">
                                    <span class="material-symbols-outlined text-[13px] font-bold">chat</span>
                                    WhatsApp
                                </a>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="px-5 py-10 text-center flex flex-col items-center justify-center">
                        <div class="w-12 h-12 rounded-full bg-slate-50 flex items-center justify-center mb-3">
                            <span class="material-symbols-outlined text-2xl text-slate-300">notifications_none</span>
                        </div>
                        <p class="text-sm font-semibold text-slate-400">Tidak ada notifikasi</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Avatar -->
        <a href="{{ route('admin.profile') }}"
           class="w-10 h-10 rounded-xl overflow-hidden flex items-center justify-center font-bold text-xs uppercase transition-all duration-300 hover:scale-105"
           style="border: 2px solid rgba(42, 120, 68, 0.2); box-shadow: 0 4px 12px rgba(42, 120, 68, 0.08);"
           title="Lihat Profil" id="admin-navbar-avatar-container">
            @if(auth()->user()->foto_profil)
                <img alt="Admin" class="w-full h-full object-cover" src="{{ asset('storage/' . auth()->user()->foto_profil) }}" id="admin-navbar-avatar-img"/>
            @else
                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-primary-green to-emerald-700 text-white font-extrabold" id="admin-navbar-avatar-placeholder">
                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                </div>
            @endif
        </a>
    </div>
</header>

<script>
    function toggleNotificationDropdown() {
        const d = document.getElementById('notificationDropdown');
        d.classList.toggle('hidden');
    }
    function handleNavbarNotificationClick(notifId) {
        const d = document.getElementById('notificationDropdown');
        if (d) d.classList.add('hidden');
        
        if (typeof window.focusNotificationInModal === 'function') {
            window.focusNotificationInModal(notifId);
        } else {
            window.location.href = `/admin/dashboard?notif_id=${notifId}`;
        }
    }
    document.addEventListener('click', function(e) {
        const d = document.getElementById('notificationDropdown');
        const btn = document.getElementById('notifBtn');
        if (!d || !btn) return;
        if (!d.contains(e.target) && !btn.contains(e.target)) {
            d.classList.add('hidden');
        }
    });
</script>
