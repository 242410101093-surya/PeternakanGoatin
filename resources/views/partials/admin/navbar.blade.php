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
            <div class="max-h-80 overflow-y-auto divide-y divide-slate-50 scrollbar-thin">
                @forelse($allNotifications as $notif)
                    <div class="px-5 py-4 transition-all duration-150 {{ !$notif->is_read ? 'bg-emerald-50/20' : '' }} hover:bg-slate-50/50" id="navbar-notif-item-{{ $notif->id }}">
                        <div class="flex justify-between items-start gap-2 mb-1">
                            <span class="text-xs font-bold text-primary-dark leading-tight">{{ $notif->title }}</span>
                            <span class="text-[10px] font-semibold text-slate-400 whitespace-nowrap">{{ $notif->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-xs leading-relaxed text-slate-500 mt-1">
                            {!! preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', nl2br(e($notif->message))) !!}
                        </p>
                        @if(!$notif->is_read)
                            <form action="{{ route('admin.notifications.read', $notif->id) }}" method="POST" class="mt-2.5 navbar-mark-read-form" data-notif-id="{{ $notif->id }}">
                                @csrf
                                <button type="submit" class="text-[11px] font-bold text-primary-green hover:text-emerald-800 flex items-center gap-1 transition-colors">
                                    <span class="material-symbols-outlined text-[14px]">done</span>
                                    Tandai dibaca
                                </button>
                            </form>
                        @endif
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
    document.addEventListener('click', function(e) {
        const d = document.getElementById('notificationDropdown');
        const btn = document.getElementById('notifBtn');
        if (!d || !btn) return;
        if (!d.contains(e.target) && !btn.contains(e.target)) {
            d.classList.add('hidden');
        }
    });
</script>
