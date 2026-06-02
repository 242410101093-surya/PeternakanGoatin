<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Goatin - Daftar Akun</title>
    <!-- Favicon -->
    <link class="favicon" rel="icon" type="image/png" sizes="32x32" href="/images/favicon-32.png">
    <link class="favicon" rel="icon" type="image/png" sizes="16x16" href="/images/favicon-16.png">
    <link rel="shortcut icon" href="/images/favicon-32.png">
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
                        "primary-dark": "#0E3247",
                        "accent-teal": "#2A7B94",
                        "accent-teal-dark": "#1F5E72",
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
            background-color: #020617;
            overflow-x: hidden;
        }

        /* ── Soft Teal Capsule Inputs ── */
        .capsule-input-container {
            background-color: rgba(224, 247, 250, 0.45) !important;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1) !important;
        }
        .capsule-input-container:focus-within {
            background-color: rgba(178, 235, 242, 0.6) !important;
            box-shadow: 0 0 0 2px rgba(42, 123, 148, 0.15) !important;
        }

        /* ── Keyframe Animations ── */
        @keyframes fadeInScale {
            from { opacity: 0; transform: scale(1.01); }
            to { opacity: 1; transform: scale(1); }
        }
        .animate-fade-scale {
            animation: fadeInScale 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
    </style>
</head>
<body class="min-h-screen antialiased flex items-stretch justify-center text-slate-800 p-4 md:p-6 relative select-none bg-slate-950 overflow-x-hidden">

    {{-- ── Global Full-screen Blurred Background Underlay ── --}}
    <div class="absolute inset-0 bg-cover bg-center select-none z-0"
         style="background-image: url('{{ asset('images/background_goats.png') }}'); filter: blur(24px) brightness(0.45); transform: scale(1.05);"></div>

    {{-- ── PORTAL LOADER (Cinematic Teal Registration Overlay) ── --}}
    <div id="portal-loader" class="fixed inset-0 z-55 hidden flex-col items-center justify-center bg-slate-950/95 backdrop-blur-lg">
        <div class="relative w-28 h-28 mb-8">
            <div class="absolute inset-0 rounded-full border-4 border-teal-500/10 scale-110"></div>
            <div class="absolute inset-0 rounded-full border-4 border-t-teal-500 border-r-teal-500 border-transparent animate-spin"></div>
            <div class="absolute inset-3 rounded-full border border-dashed border-teal-500/30 animate-pulse"></div>
            <div class="absolute inset-6 rounded-full bg-teal-500/10 flex items-center justify-center">
                <span class="material-symbols-outlined text-teal-400 text-2xl animate-pulse">person_add</span>
            </div>
        </div>
        <p id="loader-message" class="text-xs font-bold tracking-[0.25em] text-teal-400 uppercase animate-pulse">
            Mempersiapkan Pendaftaran...
        </p>
    </div>

    {{-- ── Main Container: Floating with margins showing blurred background ── --}}
    <div class="relative z-10 w-full flex flex-col md:flex-row rounded-[32px] overflow-hidden shadow-2xl border border-white/10 bg-white">

        {{-- ── LEFT PANE: Split screen form pane with Jagged Right Edge ── --}}
        <section class="w-full md:w-[45%] lg:w-[40%] xl:w-[35%] flex flex-col justify-between p-8 sm:p-12 md:p-14 relative z-20 bg-white min-h-full shrink-0">
            
            {{-- Absolute Torn-Paper Wavy Divider Edge (Visible only on medium screens and up) --}}
            <div class="absolute top-0 bottom-0 left-full w-[36px] h-full pointer-events-none z-20 -ml-px hidden md:block text-white fill-current drop-shadow-[8px_0_12px_rgba(0,0,0,0.08)]">
                <svg class="w-full h-full" viewBox="0 0 100 1000" preserveAspectRatio="none">
                    <path d="M 0 0 
                             C 15 10 25 25 25 40
                             C 25 55 15 70 0 80
                             C 15 90 25 105 25 120
                             C 25 135 15 150 0 160
                             C 15 170 25 185 25 200
                             C 25 215 15 230 0 240
                             C 15 250 25 265 25 280
                             C 25 295 15 310 0 320
                             C 15 330 25 345 25 360
                             C 25 375 15 390 0 400
                             C 15 410 25 425 25 440
                             C 25 455 15 470 0 480
                             C 15 490 25 505 25 520
                             C 25 535 15 550 0 560
                             C 15 570 25 585 25 600
                             C 25 615 15 630 0 640
                             C 15 650 25 665 25 680
                             C 25 695 15 710 0 720
                             C 15 730 25 745 25 760
                             C 25 775 15 790 0 800
                             C 15 810 25 825 25 840
                             C 25 855 15 870 0 880
                             C 15 890 25 905 25 920
                             C 25 935 15 950 0 960
                             C 15 970 25 985 25 1000
                             L 0 1000 Z" />
                </svg>
            </div>

            {{-- Top Navigation Header --}}
            <header class="flex items-center justify-between gap-4">
                <div>
                    <img src="{{ asset('images/logo.png') }}" alt="Goatin Logo" class="h-8 w-auto">
                </div>
                
                <div class="flex items-center gap-6 text-xs uppercase tracking-widest font-bold">
                    <a href="{{ route('login') }}" class="pb-1 border-b-2 border-transparent text-slate-400 hover:text-primary-dark transition-colors">
                        Sign in
                    </a>
                    <a href="{{ route('register') }}" class="pb-1 border-b-2 border-accent-teal text-primary-dark">
                        Sign up
                    </a>
                </div>
            </header>

            {{-- Center Form Box --}}
            <div class="my-auto py-8 space-y-6 animate-fade-scale">
                
                <div class="space-y-1.5">
                    <h1 class="text-3xl font-black tracking-tight" style="color:#0E3247; letter-spacing:-0.03em;">SIGN UP</h1>
                    <p class="text-xs text-slate-500 font-medium">Daftarkan akun pelanggan baru Anda secara instan di bawah ini.</p>
                </div>

                {{-- Validation Errors --}}
                @if ($errors->any())
                    <div class="p-4 rounded-xl text-xs font-semibold border" 
                         style="background:rgba(239, 68, 68, 0.03); color:#ba1a1a; border-color:rgba(186, 26, 26, 0.15);">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form id="register-form" action="{{ route('register.submit') }}" method="POST" class="space-y-4">
                    @csrf
                    
                    {{-- Nama Lengkap --}}
                    <div class="space-y-1">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest pl-1" for="name">
                            Nama Lengkap
                        </label>
                        <div class="flex items-center capsule-input-container rounded-full px-5 py-2.5 gap-3 border border-transparent">
                            <span class="material-symbols-outlined text-[20px] text-accent-teal">person</span>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                   class="bg-transparent border-none p-0 text-sm w-full text-primary-dark placeholder-slate-400 focus:ring-0 focus:outline-none" 
                                   placeholder="nama lengkap">
                        </div>
                    </div>

                    {{-- Alamat Email --}}
                    <div class="space-y-1">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest pl-1" for="email">
                            Alamat Email
                        </label>
                        <div class="flex items-center capsule-input-container rounded-full px-5 py-2.5 gap-3 border border-transparent">
                            <span class="material-symbols-outlined text-[20px] text-accent-teal">mail</span>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" required
                                   class="bg-transparent border-none p-0 text-sm w-full text-primary-dark placeholder-slate-400 focus:ring-0 focus:outline-none" 
                                   placeholder="e-mail">
                        </div>
                    </div>

                    {{-- WhatsApp --}}
                    <div class="space-y-1">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest pl-1" for="whatsapp">
                            Nomor WhatsApp
                        </label>
                        <div class="flex items-center capsule-input-container rounded-full px-5 py-2.5 gap-3 border border-transparent">
                            <span class="material-symbols-outlined text-[20px] text-accent-teal">call</span>
                            <input type="text" name="whatsapp" id="whatsapp" value="{{ old('whatsapp') }}" required
                                   class="bg-transparent border-none p-0 text-sm w-full text-primary-dark placeholder-slate-400 focus:ring-0 focus:outline-none" 
                                   placeholder="nomor WhatsApp">
                        </div>
                    </div>

                    {{-- Password --}}
                    <div class="space-y-1">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest pl-1" for="password">
                            Password
                        </label>
                        <div class="flex items-center capsule-input-container rounded-full px-5 py-2.5 gap-3 border border-transparent">
                            <span class="material-symbols-outlined text-[20px] text-accent-teal">lock</span>
                            <input type="password" name="password" id="password" required
                                   class="bg-transparent border-none p-0 text-sm w-full text-primary-dark placeholder-slate-400 focus:ring-0 focus:outline-none" 
                                   placeholder="password">
                        </div>
                    </div>

                    {{-- Konfirmasi Password --}}
                    <div class="space-y-1">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest pl-1" for="password_confirmation">
                            Konfirmasi Password
                        </label>
                        <div class="flex items-center capsule-input-container rounded-full px-5 py-2.5 gap-3 border border-transparent">
                            <span class="material-symbols-outlined text-[20px] text-accent-teal">lock_reset</span>
                            <input type="password" name="password_confirmation" id="password_confirmation" required
                                   class="bg-transparent border-none p-0 text-sm w-full text-primary-dark placeholder-slate-400 focus:ring-0 focus:outline-none" 
                                   placeholder="konfirmasi password">
                        </div>
                    </div>

                    {{-- Submit Button --}}
                    <div class="pt-2">
                        <button type="submit" class="w-full flex justify-center items-center gap-2 py-3 px-6 rounded-full text-xs font-extrabold text-white uppercase tracking-wider transition-all shadow-md hover:shadow-lg active:scale-[0.98] cursor-pointer"
                                style="background: linear-gradient(135deg, #2A7B94 0%, #1F5E72 100%);">
                            Sign Up
                            <span class="material-symbols-outlined text-sm">person_add</span>
                        </button>
                    </div>
                </form>

            </div>

            {{-- Bottom Legal --}}
            <footer class="flex items-center justify-between text-[10px] text-slate-400 pt-6 border-t border-slate-50">
                <span>© {{ date('Y') }} Goatin.</span>
                <div class="flex gap-4">
                    <a href="#" class="hover:text-slate-600 transition-colors">Privasi</a>
                    <a href="#" class="hover:text-slate-600 transition-colors">Ketentuan</a>
                </div>
            </footer>

        </section>

        {{-- ── RIGHT PANE: Lush Goats Hill Path view (Wallpaper cover, full clear) ── --}}
        <section class="hidden md:flex md:w-[55%] lg:w-[60%] xl:w-[65%] relative overflow-hidden flex-col justify-center items-center bg-cover bg-center min-h-full z-10"
                 style="background-image: url('{{ asset('images/background_goats.png') }}');">
            
            {{-- Luxury Dark Semi-translucent Overlay --}}
            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-slate-950/30 to-slate-900/40 z-0"></div>

            {{-- Beautifully Designed Slogan Text overlay --}}
            <div class="relative z-10 flex flex-col items-center justify-center text-center h-full max-w-2xl px-8 select-none">
                <span class="px-3.5 py-1 rounded-full bg-emerald-500/20 border border-emerald-500/30 text-[11px] font-bold tracking-widest text-emerald-300 uppercase mb-4 backdrop-blur-sm animate-pulse">
                    Premium SaaS 2026
                </span>
                <h2 class="text-4xl md:text-5xl font-black leading-tight text-white tracking-tight uppercase" style="letter-spacing:-0.03em;">
                    GOATIN PRIME
                </h2>
                <h3 class="text-xl md:text-2xl font-bold tracking-[0.3em] uppercase mt-2 text-transparent bg-clip-text bg-gradient-to-r from-emerald-300 to-green-200">
                    PETERNAKAN MODERN
                </h3>
                <div class="w-16 h-1 rounded bg-emerald-400/50 mt-6 mb-4"></div>
                <p class="text-sm font-medium leading-relaxed max-w-md text-emerald-100/80">
                    Hubungkan manajemen, rekam medis terperinci, pemantauan transaksi, dan edukasi ternak berkualitas dalam satu platform enterprise.
                </p>
            </div>

            {{-- Top Right brand watermark --}}
            <div class="absolute top-12 right-12 z-10">
                <span class="text-[10px] font-bold tracking-widest text-white/40 uppercase">Goatin Enterprise</span>
            </div>
        </section>

    </div>

    <script>
        document.getElementById('register-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const loader = document.getElementById('portal-loader');
            const message = document.getElementById('loader-message');

            loader.classList.remove('hidden');
            loader.classList.add('flex');

            setTimeout(() => {
                message.textContent = "Mengirimkan Data Pendaftaran...";
            }, 600);

            setTimeout(() => {
                message.textContent = "Memverifikasi Keabsahan Informasi...";
            }, 1300);

            setTimeout(() => {
                e.target.submit();
            }, 2000);
        });
    </script>
</body>
</html>
