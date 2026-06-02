@php
    $unreadNotifications = \App\Models\Notification::where('is_read', false)->latest()->get();
    $allNotifications    = \App\Models\Notification::latest()->take(5)->get();
@endphp

<!-- ===== ADMIN TOP NAVBAR — Goatin 2026 Premium ===== -->
<header class="sticky top-0 z-30 w-full flex items-center justify-between px-6 py-3"
        style="background:#ffffff; border-bottom:1px solid #E2E8F0; box-shadow:0 1px 0 #E2E8F0;">

    <!-- Mobile: Logo -->
    <a href="{{ route('admin.dashboard') }}" class="md:hidden flex items-center">
        <img src="{{ asset('images/logo.png') }}" alt="Goatin Logo" class="h-10 w-auto">
    </a>

    <!-- Desktop: Page breadcrumb / search placeholder -->
    <div class="hidden md:flex items-center gap-2">
        <span class="text-text-muted text-sm font-medium">Goatin Admin</span>
        <span class="text-border-subtle">/</span>
        <span class="text-text-heading text-sm font-semibold">@yield('title', 'Dashboard')</span>
    </div>

    <!-- Right: Notifications + Avatar -->
    <div class="flex items-center gap-3 relative">

        <!-- Notification Button -->
        <button id="notifBtn" onclick="toggleNotificationDropdown()"
                class="relative p-2 rounded-xl transition-colors"
                style="color:#64748B; background:#F8FAFC; border:1px solid #E2E8F0;"
                onmouseover="this.style.background='#EEF2F7';" onmouseout="this.style.background='#F8FAFC';">
            <span class="material-symbols-outlined" style="font-size:20px;">notifications</span>
            @if($unreadNotifications->count() > 0)
                <span class="absolute top-1.5 right-1.5 w-2 h-2 rounded-full animate-pulse"
                      style="background:#DC2626;"></span>
            @endif
        </button>

        <!-- Notification Dropdown -->
        <div id="notificationDropdown"
             class="hidden absolute right-0 top-12 w-80 sm:w-96 rounded-xl overflow-hidden"
             style="background:#fff; border:1px solid #E2E8F0; box-shadow:0 8px 32px rgba(14,50,71,.15); z-index:100;">

            <!-- Header -->
            <div class="flex justify-between items-center px-4 py-3"
                 style="border-bottom:1px solid #E2E8F0;">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-base" style="color:#2A7844;">notifications</span>
                    <span class="font-semibold text-sm" style="color:#0E3247;">Notifikasi</span>
                    @if($unreadNotifications->count() > 0)
                        <span class="text-xs font-bold px-2 py-0.5 rounded-full" style="background:#DCFCE7;color:#166534;">
                            {{ $unreadNotifications->count() }}
                        </span>
                    @endif
                </div>
                @if($unreadNotifications->count() > 0)
                    <form action="{{ route('admin.notifications.read-all') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="text-xs font-semibold transition-colors"
                                style="color:#2A7844;" onmouseover="this.style.color='#1e5c33';" onmouseout="this.style.color='#2A7844';">
                            Tandai semua dibaca
                        </button>
                    </form>
                @endif
            </div>

            <!-- Items -->
            <div class="max-h-80 overflow-y-auto divide-y" style="divide-color:#F1F5F9;">
                @forelse($allNotifications as $notif)
                    <div class="px-4 py-3 transition-colors {{ !$notif->is_read ? '' : '' }}"
                         style="{{ !$notif->is_read ? 'background:#f0faf3;' : '' }}"
                         onmouseover="this.style.background='#F8FAFC';" onmouseout="this.style.background='{{ !$notif->is_read ? '#f0faf3' : '' }}';">
                        <div class="flex justify-between items-start gap-2 mb-1">
                            <span class="text-xs font-bold" style="color:#0E3247;">{{ $notif->title }}</span>
                            <span class="text-[10px] whitespace-nowrap" style="color:#94A3B8;">{{ $notif->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-xs leading-relaxed" style="color:#64748B;">
                            {!! preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', nl2br(e($notif->message))) !!}
                        </p>
                        @if(!$notif->is_read)
                            <form action="{{ route('admin.notifications.read', $notif->id) }}" method="POST" class="mt-2">
                                @csrf
                                <button type="submit" class="text-[11px] font-semibold flex items-center gap-1 transition-colors"
                                        style="color:#2A7844;">
                                    <span class="material-symbols-outlined" style="font-size:13px;">done</span>
                                    Tandai dibaca
                                </button>
                            </form>
                        @endif
                    </div>
                @empty
                    <div class="px-4 py-8 text-center">
                        <span class="material-symbols-outlined text-4xl mb-2 block" style="color:#CBD5E1;">notifications_none</span>
                        <p class="text-sm" style="color:#94A3B8;">Tidak ada notifikasi</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Avatar -->
        <a href="{{ route('admin.profile') }}"
           class="w-9 h-9 rounded-full overflow-hidden flex items-center justify-center font-bold text-xs uppercase transition-all"
           style="border: 2px solid #2A7844; box-shadow: 0 0 0 2px rgba(42,120,68,.15);"
           title="Lihat Profil">
            @if(auth()->user()->foto_profil)
                <img alt="Admin" class="w-full h-full object-cover" src="{{ asset('storage/' . auth()->user()->foto_profil) }}"/>
            @else
                <div class="w-full h-full flex items-center justify-center"
                     style="background:#2A7844; color:#fff;">
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
