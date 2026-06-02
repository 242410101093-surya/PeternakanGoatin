<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Goatin - Reset Password</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="32x32" href="/images/favicon-32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/images/favicon-16.png">
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
<body class="min-h-screen antialiased flex items-center justify-center text-slate-800 p-4 relative select-none bg-slate-950 overflow-x-hidden">

    {{-- ── Global Full-screen Blurred Background Underlay ── --}}
    <div class="absolute inset-0 bg-cover bg-center select-none z-0"
         style="background-image: url('{{ asset('images/background_goats.png') }}'); filter: blur(24px) brightness(0.45); transform: scale(1.05);"></div>

    {{-- ── PORTAL LOADER (Cinematic Teal Entrance overlay) ── --}}
    <div id="portal-loader" class="fixed inset-0 z-55 hidden flex-col items-center justify-center bg-slate-950/95 backdrop-blur-lg">
        <div class="relative w-28 h-28 mb-8">
            <div class="absolute inset-0 rounded-full border-4 border-teal-500/10 scale-110"></div>
            <div class="absolute inset-0 rounded-full border-4 border-t-teal-500 border-r-teal-500 border-transparent animate-spin"></div>
            <div class="absolute inset-3 rounded-full border border-dashed border-teal-500/30 animate-pulse"></div>
            <div class="absolute inset-6 rounded-full bg-teal-500/10 flex items-center justify-center">
                <span class="material-symbols-outlined text-teal-400 text-2xl animate-pulse">lock_open</span>
            </div>
        </div>
        <p id="loader-message" class="text-xs font-bold tracking-[0.25em] text-teal-400 uppercase animate-pulse">
            Memverifikasi Kode...
        </p>
    </div>

    {{-- ── Centered Card: Premium White Floating Capsule ── --}}
    <div class="relative z-10 w-full max-w-md bg-white p-8 sm:p-10 rounded-[32px] shadow-2xl border border-white/10 flex flex-col justify-between animate-fade-scale">
        
        {{-- Logo Header --}}
        <div class="flex items-center justify-center mb-5">
            <img src="{{ asset('images/logo.png') }}" alt="Goatin Logo" class="h-16 w-auto">
        </div>

        {{-- Form Title & Info Text --}}
        <div class="text-center mb-5 space-y-1.5">
            <h1 class="text-2xl font-black tracking-tight" style="color:#0E3247; letter-spacing:-0.03em;">RESET PASSWORD</h1>
            <p class="text-xs text-slate-500 font-medium leading-relaxed">Masukkan kode verifikasi dan password baru untuk akun Anda.</p>
        </div>

        {{-- Informational Alert: Verification Code Sent --}}
        <div class="mb-5 p-3.5 rounded-xl text-xs font-semibold border flex items-center gap-2.5" 
             style="background:#f0faf3; color:#1e5c33; border-color:rgba(42, 120, 68, 0.15);">
            <span class="material-symbols-outlined text-[18px] text-emerald-600">check_circle</span>
            <span>Kode verifikasi telah dikirim ke email Anda.</span>
        </div>

        {{-- Success Session Alert --}}
        @if(session('status'))
            <div class="mb-5 p-4 rounded-xl text-xs font-semibold border" 
                 style="background:#f0faf3; color:#1e5c33; border-color:rgba(42, 120, 68, 0.15);">
                {{ session('status') }}
            </div>
        @endif

        {{-- Form Area --}}
        <form id="reset-password-form" action="{{ route('password.update') }}" method="POST" class="space-y-4">
            @csrf
            
            {{-- Email (Read-only Capsule) --}}
            <div class="space-y-1">
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest pl-1" for="email">
                    Alamat Email
                </label>
                <div class="flex items-center rounded-full px-5 py-2.5 gap-3 border border-slate-100 bg-slate-50 cursor-not-allowed">
                    <span class="material-symbols-outlined text-[20px] text-slate-400">mail</span>
                    <input type="email" name="email" id="email" value="{{ old('email', $email) }}" readonly required
                           class="bg-transparent border-none p-0 text-sm w-full text-slate-500 placeholder-slate-400 focus:ring-0 focus:outline-none cursor-not-allowed">
                </div>
                @error('email')
                    <p class="mt-1 text-[11px] text-red-500 font-semibold pl-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Verification Code --}}
            <div class="space-y-1">
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest pl-1" for="code">
                    Kode Verifikasi (6 Digit)
                </label>
                <div class="flex items-center capsule-input-container rounded-full px-5 py-2.5 gap-3 border border-transparent">
                    <span class="material-symbols-outlined text-[20px] text-accent-teal">pin</span>
                    <input type="text" name="code" id="code" required maxlength="6" autocomplete="off"
                           class="bg-transparent border-none p-0 text-sm w-full text-primary-dark placeholder-slate-300 focus:ring-0 focus:outline-none" 
                           placeholder="Masukkan 6 digit kode">
                </div>
                @error('code')
                    <p class="mt-1 text-[11px] text-red-500 font-semibold pl-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- New Password --}}
            <div class="space-y-1">
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest pl-1" for="password">
                    Password Baru
                </label>
                <div class="flex items-center capsule-input-container rounded-full px-5 py-2.5 gap-3 border border-transparent">
                    <span class="material-symbols-outlined text-[20px] text-accent-teal">lock</span>
                    <input type="password" name="password" id="password" required autocomplete="new-password"
                           class="bg-transparent border-none p-0 text-sm w-full text-primary-dark placeholder-slate-300 focus:ring-0 focus:outline-none" 
                           placeholder="Minimal 8 karakter">
                </div>
                @error('password')
                    <p class="mt-1 text-[11px] text-red-500 font-semibold pl-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Confirm Password --}}
            <div class="space-y-1">
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest pl-1" for="password_confirmation">
                    Konfirmasi Password Baru
                </label>
                <div class="flex items-center capsule-input-container rounded-full px-5 py-2.5 gap-3 border border-transparent">
                    <span class="material-symbols-outlined text-[20px] text-accent-teal">lock_reset</span>
                    <input type="password" name="password_confirmation" id="password_confirmation" required autocomplete="new-password"
                           class="bg-transparent border-none p-0 text-sm w-full text-primary-dark placeholder-slate-300 focus:ring-0 focus:outline-none" 
                           placeholder="Ulangi kata sandi baru">
                </div>
            </div>

            {{-- Submit Button --}}
            <div class="pt-2">
                <button type="submit" class="w-full flex justify-center items-center gap-2 py-3 px-6 rounded-full text-xs font-extrabold text-white uppercase tracking-wider transition-all shadow-md hover:shadow-lg active:scale-[0.98] cursor-pointer"
                        style="background: linear-gradient(135deg, #2A7B94 0%, #1F5E72 100%);">
                    Reset Password
                    <span class="material-symbols-outlined text-sm">check</span>
                </button>
            </div>
        </form>

        {{-- Back Navigation Link --}}
        <div class="mt-6 pt-4 border-t border-slate-100 text-center">
            <a href="{{ route('login') }}" class="text-xs text-accent-teal hover:text-accent-teal-dark font-extrabold transition-colors inline-flex items-center gap-1">
                <span class="material-symbols-outlined text-sm">arrow_back</span>
                Kembali ke Halaman Login
            </a>
        </div>

    </div>

    <script>
        document.getElementById('reset-password-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const loader = document.getElementById('portal-loader');
            const message = document.getElementById('loader-message');

            // Display loading overlay
            loader.classList.remove('hidden');
            loader.classList.add('flex');

            // Dynamic stages of auth loading
            setTimeout(() => {
                message.textContent = "Mengajukan Reset Sandi...";
            }, 600);

            setTimeout(() => {
                message.textContent = "Menerapkan Sandi Baru Anda...";
            }, 1300);

            // Complete simulation before actual submit
            setTimeout(() => {
                e.target.submit();
            }, 2100);
        });
    </script>
</body>
</html>
