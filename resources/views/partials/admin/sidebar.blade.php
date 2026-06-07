<!-- ===== ADMIN SIDEBAR — Goatin 2026 Premium ===== -->
<nav id="admin-sidebar" class="fixed left-0 top-0 h-full flex flex-col z-50 w-64 transition-transform duration-300 -translate-x-full md:translate-x-0 sidebar-modern">

    <!-- Logo Area -->
    <div class="px-6 py-6 flex items-center justify-center logo-container">
        <a href="{{ route('admin.dashboard') }}" class="flex flex-col items-center gap-1 group">
            <img src="{{ asset('images/logo.png') }}" alt="Goatin Logo" class="h-12 w-auto logo-img transition-transform duration-300 group-hover:scale-105" style="filter: brightness(0) invert(1);">
        </a>
    </div>

    <!-- Navigation Links -->
    <div class="flex-1 overflow-y-auto py-6 px-4 space-y-6 scrollbar-thin">

        <!-- Section: Main Menu -->
        <div>
            <p class="px-3 mb-3 text-[10px] font-extrabold tracking-[0.15em] text-slate-400/50 uppercase">Menu Utama</p>
            <ul class="space-y-1.5">
                <!-- Dashboard -->
                <li>
                    <a href="{{ route('admin.dashboard') }}"
                       class="sidebar-link flex items-center gap-3.5 px-4 py-3 rounded-xl text-sm font-semibold transition-all duration-200 group
                              {{ request()->routeIs('admin.dashboard') ? 'sidebar-link-active' : 'sidebar-link-idle' }}">
                        <span class="material-symbols-outlined text-lg transition-transform duration-200 group-hover:scale-110 {{ request()->routeIs('admin.dashboard') ? 'fill text-white' : 'text-slate-400' }}">dashboard</span>
                        <span>Dashboard</span>
                        @if(request()->routeIs('admin.dashboard'))
                            <span class="ml-auto w-1.5 h-1.5 rounded-full bg-white shadow-[0_0_8px_#fff]"></span>
                        @endif
                    </a>
                </li>

                <!-- Accounts -->
                <li>
                    <a href="{{ route('admin.accounts.index') ?? '#' }}"
                       class="sidebar-link flex items-center gap-3.5 px-4 py-3 rounded-xl text-sm font-semibold transition-all duration-200 group
                              {{ request()->routeIs('admin.accounts.*') ? 'sidebar-link-active' : 'sidebar-link-idle' }}">
                        <span class="material-symbols-outlined text-lg transition-transform duration-200 group-hover:scale-110 {{ request()->routeIs('admin.accounts.*') ? 'fill text-white' : 'text-slate-400' }}">person</span>
                        <span>Accounts</span>
                        @if(request()->routeIs('admin.accounts.*'))
                            <span class="ml-auto w-1.5 h-1.5 rounded-full bg-white shadow-[0_0_8px_#fff]"></span>
                        @endif
                    </a>
                </li>

                <!-- Inventory -->
                <li>
                    <a href="{{ route('admin.inventaris.index') }}"
                       class="sidebar-link flex items-center gap-3.5 px-4 py-3 rounded-xl text-sm font-semibold transition-all duration-200 group
                              {{ request()->routeIs('admin.inventaris.*') ? 'sidebar-link-active' : 'sidebar-link-idle' }}">
                        <span class="material-symbols-outlined text-lg transition-transform duration-200 group-hover:scale-110 {{ request()->routeIs('admin.inventaris.*') ? 'fill text-white' : 'text-slate-400' }}">inventory_2</span>
                        <span>Inventaris</span>
                        @if(request()->routeIs('admin.inventaris.*'))
                            <span class="ml-auto w-1.5 h-1.5 rounded-full bg-white shadow-[0_0_8px_#fff]"></span>
                        @endif
                    </a>
                </li>

                <!-- Medical Records -->
                <li>
                    <a href="{{ route('admin.rekam-medis.index') }}"
                       class="sidebar-link flex items-center gap-3.5 px-4 py-3 rounded-xl text-sm font-semibold transition-all duration-200 group
                              {{ request()->routeIs('admin.rekam-medis.*') ? 'sidebar-link-active' : 'sidebar-link-idle' }}">
                        <span class="material-symbols-outlined text-lg transition-transform duration-200 group-hover:scale-110 {{ request()->routeIs('admin.rekam-medis.*') ? 'fill text-white' : 'text-slate-400' }}">medical_services</span>
                        <span>Rekam Medis</span>
                        @if(request()->routeIs('admin.rekam-medis.*'))
                            <span class="ml-auto w-1.5 h-1.5 rounded-full bg-white shadow-[0_0_8px_#fff]"></span>
                        @endif
                    </a>
                </li>

                <!-- Articles -->
                <li>
                    <a href="{{ route('admin.artikel.index') }}"
                       class="sidebar-link flex items-center gap-3.5 px-4 py-3 rounded-xl text-sm font-semibold transition-all duration-200 group
                              {{ request()->routeIs('admin.artikel.*') ? 'sidebar-link-active' : 'sidebar-link-idle' }}">
                        <span class="material-symbols-outlined text-lg transition-transform duration-200 group-hover:scale-110 {{ request()->routeIs('admin.artikel.*') ? 'fill text-white' : 'text-slate-400' }}">description</span>
                        <span>Artikel</span>
                        @if(request()->routeIs('admin.artikel.*'))
                            <span class="ml-auto w-1.5 h-1.5 rounded-full bg-white shadow-[0_0_8px_#fff]"></span>
                        @endif
                    </a>
                </li>

                <!-- Financial Reports -->
                <li>
                    <a href="{{ route('admin.keuangan.index') }}"
                       class="sidebar-link flex items-center gap-3.5 px-4 py-3 rounded-xl text-sm font-semibold transition-all duration-200 group
                              {{ request()->routeIs('admin.keuangan.*') ? 'sidebar-link-active' : 'sidebar-link-idle' }}">
                        <span class="material-symbols-outlined text-lg transition-transform duration-200 group-hover:scale-110 {{ request()->routeIs('admin.keuangan.*') ? 'fill text-white' : 'text-slate-400' }}">payments</span>
                        <span>Keuangan</span>
                        @if(request()->routeIs('admin.keuangan.*'))
                            <span class="ml-auto w-1.5 h-1.5 rounded-full bg-white shadow-[0_0_8px_#fff]"></span>
                        @endif
                    </a>
                </li>

                <!-- Catalog -->
                <li>
                    <a href="{{ route('admin.katalog.index') }}"
                       class="sidebar-link flex items-center gap-3.5 px-4 py-3 rounded-xl text-sm font-semibold transition-all duration-200 group
                              {{ request()->routeIs('admin.katalog.*') ? 'sidebar-link-active' : 'sidebar-link-idle' }}">
                        <span class="material-symbols-outlined text-lg transition-transform duration-200 group-hover:scale-110 {{ request()->routeIs('admin.katalog.*') ? 'fill text-white' : 'text-slate-400' }}">menu_book</span>
                        <span>Katalog</span>
                        @if(request()->routeIs('admin.katalog.*'))
                            <span class="ml-auto w-1.5 h-1.5 rounded-full bg-white shadow-[0_0_8px_#fff]"></span>
                        @endif
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Bottom: User Info + Logout -->
    <div class="p-4 space-y-3 sidebar-footer">
        <!-- Logout Button -->
        <form method="POST" action="{{ route('logout') }}" class="w-full">
            @csrf
            <button type="submit"
                    class="w-full flex items-center gap-3 px-4.5 py-3 rounded-xl text-sm font-semibold transition-all duration-300 logout-btn">
                <span class="material-symbols-outlined text-lg">logout</span>
                <span>Keluar</span>
            </button>
        </form>
    </div>
</nav>

<style>
    /* Premium Modern Sidebar styles */
    .sidebar-modern {
        background: linear-gradient(185deg, #051F20 0%, #0B2B26 60%, #163832 100%);
        border-right: 1px solid rgba(255, 255, 255, 0.05);
        box-shadow: 10px 0 40px rgba(5, 31, 32, 0.35);
    }
    
    .logo-container {
        border-bottom: 1px solid rgba(255, 255, 255, 0.04);
        position: relative;
    }
    .logo-container::after {
        content: '';
        position: absolute;
        bottom: -1px;
        left: 20%;
        width: 60%;
        height: 1px;
        background: linear-gradient(90deg, transparent, rgba(42, 120, 68, 0.4), transparent);
    }

    .sidebar-link {
        position: relative;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .sidebar-link-idle {
        color: #94A3B8;
    }
    .sidebar-link-idle:hover {
        background: rgba(255, 255, 255, 0.03);
        color: #FFFFFF;
        padding-left: 1.25rem;
    }
    .sidebar-link-idle:hover .material-symbols-outlined {
        color: #FFFFFF;
    }

    .sidebar-link-active {
        background: linear-gradient(135deg, rgba(42, 120, 68, 0.8) 0%, rgba(30, 92, 51, 0.9) 100%);
        color: #FFFFFF;
        box-shadow: 0 4px 20px rgba(42, 120, 68, 0.3), inset 0 1px 1px rgba(255, 255, 255, 0.15);
        border: 1px solid rgba(255, 255, 255, 0.05);
    }

    /* Profile widget */
    .profile-card {
        background: rgba(255, 255, 255, 0.02);
        border: 1px solid rgba(255, 255, 255, 0.04);
        box-shadow: 0 4px 24px rgba(0, 0, 0, 0.15);
    }
    .profile-card:hover {
        background: rgba(255, 255, 255, 0.04);
        border-color: rgba(255, 255, 255, 0.08);
    }
    .profile-avatar {
        background: linear-gradient(135deg, #2A7844 0%, #1e5c33 100%);
        color: #FFFFFF;
        box-shadow: 0 2px 10px rgba(42, 120, 68, 0.25);
    }

    /* Logout Button styling */
    .logout-btn {
        color: #94A3B8;
        background: transparent;
        border: 1px solid transparent;
    }
    .logout-btn:hover {
        background: rgba(220, 38, 38, 0.08);
        border-color: rgba(220, 38, 38, 0.15);
        color: #FCA5A5;
        box-shadow: 0 4px 15px rgba(220, 38, 38, 0.05);
    }

    /* Custom thin scrollbar */
    .scrollbar-thin::-webkit-scrollbar {
        width: 4px;
    }
    .scrollbar-thin::-webkit-scrollbar-track {
        background: transparent;
    }
    .scrollbar-thin::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.08);
        border-radius: 999px;
    }
    .scrollbar-thin::-webkit-scrollbar-thumb:hover {
        background: rgba(255, 255, 255, 0.15);
    }

    .sidebar-footer {
        border-top: 1px solid rgba(255, 255, 255, 0.04);
    }
</style>

