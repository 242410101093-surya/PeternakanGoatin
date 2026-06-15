<style>
    /* ── Solid White Nav ── */
    .glass-nav {
        background: #ffffff;
        border: 1px solid rgba(5, 31, 32, 0.08);
        box-shadow: 0 4px 24px rgba(5, 31, 32, 0.08);
    }
    /* ── Nav Link Base ── */
    .nav-link {
        position: relative;
        transition: color 0.25s ease;
        z-index: 1;
    }
    /* ── Sliding Pill Indicator ── */
    .nav-links-wrapper {
        position: relative;
    }
    .nav-active-pill {
        position: absolute;
        bottom: -8px;
        left: 0;
        height: 3px;
        border-radius: 9999px;
        background: linear-gradient(90deg, #2A7844, #37a05e);
        box-shadow: 0 2px 8px rgba(42, 120, 68, 0.45);
        transition: left 0.35s cubic-bezier(0.4, 0, 0.2, 1),
                    width 0.35s cubic-bezier(0.4, 0, 0.2, 1),
                    opacity 0.25s ease;
        opacity: 0;
        pointer-events: none;
    }
    .nav-active-pill.visible {
        opacity: 1;
    }
    .nav-link.active {
        color: #2A7844 !important;
    }
    /* ── Scroll Progress Bar ── */
    .scroll-progress {
        position: fixed;
        top: 0;
        left: 0;
        height: 3px;
        background: linear-gradient(90deg, #2A7844 0%, #235347 100%);
        width: 0%;
        z-index: 9999;
        transition: width 0.1s ease-out;
    }
    /* ── Animated Auth CTA Button ── */
    @keyframes ctaGradientShift {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }
    .cta-animate-shift {
        background: linear-gradient(-45deg, #2A7844, #235347, #163832, #2A7844) !important;
        background-size: 300% 300% !important;
        animation: ctaGradientShift 5s ease infinite !important;
        transition: all 0.3s ease-in-out !important;
    }
</style>

<!-- Scroll Progress Indicator -->
<div class="scroll-progress" id="scrollIndicator"></div>

<!-- NAVBAR -->
<nav class="fixed top-4 left-1/2 -translate-x-1/2 w-[calc(100%-32px)] max-w-7xl z-50 glass-nav transition-all duration-300 py-3 rounded-2xl" id="mainNavbar">
    <div class="max-w-7xl mx-auto px-6">
        <div class="flex items-center justify-between h-12">
            
            <!-- Logo & Brand -->
            <a href="{{ request()->routeIs('landing') ? '#home' : route('landing').'#home' }}" class="flex items-center group transition-transform duration-200 hover:scale-[1.02]">
                <img src="{{ asset('images/logo-auth.png') }}" alt="Goatin Logo" class="h-9 sm:h-11 w-auto object-contain">
            </a>

            <!-- Navigation Links (Desktop) -->
            <div class="hidden md:flex items-center gap-8 nav-links-wrapper" id="navLinksWrapper">
                <a href="{{ request()->routeIs('landing') ? '#home' : route('landing').'#home' }}" data-section="home" class="nav-link text-xs uppercase tracking-wider font-extrabold text-primary-dark/80 hover:text-goatin-green transition-colors">Beranda</a>
                <a href="{{ request()->routeIs('landing') ? '#features' : route('landing').'#features' }}" data-section="features" class="nav-link text-xs uppercase tracking-wider font-extrabold text-primary-dark/80 hover:text-goatin-green transition-colors">Keunggulan</a>
                <a href="{{ request()->routeIs('landing') ? '#catalog' : route('landing').'#catalog' }}" data-section="catalog" class="nav-link text-xs uppercase tracking-wider font-extrabold text-primary-dark/80 hover:text-goatin-green transition-colors">Katalog</a>
                <a href="{{ request()->routeIs('landing') ? '#testimonials' : route('landing').'#testimonials' }}" data-section="testimonials" class="nav-link text-xs uppercase tracking-wider font-extrabold text-primary-dark/80 hover:text-goatin-green transition-colors">Testimoni</a>
                <a href="{{ request()->routeIs('landing') ? '#contact' : route('landing').'#contact' }}" data-section="contact" class="nav-link text-xs uppercase tracking-wider font-extrabold text-primary-dark/80 hover:text-goatin-green transition-colors">Kontak</a>
                <!-- Sliding Active Pill -->
                <div class="nav-active-pill" id="navActivePill"></div>
            </div>

            <!-- Auth Buttons / CTA (Desktop) -->
            <div class="hidden md:flex items-center gap-3">
                @auth
                    {{-- Tombol Keluar --}}
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit"
                           class="flex items-center gap-1.5 py-2.5 px-5 rounded-full text-xs font-extrabold text-slate-500 border border-slate-200 hover:border-red-300 hover:text-red-500 uppercase tracking-widest transition-all duration-200 shadow-sm hover:shadow-md active:scale-[0.98] cursor-pointer bg-white">
                            <span class="material-symbols-outlined text-sm">logout</span>
                            <span>Keluar</span>
                        </button>
                    </form>
                    {{-- Tombol Ke Dashboard --}}
                    <a href="{{ route('dashboard') }}" 
                       class="flex items-center gap-2 py-2.5 px-6 rounded-full text-xs font-extrabold text-white uppercase tracking-widest transition-all duration-300 bg-goatin-green hover:bg-accent-teal shadow-md hover:shadow-lg active:scale-[0.98] group">
                        <span>Ke Dashboard</span>
                        <span class="material-symbols-outlined text-sm transition-transform duration-200 group-hover:translate-x-1">dashboard</span>
                    </a>
                @else
                    @if(request()->routeIs('login'))
                        {{-- Halaman Login: Sign In aktif --}}
                        <a href="{{ route('login') }}" 
                           class="flex items-center gap-1.5 py-2.5 px-6 rounded-full text-xs font-extrabold text-white uppercase tracking-widest transition-all duration-300 cta-animate-shift shadow-md hover:shadow-lg active:scale-[0.98] group">
                            <span class="material-symbols-outlined text-sm">login</span>
                            <span>Login</span>
                        </a>
                        <a href="{{ route('register') }}" 
                           class="flex items-center gap-1.5 py-2.5 px-5 rounded-full text-xs font-extrabold text-primary-dark border border-slate-200 hover:border-goatin-green hover:text-goatin-green uppercase tracking-widest transition-all duration-200 shadow-sm hover:shadow-md active:scale-[0.98]">
                            <span class="material-symbols-outlined text-sm">person_add</span>
                            <span>Register</span>
                        </a>
                    @elseif(request()->routeIs('register'))
                        {{-- Halaman Register: Sign Up aktif --}}
                        <a href="{{ route('login') }}" 
                           class="flex items-center gap-1.5 py-2.5 px-5 rounded-full text-xs font-extrabold text-primary-dark border border-slate-200 hover:border-goatin-green hover:text-goatin-green uppercase tracking-widest transition-all duration-200 shadow-sm hover:shadow-md active:scale-[0.98]">
                            <span class="material-symbols-outlined text-sm">login</span>
                            <span>Login</span>
                        </a>
                        <a href="{{ route('register') }}" 
                           class="flex items-center gap-1.5 py-2.5 px-6 rounded-full text-xs font-extrabold text-white uppercase tracking-widest transition-all duration-300 cta-animate-shift shadow-md hover:shadow-lg active:scale-[0.98] group">
                            <span class="material-symbols-outlined text-sm">person_add</span>
                            <span>Register</span>
                        </a>
                    @else
                        {{-- Halaman lain (Landing, dll): tampilkan keduanya --}}
                        <a href="{{ route('login') }}" 
                           class="flex items-center gap-1.5 py-2.5 px-5 rounded-full text-xs font-extrabold text-primary-dark border border-slate-200 hover:border-goatin-green hover:text-goatin-green uppercase tracking-widest transition-all duration-200 shadow-sm hover:shadow-md active:scale-[0.98]">
                            <span class="material-symbols-outlined text-sm">login</span>
                            <span>Login</span>
                        </a>
                        <a href="{{ route('register') }}" 
                           class="flex items-center gap-1.5 py-2.5 px-6 rounded-full text-xs font-extrabold text-white uppercase tracking-widest transition-all duration-300 bg-goatin-green hover:bg-accent-teal shadow-md hover:shadow-lg active:scale-[0.98] group">
                            <span class="material-symbols-outlined text-sm">person_add</span>
                            <span>Register</span>
                        </a>
                    @endif
                @endauth
            </div>

            <!-- Mobile Menu Button -->
            <div class="md:hidden">
                <button type="button" class="text-primary-dark hover:text-goatin-green focus:outline-none p-2 rounded-lg flex items-center justify-center" id="mobileMenuBtn">
                    <span class="material-symbols-outlined text-[28px] leading-none" id="mobileMenuIcon">menu</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu Container -->
    <div class="hidden md:hidden absolute top-full left-0 right-0 bg-white backdrop-blur-md border border-slate-100/50 shadow-xl rounded-2xl mt-2 mx-4 p-4" id="mobileMenu">
        <div class="space-y-3 flex flex-col font-extrabold text-xs uppercase tracking-wider">
            <a href="{{ request()->routeIs('landing') ? '#home' : route('landing').'#home' }}" class="mobile-nav-link py-2.5 px-4 rounded-xl hover:bg-slate-50 hover:text-goatin-green text-primary-dark">Beranda</a>
            <a href="{{ request()->routeIs('landing') ? '#features' : route('landing').'#features' }}" class="mobile-nav-link py-2.5 px-4 rounded-xl hover:bg-slate-50 hover:text-goatin-green text-primary-dark">Keunggulan</a>
            <a href="{{ request()->routeIs('landing') ? '#catalog' : route('landing').'#catalog' }}" class="mobile-nav-link py-2.5 px-4 rounded-xl hover:bg-slate-50 hover:text-goatin-green text-primary-dark">Katalog</a>
            <a href="{{ request()->routeIs('landing') ? '#testimonials' : route('landing').'#testimonials' }}" class="mobile-nav-link py-2.5 px-4 rounded-xl hover:bg-slate-50 hover:text-goatin-green text-primary-dark">Testimoni</a>
            <a href="{{ request()->routeIs('landing') ? '#contact' : route('landing').'#contact' }}" class="mobile-nav-link py-2.5 px-4 rounded-xl hover:bg-slate-50 hover:text-goatin-green text-primary-dark">Kontak</a>
            
            <div class="border-t border-slate-100 pt-3 flex flex-col gap-2">
                @auth
                    <a href="{{ route('dashboard') }}" 
                       class="w-full flex justify-center items-center gap-2 py-3 px-6 rounded-full text-white bg-goatin-green hover:bg-accent-teal text-xs">
                        <span>Ke Dashboard</span>
                        <span class="material-symbols-outlined text-sm">dashboard</span>
                    </a>
                @else
                    @if(request()->routeIs('login'))
                        <a href="{{ route('login') }}" 
                           class="w-full flex justify-center py-3 rounded-full text-white cta-animate-shift text-xs shadow-sm">
                            Sign In
                        </a>
                        <a href="{{ route('register') }}" 
                           class="w-full flex justify-center py-3 border border-slate-200 rounded-full text-primary-dark hover:bg-slate-50 text-xs">
                            Sign Up
                        </a>
                    @elseif(request()->routeIs('register'))
                        <a href="{{ route('login') }}" 
                           class="w-full flex justify-center py-3 border border-slate-200 rounded-full text-primary-dark hover:bg-slate-50 text-xs">
                            Sign In
                        </a>
                        <a href="{{ route('register') }}" 
                           class="w-full flex justify-center py-3 rounded-full text-white cta-animate-shift text-xs shadow-sm">
                            Sign Up
                        </a>
                    @else
                        <a href="{{ route('login') }}" 
                           class="w-full flex justify-center items-center gap-2 py-3 border border-slate-200 rounded-full text-primary-dark hover:bg-slate-50 text-xs font-extrabold uppercase tracking-wider">
                             <span class="material-symbols-outlined text-sm">login</span>
                             Masuk
                         </a>
                         <a href="{{ route('register') }}" 
                            class="w-full flex justify-center items-center gap-2 py-3 rounded-full text-white bg-goatin-green hover:bg-accent-teal text-xs font-extrabold uppercase tracking-wider shadow-sm">
                             <span class="material-symbols-outlined text-sm">person_add</span>
                             Daftar
                         </a>
                    @endif
                @endauth
            </div>
        </div>
    </div>
</nav>

<script>
    (function() {
        // Mobile menu toggle
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const mobileMenu = document.getElementById('mobileMenu');
        const mobileMenuIcon = document.getElementById('mobileMenuIcon');

        if (mobileMenuBtn && mobileMenu) {
            mobileMenuBtn.addEventListener('click', function() {
                const isHidden = mobileMenu.classList.contains('hidden');
                if (isHidden) {
                    mobileMenu.classList.remove('hidden');
                    mobileMenuIcon.textContent = 'close';
                } else {
                    mobileMenu.classList.add('hidden');
                    mobileMenuIcon.textContent = 'menu';
                }
            });
        }

        document.querySelectorAll('.mobile-nav-link').forEach(link => {
            link.addEventListener('click', () => {
                if (mobileMenu) {
                    mobileMenu.classList.add('hidden');
                    mobileMenuIcon.textContent = 'menu';
                }
            });
        });

        // Dynamic Navbar scroll effect
        const mainNavbar = document.getElementById('mainNavbar');
        window.addEventListener('scroll', function() {
            if (!mainNavbar) return;
            if (window.scrollY > 20) {
                mainNavbar.classList.remove('top-4', 'py-3', 'rounded-2xl', 'w-[calc(100%-32px)]');
                mainNavbar.classList.add('top-2', 'py-2', 'rounded-xl', 'w-[calc(100%-16px)]', 'shadow-lg');
                mainNavbar.style.backgroundColor = '#ffffff';
                mainNavbar.style.borderColor = 'rgba(35, 83, 71, 0.12)';
                mainNavbar.style.boxShadow = '0 8px 32px rgba(5, 31, 32, 0.12)';
            } else {
                mainNavbar.classList.remove('top-2', 'py-2', 'rounded-xl', 'w-[calc(100%-16px)]', 'shadow-lg');
                mainNavbar.classList.add('top-4', 'py-3', 'rounded-2xl', 'w-[calc(100%-32px)]');
                mainNavbar.style.backgroundColor = '#ffffff';
                mainNavbar.style.borderColor = 'rgba(5, 31, 32, 0.08)';
                mainNavbar.style.boxShadow = '0 4px 24px rgba(5, 31, 32, 0.08)';
            }

            // Scroll Indicator Width update
            const scrollIndicator = document.getElementById("scrollIndicator");
            if (scrollIndicator) {
                const winScroll = document.body.scrollTop || document.documentElement.scrollTop;
                const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
                const scrolled = height > 0 ? (winScroll / height) * 100 : 0;
                scrollIndicator.style.width = scrolled + "%";
            }

            // Update active link on scroll
            updateActiveLinkOnScroll();
        });

        // ── Modern Sliding Pill Active Indicator ──
        const navLinks = document.querySelectorAll('.nav-link[data-section]');
        const pill = document.getElementById('navActivePill');
        const wrapper = document.getElementById('navLinksWrapper');

        function movePillToLink(link) {
            if (!pill || !wrapper || !link) return;
            const wrapperRect = wrapper.getBoundingClientRect();
            const linkRect = link.getBoundingClientRect();
            pill.style.left = (linkRect.left - wrapperRect.left) + 'px';
            pill.style.width = linkRect.width + 'px';
            pill.classList.add('visible');
        }

        function setActiveLink(sectionId) {
            navLinks.forEach(link => {
                const isActive = link.getAttribute('data-section') === sectionId;
                link.classList.toggle('active', isActive);
                link.classList.toggle('text-goatin-green', isActive);
                link.classList.toggle('text-primary-dark/80', !isActive);
                if (isActive) movePillToLink(link);
            });
            if (!sectionId) pill.classList.remove('visible');
        }

        // On click: set active immediately
        navLinks.forEach(link => {
            link.addEventListener('click', () => {
                setActiveLink(link.getAttribute('data-section'));
            });
        });

        // Scroll-based detection using IntersectionObserver
        const sectionIds = ['home', 'features', 'catalog', 'testimonials', 'contact'];
        const sectionEls = sectionIds.map(id => document.getElementById(id)).filter(Boolean);
        let activeSectionId = '';

        const observerOptions = {
            root: null,
            rootMargin: '-40% 0px -55% 0px',
            threshold: 0
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    activeSectionId = entry.target.getAttribute('id');
                    setActiveLink(activeSectionId);
                }
            });
        }, observerOptions);

        sectionEls.forEach(section => observer.observe(section));

        // Run once on load to set initial state
        function initActiveLink() {
            const scrollPos = window.scrollY + window.innerHeight * 0.4;
            let found = 'home';
            sectionEls.forEach(section => {
                if (scrollPos >= section.offsetTop) {
                    found = section.getAttribute('id');
                }
            });
            setActiveLink(found);
        }

        // Recalculate pill position on window resize
        window.addEventListener('resize', () => {
            const activeLink = wrapper ? wrapper.querySelector('.nav-link.active') : null;
            if (activeLink) movePillToLink(activeLink);
        });

        // Wait for fonts/layout to settle before calculating pill position
        if (document.readyState === 'complete') {
            initActiveLink();
        } else {
            window.addEventListener('load', initActiveLink);
        }
    })();
</script>
