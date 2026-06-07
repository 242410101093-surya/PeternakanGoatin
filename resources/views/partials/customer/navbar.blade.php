<!-- ===== CUSTOMER NAV FLOATING CAPSULE — Goatin 2026 Premium ===== -->
<nav class="fixed top-4 left-1/2 -translate-x-1/2 w-[calc(100%-32px)] max-w-[1200px] z-50 transition-all duration-300" id="floating-navbar">
    <div class="px-6 py-3.5 rounded-2xl border flex items-center justify-between shadow-lg"
         style="background: rgba(5, 31, 32, 0.85); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); border-color: rgba(35, 83, 71, 0.3); box-shadow: 0 8px 32px rgba(5, 31, 32, 0.25);">

        <!-- Brand Logo -->
        <a href="{{ route('customer.dashboard') }}" class="flex items-center shrink-0 hover:opacity-90 transition-opacity">
            <img src="{{ asset('images/logo.png') }}" alt="Goatin Logo" class="h-10 w-auto" style="filter: brightness(0) invert(1);">
        </a>

        <!-- Desktop Navigation Capsule Link -->
        <div class="hidden md:flex items-center gap-1.5 p-1 rounded-xl" style="background: rgba(255, 255, 255, 0.08);">
            @php
                $navLinks = [
                    ['route' => 'customer.produk',      'label' => 'Katalog Produk', 'icon' => 'storefront'],
                    ['route' => 'customer.monitoring',  'label' => 'Monitoring Pesanan',  'icon' => 'analytics'],
                    ['route' => 'customer.dashboard',   'label' => 'Artikel & Edukasi',   'icon' => 'menu_book'],
                ];
            @endphp
            @foreach($navLinks as $link)
                @php 
                    $isActive = false;
                    if ($link['route'] === 'customer.dashboard') {
                        $isActive = request()->routeIs('customer.dashboard') || request()->routeIs('customer.artikel.show');
                    } else {
                        $isActive = request()->routeIs($link['route']);
                    }
                @endphp
                
                @if($isActive)
                    <a href="{{ route($link['route']) }}"
                       class="flex items-center gap-1.5 px-4 py-2 rounded-lg text-xs font-bold text-white transition-all shadow-md"
                       style="background: linear-gradient(135deg, #2A7844 0%, #1e5c33 100%); box-shadow: 0 4px 12px rgba(42, 120, 68, 0.25);">
                        <span class="material-symbols-outlined" style="font-size: 16px;">{{ $link['icon'] }}</span>
                        {{ $link['label'] }}
                    </a>
                @else
                    <a href="{{ route($link['route']) }}"
                       class="flex items-center gap-1.5 px-4 py-2 rounded-lg text-xs font-semibold transition-all duration-200"
                       style="color: #DAF1DE;"
                       onmouseover="this.style.color='#051F20'; this.style.background='#DAF1DE'; this.style.boxShadow='0 2px 8px rgba(5, 31, 32, 0.1)';"
                       onmouseout="this.style.color='#DAF1DE'; this.style.background='transparent'; this.style.boxShadow='none';">
                        <span class="material-symbols-outlined" style="font-size: 16px;">{{ $link['icon'] }}</span>
                        {{ $link['label'] }}
                    </a>
                @endif
            @endforeach
        </div>

        <!-- Right Side Controls -->
        <div class="flex items-center gap-2.5">
            <!-- Logout Button -->
            <form action="{{ route('logout') }}" method="POST" class="hidden sm:block">
                @csrf
                <button type="submit"
                        class="flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold transition-all border"
                        style="color: #DAF1DE; border-color: rgba(218, 241, 222, 0.25);"
                        onmouseover="this.style.color='#DC2626'; this.style.borderColor='rgba(239, 68, 68, 0.4)'; this.style.background='rgba(254, 242, 242, 0.15)';"
                        onmouseout="this.style.color='#DAF1DE'; this.style.borderColor='rgba(218, 241, 222, 0.25)'; this.style.background='transparent';">
                    <span class="material-symbols-outlined" style="font-size: 16px;">logout</span>
                    Keluar
                </button>
            </form>

            <!-- User Avatar Quick Link -->
            <a href="{{ route('customer.profile') }}" class="w-9 h-9 rounded-full overflow-hidden border-2 border-emerald-500 hover:border-emerald-600 transition-colors">
                @if(auth()->user()->foto_profil)
                    <img src="{{ asset('storage/' . auth()->user()->foto_profil) }}" alt="{{ auth()->user()->name }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full bg-emerald-50 flex items-center justify-center text-emerald-800 text-xs font-bold uppercase">
                        {{ substr(auth()->user()->name, 0, 2) }}
                    </div>
                @endif
            </a>

            <!-- Mobile Hamburger Menu Button -->
            <button class="md:hidden p-2 rounded-xl border flex items-center justify-center transition-colors"
                    style="color: #DAF1DE; border-color: rgba(218, 241, 222, 0.25);"
                    onclick="document.getElementById('mobile-capsule-menu').classList.toggle('hidden')">
                <span class="material-symbols-outlined" style="font-size: 20px;">menu</span>
            </button>
        </div>
    </div>

    <!-- Mobile Dropdown Menu Inside Floating Box -->
    <div id="mobile-capsule-menu" class="hidden md:hidden mt-2 p-3 rounded-2xl border space-y-1 shadow-xl animate-fade-in"
         style="background: rgba(5, 31, 32, 0.95); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); border-color: rgba(35, 83, 71, 0.3);">
        @foreach($navLinks as $link)
            @php 
                $isActive = false;
                if ($link['route'] === 'customer.dashboard') {
                    $isActive = request()->routeIs('customer.dashboard') || request()->routeIs('customer.artikel.show');
                } else {
                    $isActive = request()->routeIs($link['route']);
                }
            @endphp
            <a href="{{ route($link['route']) }}" 
               class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-xs font-semibold {{ $isActive ? 'text-white' : 'text-slate-300' }}"
               style="{{ $isActive ? 'background: linear-gradient(135deg, #2A7844 0%, #1e5c33 100%);' : '' }}">
                <span class="material-symbols-outlined" style="font-size: 18px;">{{ $link['icon'] }}</span>
                {{ $link['label'] }}
            </a>
        @endforeach
        
        <div class="h-px bg-slate-100 my-2"></div>
        
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 rounded-xl text-xs font-bold text-red-400 hover:bg-red-500/10 transition-colors">
                <span class="material-symbols-outlined" style="font-size: 18px;">logout</span>
                Keluar Aplikasi
            </button>
        </form>
    </div>
</nav>

<script>
    // Add border and background glow on scroll for capsule navbar
    window.addEventListener('scroll', function() {
        const nav = document.getElementById('floating-navbar');
        if (window.scrollY > 20) {
            nav.classList.add('top-2');
            nav.classList.remove('top-4');
        } else {
            nav.classList.add('top-4');
            nav.classList.remove('top-2');
        }
    });
</script>
