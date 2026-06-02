<!-- ===== ADMIN SIDEBAR — Goatin 2026 Premium ===== -->
<nav id="admin-sidebar" class="fixed left-0 top-0 h-full flex flex-col z-40 w-64 hidden md:flex transition-all duration-300"
     style="background: linear-gradient(180deg, #0E3247 0%, #0a2539 100%); box-shadow: 4px 0 32px rgba(14,50,71,.25);">

    <!-- Logo Area -->
    <div class="px-6 py-5 flex items-center justify-center border-b" style="border-color: rgba(255,255,255,.10);">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center">
            <img src="{{ asset('images/logo.png') }}" alt="Goatin Logo" class="h-14 w-auto" style="filter: brightness(0) invert(1);">
        </a>
    </div>

    <!-- Navigation Links -->
    <div class="flex-1 overflow-y-auto py-4 px-3">

        <!-- Section Label -->
        <p class="px-4 mb-2 mt-1" style="font-size:10px; font-weight:700; letter-spacing:.12em; color:rgba(255,255,255,.35); text-transform:uppercase;">Menu Utama</p>

        <ul class="space-y-1">
            <!-- Dashboard -->
            <li>
                <a href="{{ route('admin.dashboard') }}"
                   class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 group
                          {{ request()->routeIs('admin.dashboard') ? 'sidebar-link-active' : 'sidebar-link-idle' }}">
                    <span class="material-symbols-outlined text-xl {{ request()->routeIs('admin.dashboard') ? 'fill' : '' }}">dashboard</span>
                    Dashboard
                </a>
            </li>

            <!-- Accounts -->
            <li>
                <a href="{{ route('admin.accounts.index') ?? '#' }}"
                   class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 group
                          {{ request()->routeIs('admin.accounts.*') ? 'sidebar-link-active' : 'sidebar-link-idle' }}">
                    <span class="material-symbols-outlined text-xl {{ request()->routeIs('admin.accounts.*') ? 'fill' : '' }}">person</span>
                    Accounts
                </a>
            </li>

            <!-- Inventory -->
            <li>
                <a href="{{ route('admin.inventaris.index') }}"
                   class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 group
                          {{ request()->routeIs('admin.inventaris.*') ? 'sidebar-link-active' : 'sidebar-link-idle' }}">
                    <span class="material-symbols-outlined text-xl {{ request()->routeIs('admin.inventaris.*') ? 'fill' : '' }}">inventory_2</span>
                    Inventaris
                </a>
            </li>

            <!-- Medical Records -->
            <li>
                <a href="{{ route('admin.rekam-medis.index') }}"
                   class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 group
                          {{ request()->routeIs('admin.rekam-medis.*') ? 'sidebar-link-active' : 'sidebar-link-idle' }}">
                    <span class="material-symbols-outlined text-xl {{ request()->routeIs('admin.rekam-medis.*') ? 'fill' : '' }}">medical_services</span>
                    Rekam Medis
                </a>
            </li>

            <!-- Articles -->
            <li>
                <a href="{{ route('admin.artikel.index') }}"
                   class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 group
                          {{ request()->routeIs('admin.artikel.*') ? 'sidebar-link-active' : 'sidebar-link-idle' }}">
                    <span class="material-symbols-outlined text-xl {{ request()->routeIs('admin.artikel.*') ? 'fill' : '' }}">description</span>
                    Artikel
                </a>
            </li>

            <!-- Financial Reports -->
            <li>
                <a href="{{ route('admin.keuangan.index') }}"
                   class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 group
                          {{ request()->routeIs('admin.keuangan.*') ? 'sidebar-link-active' : 'sidebar-link-idle' }}">
                    <span class="material-symbols-outlined text-xl {{ request()->routeIs('admin.keuangan.*') ? 'fill' : '' }}">payments</span>
                    Keuangan
                </a>
            </li>

            <!-- Catalog -->
            <li>
                <a href="{{ route('admin.katalog.index') }}"
                   class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 group
                          {{ request()->routeIs('admin.katalog.*') ? 'sidebar-link-active' : 'sidebar-link-idle' }}">
                    <span class="material-symbols-outlined text-xl {{ request()->routeIs('admin.katalog.*') ? 'fill' : '' }}">menu_book</span>
                    Katalog
                </a>
            </li>
        </ul>
    </div>

    <!-- Bottom: User Info + Logout -->
    <div class="px-4 py-4" style="border-top: 1px solid rgba(255,255,255,.10);">
        <!-- User chip -->
        <div class="flex items-center gap-3 px-3 py-2.5 mb-2 rounded-xl" style="background:rgba(255,255,255,.07);">
            <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold shrink-0"
                 style="background:#2A7844; color:#fff;">
                {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 2)) }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-xs font-semibold truncate" style="color:#fff;">{{ auth()->user()->name ?? 'Admin' }}</p>
                <p class="text-[10px] truncate" style="color:rgba(255,255,255,.45);">Administrator</p>
            </div>
            <a href="{{ route('admin.profile') }}" style="color:rgba(255,255,255,.5);" class="hover:text-white transition-colors">
                <span class="material-symbols-outlined text-base">manage_accounts</span>
            </a>
        </div>

        <!-- Logout -->
        <form method="POST" action="{{ route('logout') }}" class="w-full">
            @csrf
            <button type="submit"
                    class="w-full flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200"
                    style="color:rgba(255,255,255,.55);"
                    onmouseover="this.style.background='rgba(220,38,38,.15)'; this.style.color='#fca5a5';"
                    onmouseout="this.style.background='transparent'; this.style.color='rgba(255,255,255,.55)';">
                <span class="material-symbols-outlined text-xl">logout</span>
                Keluar
            </button>
        </form>
    </div>
</nav>

<style>
    .sidebar-link-active {
        background: rgba(42,120,68,.25);
        color: #ffffff;
        border-left: 3px solid #2A7844;
        padding-left: calc(1rem - 3px);
    }
    .sidebar-link-idle {
        color: rgba(255,255,255,.62);
        border-left: 3px solid transparent;
        padding-left: calc(1rem - 3px);
    }
    .sidebar-link-idle:hover {
        background: rgba(255,255,255,.08);
        color: #ffffff;
    }
    .sidebar-link .material-symbols-outlined {
        font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        transition: transform .2s ease;
    }
    .sidebar-link:hover .material-symbols-outlined { transform: scale(1.1); }
</style>
