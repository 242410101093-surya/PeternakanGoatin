<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Goatin - Masuk</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="64x64" href="{{ asset('images/favicon-64.png?v=3') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon-32.png?v=3') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon-16.png?v=3') }}">
    <link rel="shortcut icon" href="{{ asset('images/favicon.png?v=3') }}">
    <!-- Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
    <!-- Material Symbols -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <!-- Tailwind CSS -->
    <style>
        /* ── Fallback Styling (when sandboxed or Tailwind CDN is blocked) ── */
        img {
            max-width: 100%;
        }
        .h-10, .h-12, .h-11, nav img, header img {
            height: 48px !important;
            width: auto !important;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #051F20;
            overflow-x: hidden;
        }

        /* ── Input Styling ── */
        .capsule-input-container {
            background-color: #EDF4F8 !important;
            border: 1px solid transparent !important;
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1) !important;
        }
        .capsule-input-container:focus-within {
            background-color: #E2ECF2 !important;
            border-color: rgba(35, 83, 71, 0.25) !important;
            box-shadow: 0 0 0 3px rgba(35, 83, 71, 0.06) !important;
        }

        /* ── Entrance Animations ── */
        @keyframes containerEntrance {
            from {
                opacity: 0;
                transform: translateY(28px) scale(0.99);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }
        .animate-container {
            animation: containerEntrance 0.75s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        @keyframes fadeInLeft {
            from {
                opacity: 0;
                transform: translateX(-16px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        .animate-left {
            opacity: 0;
            animation: fadeInLeft 0.75s cubic-bezier(0.16, 1, 0.3, 1) 0.12s forwards;
        }

        @keyframes slideInLeftStaggered {
            from {
                opacity: 0;
                transform: translateX(-32px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        .animate-stagger-1 {
            opacity: 0;
            animation: slideInLeftStaggered 0.7s cubic-bezier(0.16, 1, 0.3, 1) 0.25s forwards;
        }
        .animate-stagger-2 {
            opacity: 0;
            animation: slideInLeftStaggered 0.7s cubic-bezier(0.16, 1, 0.3, 1) 0.38s forwards;
        }
        .animate-stagger-3 {
            opacity: 0;
            animation: slideInLeftStaggered 0.7s cubic-bezier(0.16, 1, 0.3, 1) 0.5s forwards;
        }
        .animate-stagger-4 {
            opacity: 0;
            animation: slideInLeftStaggered 0.7s cubic-bezier(0.16, 1, 0.3, 1) 0.62s forwards;
        }
        .animate-stagger-5 {
            opacity: 0;
            animation: slideInLeftStaggered 0.7s cubic-bezier(0.16, 1, 0.3, 1) 0.74s forwards;
        }

        @keyframes fadeInRight {
            from {
                opacity: 0;
                transform: translateX(16px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        .animate-right {
            opacity: 0;
            animation: fadeInRight 0.75s cubic-bezier(0.16, 1, 0.3, 1) 0.18s forwards;
        }

        /* micro-interactions */
        .hover-lift {
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .hover-lift:hover {
            transform: translateY(-3px);
            background-color: rgba(255, 255, 255, 0.12);
            border-color: rgba(255, 255, 255, 0.22);
        }

        /* custom validation tooltip */
        .custom-val-tooltip {
            position: absolute;
            top: -42px;
            left: 10px;
            background: #ef4444; /* red-500 */
            color: white;
            padding: 8px 14px;
            border-radius: 10px;
            font-size: 11.5px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 6px;
            z-index: 50;
            box-shadow: 0 4px 14px rgba(239, 68, 68, 0.3);
            animation: bounceIn 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .custom-val-tooltip::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 20px;
            width: 12px;
            height: 12px;
            background: #ef4444;
            transform: rotate(45deg);
        }
        @keyframes bounceIn {
            0% { opacity: 0; transform: translateY(10px) scale(0.95); }
            100% { opacity: 1; transform: translateY(0) scale(1); }
        }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen antialiased flex flex-col justify-between select-none overflow-x-hidden pt-[100px]">

    {{-- ── Global Full-screen Blurred Background Underlay ── --}}
    <div class="fixed inset-0 bg-cover bg-center select-none z-0"
         style="background-image: url('{{ asset('images/background_goats.png') }}'); filter: blur(20px) brightness(0.42); transform: scale(1.05);"></div>

    <!-- ═══ Global Page-Navigation Loading Spinner ═══ -->
    <div id="global-page-loader"
         style="display:none; position:fixed; inset:0; z-index:9999;
                background:rgba(255,255,255,0.3); backdrop-filter:blur(2px);
                align-items:center; justify-content:center;">
        @include('partials.modern_loader')
    </div>

    @include('partials.landing.header')

    <main class="flex-grow flex items-center justify-center py-10 px-4 md:px-6 relative z-10">
        {{-- ── Main Container: Floating Rounded Card (No Padding to match 100% boundary) ── --}}
        <div class="relative w-full max-w-[1100px] flex flex-col md:flex-row rounded-[32px] overflow-hidden shadow-2xl border border-white/10 bg-white p-0 animate-container">

        {{-- ── LEFT SECTION: Image Banner (62% Width, Flush with Left/Top/Bottom margins) ── --}}
        <section class="hidden md:flex md:w-[62%] relative overflow-hidden flex-col justify-between p-10 min-h-[580px] z-10 rounded-l-[32px] rounded-r-[48px] animate-left"
                 style="background-image: url('{{ asset('images/background_goats.png') }}'); background-size: cover; background-position: right center;">
            
            {{-- Dark Overlay to increase readability (Stronger gradient at the bottom) --}}
            <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/40 to-black/20 z-0"></div>

            {{-- Slogans (Middle) wrapped per-line for an exact fit glassmorphic effect --}}
            <div class="relative z-10 flex flex-col items-start gap-1.5 mt-auto mb-6 text-left pr-10">
                <h2 class="w-fit text-3xl md:text-4xl font-black text-white tracking-tight leading-none uppercase animate-stagger-2 bg-black/40 backdrop-blur-md px-3.5 py-1.5 rounded-xl border border-white/10 shadow-lg" style="letter-spacing: -0.02em;">
                    GOATIN
                </h2>
                <h3 class="w-fit text-xl md:text-2xl font-extrabold text-white tracking-tight leading-none animate-stagger-3 bg-black/40 backdrop-blur-md px-3.5 py-1.5 rounded-xl border border-white/10 shadow-lg" style="letter-spacing: -0.02em;">
                    Peternakan Kambing
                </h3>
                <div class="w-fit flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-black/40 backdrop-blur-md border border-white/10 shadow-lg text-[10px] md:text-[11px] font-extrabold tracking-widest text-[#8EB69B] uppercase animate-stagger-4">
                    <span class="material-symbols-outlined text-[14px] font-bold">eco</span>
                    <span>Modern &bull; Efisien &bull; Terpercaya</span>
                </div>
                <p class="w-fit text-[11px] md:text-xs font-medium leading-relaxed max-w-[350px] text-slate-200/90 animate-stagger-5 bg-black/40 backdrop-blur-md px-3.5 py-2.5 rounded-xl border border-white/10 shadow-lg mt-1">
                    Teknologi dan manajemen terintegrasi untuk peternakan kambing yang lebih produktif, sehat, dan berkelanjutan.
                </p>
            </div>

        </section>

        {{-- ── RIGHT SECTION: Form Area (38% Width, Padded) ── --}}
        <section class="w-full md:w-[38%] flex flex-col justify-between p-8 md:p-10 min-h-full shrink-0 z-10 animate-right">
            
            {{-- Header with Logo and Sign In/Sign Up links --}}
            <header class="flex items-center justify-between gap-4">
                <div>
                    <img src="{{ asset('images/logo-auth.png') }}" alt="Goatin Logo" class="h-10 sm:h-12 w-auto object-contain">
                </div>
                
                <div class="flex items-center gap-5 text-[11px] uppercase tracking-wider font-extrabold">
                    <a href="{{ route('login') }}" class="pb-1 border-b-2 border-[#235347] text-[#051F20]">
                        Sign in
                    </a>
                    <a href="{{ route('register') }}" class="pb-1 border-b-2 border-transparent text-[#9EAFB8] hover:text-[#235347] transition-all duration-200">
                        Sign up
                    </a>
                </div>
            </header>

            {{-- Form Wrapper --}}
            <div class="my-auto py-8 space-y-7">
                
                <div class="space-y-1.5">
                    <h1 class="text-3xl font-extrabold tracking-tight text-[#051F20] uppercase" style="letter-spacing:-0.03em;">SIGN IN</h1>
                    <p class="text-xs text-[#6C7A84] font-medium">Silakan masuk menggunakan kredensial akun terdaftar Anda.</p>
                </div>

                @if(session('status'))
                    <div class="p-4 rounded-xl text-xs font-semibold border" 
                         style="background:#f0faf3; color:#1e5c33; border-color:rgba(42, 120, 68, 0.15);">
                        {{ session('status') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="flex items-center gap-3 p-4 rounded-xl text-xs font-semibold border animate-container" 
                         style="background:rgba(239, 68, 68, 0.04); color:#ba1a1a; border-color:rgba(239, 68, 68, 0.2);">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-red-100 flex items-center justify-center">
                            <span class="material-symbols-outlined text-[18px] text-red-600">error</span>
                        </div>
                        <ul class="list-none space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form id="login-form" action="{{ route('login.submit') }}" method="POST" class="space-y-5" novalidate>
                    @csrf
                    
                    {{-- Username / Email Input --}}
                    <div class="space-y-1.5">
                        <label class="block text-[9.5px] font-extrabold text-[#8C9EA8] uppercase tracking-widest pl-1" for="email">
                            Alamat Email / Username
                        </label>
                        <div class="flex items-center capsule-input-container rounded-full px-5 py-3.5 gap-3">
                            <span class="material-symbols-outlined text-[20px] text-[#235347]">mail</span>
                            <input type="text" name="email" id="email" value="{{ old('email') }}" required autocomplete="username"
                                   class="bg-transparent border-none p-0 text-sm w-full text-slate-800 focus:ring-0 focus:outline-none" 
                                   placeholder="e-mail">
                        </div>
                    </div>

                    {{-- Password Input --}}
                    <div class="space-y-1.5">
                        <div class="flex justify-between items-center pl-1">
                            <label class="block text-[9.5px] font-extrabold text-[#8C9EA8] uppercase tracking-widest" for="password">
                                Password
                            </label>
                            <a href="{{ route('password.request') }}" class="text-[9.5px] text-[#235347] hover:text-[#163832] hover:underline font-extrabold transition-colors">
                                Lupa?
                            </a>
                        </div>
                        <div class="flex items-center capsule-input-container rounded-full px-5 py-3.5 gap-3">
                            <span class="material-symbols-outlined text-[20px] text-[#235347]">lock</span>
                            <input type="password" name="password" id="password" required autocomplete="current-password"
                                   class="bg-transparent border-none p-0 text-sm w-full text-slate-800 focus:ring-0 focus:outline-none" 
                                   placeholder="password">
                            <button type="button" class="text-[#8C9EA8] hover:text-[#235347] focus:outline-none flex items-center transition-colors" onclick="togglePasswordVisibility('password', this)">
                                <span class="material-symbols-outlined text-[18px]">visibility</span>
                            </button>
                        </div>
                    </div>

                    {{-- Remember me checkbox --}}
                    <div class="flex items-center gap-2.5 pl-1.5">
                        <input type="checkbox" id="remember-me" name="remember-me" 
                               class="h-4 w-4 rounded border-slate-300 bg-slate-50 text-[#235347] focus:ring-[#235347]/30">
                        <label for="remember-me" class="text-[11px] text-[#4A5568] font-bold select-none cursor-pointer">Ingat saya untuk sesi berikutnya</label>
                    </div>

                    {{-- Submit Button --}}
                    <div class="pt-2">
                        <button type="submit" class="w-full flex justify-center items-center gap-2 py-3.5 px-6 rounded-full text-xs font-extrabold text-white uppercase tracking-widest transition-all duration-300 bg-[#235347] hover:bg-[#163832] shadow-md hover:shadow-lg active:scale-[0.98] cursor-pointer group">
                            <span>Sign In</span>
                            <span class="material-symbols-outlined text-sm transition-transform duration-200 group-hover:translate-x-1">arrow_forward</span>
                        </button>
                    </div>
                </form>

                {{-- Divider --}}
                <div class="relative flex items-center justify-center my-4">
                    <div class="absolute inset-0 flex items-center" aria-hidden="true">
                        <div class="w-full border-t border-slate-200/80"></div>
                    </div>
                    <div class="relative bg-white px-4 text-[9px] font-extrabold text-slate-400 uppercase tracking-widest">
                        atau masuk dengan
                    </div>
                </div>

                {{-- Google Button --}}
                <div>
                    <a href="{{ route('auth.google') }}" 
                       class="w-full flex justify-center items-center gap-3 py-3.5 px-6 rounded-full text-xs font-extrabold text-slate-700 uppercase tracking-widest border border-slate-200 bg-white hover:bg-slate-50 transition-all duration-300 shadow-sm hover:shadow active:scale-[0.98] cursor-pointer group">
                        <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24">
                            <path fill="#EA4335" d="M12 5.04c1.66 0 3.2.57 4.38 1.69l3.27-3.27C17.68 1.54 14.98 1 12 1 7.35 1 3.37 3.67 1.39 7.56l3.85 2.99c.9-2.69 3.42-4.51 6.76-4.51z"/>
                            <path fill="#4285F4" d="M23.49 12.27c0-.81-.07-1.59-.2-2.36H12v4.51h6.46c-.28 1.48-1.11 2.73-2.36 3.58l3.66 2.84c2.14-1.98 3.39-4.88 3.39-8.57z"/>
                            <path fill="#FBBC05" d="M5.24 14.57c-.23-.69-.36-1.42-.36-2.18s.13-1.49.36-2.18L1.39 7.22C.5 9 .01 10.97.01 13c0 2.03.49 4 1.38 5.78l3.85-2.99c-.23-.69-.36-1.42-.36-2.21z"/>
                            <path fill="#34A853" d="M12 23c3.24 0 5.97-1.07 7.96-2.91l-3.66-2.84c-1.01.68-2.31 1.09-4.3 1.09-3.34 0-5.86-1.82-6.87-4.51l-3.85 2.99C3.37 20.33 7.35 23 12 23z"/>
                        </svg>
                        <span>Google</span>
                    </a>
                </div>

            </div>

            {{-- Footer --}}
            <footer class="flex items-center justify-between text-[10px] text-slate-400 pt-6 border-t border-slate-100">
                <span>© 2026 Goatin.</span>
                <div class="flex gap-4">
                    <a href="#" class="hover:text-slate-600 transition-colors">Privasi</a>
                    <a href="#" class="hover:text-slate-600 transition-colors">Ketentuan</a>
                </div>
            </footer>

        </section>

    </div>
    </main>

    @include('partials.landing.footer')

    <script>
        (function() {
            const loader = document.getElementById('global-page-loader');
            function showLoader() { loader.style.display = 'flex'; }
            function hideLoader() { loader.style.display = 'none'; }

            document.addEventListener('click', function(e) {
                const link = e.target.closest('a[href]');
                if (!link) return;
                const href = link.getAttribute('href');
                if (!href || href.startsWith('#') || href.startsWith('javascript') ||
                    href.startsWith('mailto') || href.startsWith('tel') ||
                    link.target === '_blank' || link.hasAttribute('download')) return;
                showLoader();
            });

            const loginForm = document.getElementById('login-form');
            loginForm.addEventListener('submit', function(e) {
                // Clear any existing custom tooltips
                document.querySelectorAll('.custom-val-tooltip').forEach(el => el.remove());
                
                let isValid = true;
                const requiredInputs = loginForm.querySelectorAll('input[required]');
                
                // Reverse loop so the first empty input gets focus
                for (let i = requiredInputs.length - 1; i >= 0; i--) {
                    const input = requiredInputs[i];
                    if (!input.value.trim()) {
                        isValid = false;
                        
                        const tooltip = document.createElement('div');
                        tooltip.className = 'custom-val-tooltip';
                        tooltip.innerHTML = '<span class="material-symbols-outlined text-[16px]">error</span> Harap isi semua kolom';
                        
                        const container = input.closest('.capsule-input-container');
                        container.style.position = 'relative';
                        container.appendChild(tooltip);
                        
                        // Auto-hide after 3.5s
                        setTimeout(() => {
                            if (tooltip.parentElement) {
                                tooltip.style.animation = 'bounceIn 0.2s cubic-bezier(0.16, 1, 0.3, 1) reverse forwards';
                                setTimeout(() => tooltip.remove(), 200);
                            }
                        }, 3500);
                        
                        input.focus();
                        
                        // Remove tooltip on typing
                        input.addEventListener('input', function onInput() {
                            if (tooltip.parentElement) tooltip.remove();
                            input.removeEventListener('input', onInput);
                        });
                    }
                }
                
                if (!isValid) {
                    e.preventDefault();
                    return false;
                }
                
                showLoader();
            });

            window.addEventListener('pageshow', function() {
                hideLoader();
            });

            window.togglePasswordVisibility = function(inputId, btn) {
                const input = document.getElementById(inputId);
                const icon = btn.querySelector('span');
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.textContent = 'visibility_off';
                } else {
                    input.type = 'password';
                    icon.textContent = 'visibility';
                }
            };
        })();
    </script>
</body>
</html>
