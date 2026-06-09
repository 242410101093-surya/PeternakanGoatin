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
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary-dark": "#051F20",
                        "accent-teal": "#235347",
                        "accent-teal-dark": "#163832",
                    },
                    fontFamily: {
                        "sans": ['"Plus Jakarta Sans"', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #051F20;
            overflow-x: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
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
    </style>
</head>
<body class="min-h-screen antialiased flex items-center justify-center p-4 md:p-6 select-none overflow-x-hidden">

    {{-- ── Global Full-screen Blurred Background Underlay ── --}}
    <div class="absolute inset-0 bg-cover bg-center select-none z-0"
         style="background-image: url('{{ asset('images/background_goats.png') }}'); filter: blur(20px) brightness(0.42); transform: scale(1.05);"></div>

    <!-- ═══ Global Page-Navigation Loading Spinner ═══ -->
    <div id="global-page-loader"
         style="display:none; position:fixed; inset:0; z-index:9999;
                background:rgba(5,31,32,0.50); backdrop-filter:blur(5px);
                align-items:center; justify-content:center; flex-direction:column; gap:16px;">
        <div style="position:relative; width:72px; height:72px;">
            <div style="position:absolute; inset:-6px; border-radius:50%;
                        border:2px solid rgba(35,83,71,0.18); animation:gpl-pulse 2s ease-in-out infinite;"></div>
            <div style="position:absolute; inset:0; border-radius:50%;
                        border:4px solid transparent;
                        border-top-color:#235347; border-right-color:#235347;
                        animation:gpl-spin 0.8s linear infinite;"></div>
            <div style="position:absolute; inset:10px; border-radius:50%;
                        border:1.5px dashed rgba(35,83,71,0.35);
                        animation:gpl-spin 4s linear infinite reverse;"></div>
            <div style="position:absolute; inset:18px; border-radius:50%;
                        background:rgba(35,83,71,0.1); display:flex;
                        align-items:center; justify-content:center;">
                <img src="{{ asset('images/favicon-32.png?v=3') }}" alt="" style="width:20px;height:20px;object-fit:contain;opacity:0.85;">
            </div>
        </div>
        <p style="color:#8EB69B; font-size:10px; font-weight:700; letter-spacing:0.2em;
                  text-transform:uppercase; animation:gpl-pulse 1.5s ease-in-out infinite;">Memuat...</p>
    </div>
    <style>
        @keyframes gpl-spin  { to { transform: rotate(360deg); } }
        @keyframes gpl-pulse { 0%,100%{opacity:.5;} 50%{opacity:1;} }
    </style>

    {{-- ── Main Container: Floating Rounded Card (No Padding to match 100% boundary) ── --}}
    <div class="relative z-10 w-full max-w-[1100px] flex flex-col md:flex-row rounded-[32px] overflow-hidden shadow-2xl border border-white/10 bg-white p-0 animate-container">

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
                    <img src="{{ asset('images/logo.png') }}" alt="Goatin Logo" class="h-8 w-auto">
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

                <form id="login-form" action="{{ route('login.submit') }}" method="POST" class="space-y-5">
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
                        @error('email')
                            <p class="mt-1 text-[11px] text-red-500 font-semibold pl-1">{{ $message }}</p>
                        @enderror
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
                        @error('password')
                            <p class="mt-1 text-[11px] text-red-500 font-semibold pl-1">{{ $message }}</p>
                        @enderror
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

            document.getElementById('login-form').addEventListener('submit', function(e) {
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
