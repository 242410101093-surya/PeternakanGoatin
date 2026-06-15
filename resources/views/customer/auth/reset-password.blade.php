<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Goatin - Reset Password</title>
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

        /* ── Soft Blue Capsule Inputs ── */
        .capsule-input-container {
            background-color: #F0F6FA !important;
            border: 1px solid rgba(226, 232, 240, 0.8) !important;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1) !important;
        }
        .capsule-input-container:focus-within {
            background-color: #E6F0F6 !important;
            border-color: #235347 !important;
            box-shadow: 0 0 0 3px rgba(35, 83, 71, 0.08) !important;
        }

        /* ── Keyframe Animations ── */
        @keyframes containerEntrance {
            from {
                opacity: 0;
                transform: translateY(30px) scale(0.98);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }
        .animate-container {
            animation: containerEntrance 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
    </style>
</head>
<body class="min-h-screen antialiased flex items-center justify-center text-slate-800 p-4 relative select-none bg-slate-950 overflow-x-hidden">

    {{-- ── Global Full-screen Blurred Background Underlay ── --}}
    <div class="absolute inset-0 bg-cover bg-center select-none z-0"
         style="background-image: url('{{ asset('images/background_goats.png') }}'); filter: blur(24px) brightness(0.45); transform: scale(1.05);"></div>

    <!-- ═══ Global Page-Navigation Loading Spinner ═══ -->
    <div id="global-page-loader"
         style="display:none; position:fixed; inset:0; z-index:9999;
                background:rgba(255,255,255,0.3); backdrop-filter:blur(2px);
                align-items:center; justify-content:center;">
        @include('partials.modern_loader')
    </div>

    {{-- ── Centered Card: Premium White Floating Capsule ── --}}
    <div class="relative z-10 w-full max-w-md bg-white p-8 sm:p-10 rounded-[32px] shadow-2xl border border-white/10 flex flex-col justify-between animate-container">
        
        {{-- Logo Header --}}
        <div class="flex items-center justify-center mb-5">
            <img src="{{ asset('images/logo-auth.png') }}" alt="Goatin Logo" class="h-14 sm:h-16 w-auto object-contain">
        </div>

        {{-- Form Title & Info Text --}}
        <div class="text-center mb-5 space-y-1.5">
            <h1 class="text-2xl font-black tracking-tight text-[#051F20]" style="letter-spacing:-0.03em;">RESET PASSWORD</h1>
            <p class="text-xs text-slate-500 font-medium leading-relaxed">Masukkan kode verifikasi dan password baru untuk akun Anda.</p>
        </div>

        {{-- Countdown Alert Banner --}}
        <div id="otp-timer-banner" class="mb-5 p-4 rounded-[16px] text-xs font-semibold border flex flex-col items-center justify-center gap-1 text-center transition-all duration-300" 
             style="background:#f0faf3; color:#1e5c33; border-color:rgba(42, 120, 68, 0.15);">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px] text-emerald-600">mark_email_read</span>
                <span>Kode OTP berhasil dikirim ke Email Anda.</span>
            </div>
            <div class="text-[10.5px] text-[#2A7844] mt-1 font-bold">
                Harap tunggu <span id="top-countdown" class="font-black text-sm mx-0.5">60</span> detik sebelum mengirim ulang.
            </div>
        </div>

        {{-- Error Alert --}}
        @if($errors->any())
            <div class="mb-5 p-4 rounded-xl text-xs font-semibold border bg-red-50 text-red-600 border-red-100 flex flex-col gap-1">
                @foreach ($errors->all() as $error)
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-[16px]">error</span>
                        <span>{{ $error }}</span>
                    </div>
                @endforeach
            </div>
        @endif

        {{-- Form Area --}}
        <form id="reset-password-form" action="{{ route('password.update') }}" method="POST" class="space-y-4">
            @csrf
            
            {{-- Email (Read-only Capsule) --}}
            <div class="space-y-1.5">
                <label class="block text-[9.5px] font-extrabold text-slate-400 uppercase tracking-widest pl-1" for="email">
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
            <div class="space-y-1.5">
                <div class="flex justify-between items-center pl-1">
                    <label class="block text-[9.5px] font-extrabold text-slate-400 uppercase tracking-widest" for="code">
                        Kode Verifikasi (6 Digit)
                    </label>
                    <button type="button" id="resend-code-btn" onclick="resendCode()" class="text-[9.5px] font-extrabold text-slate-400 cursor-not-allowed transition-colors" disabled>
                        Kirim Ulang (<span id="countdown">60</span>s)
                    </button>
                </div>
                <div class="flex items-center capsule-input-container rounded-full px-5 py-2.5 gap-3 border border-transparent">
                    <span class="material-symbols-outlined text-[20px] text-[#235347]">key</span>
                    <input type="text" name="code" id="code" required maxlength="6" pattern="\d{6}"
                           value="{{ old('code', request()->query('dev_code')) }}"
                           class="bg-transparent border-none p-0 text-sm w-full text-slate-800 placeholder-slate-300 focus:ring-0 focus:outline-none tracking-[0.2em] font-bold" 
                           placeholder="123456">
                </div>
                @error('code')
                    <p class="mt-1 text-[11px] text-red-500 font-semibold pl-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- New Password --}}
            <div class="space-y-1.5">
                <label class="block text-[9.5px] font-extrabold text-slate-400 uppercase tracking-widest pl-1" for="password">
                    Password Baru
                </label>
                <div class="flex items-center capsule-input-container rounded-full px-5 py-2.5 gap-3 border border-transparent">
                    <span class="material-symbols-outlined text-[20px] text-[#235347]">lock</span>
                    <input type="password" name="password" id="password" required autocomplete="new-password"
                           class="bg-transparent border-none p-0 text-sm w-full text-slate-800 placeholder-slate-300 focus:ring-0 focus:outline-none" 
                           placeholder="Minimal 8 karakter">
                </div>
                @error('password')
                    <p class="mt-1 text-[11px] text-red-500 font-semibold pl-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Confirm Password --}}
            <div class="space-y-1.5">
                <label class="block text-[9.5px] font-extrabold text-slate-400 uppercase tracking-widest pl-1" for="password_confirmation">
                    Konfirmasi Password Baru
                </label>
                <div class="flex items-center capsule-input-container rounded-full px-5 py-2.5 gap-3 border border-transparent">
                    <span class="material-symbols-outlined text-[20px] text-[#235347]">lock_reset</span>
                    <input type="password" name="password_confirmation" id="password_confirmation" required autocomplete="new-password"
                           class="bg-transparent border-none p-0 text-sm w-full text-slate-800 placeholder-slate-300 focus:ring-0 focus:outline-none" 
                           placeholder="Ulangi kata sandi baru">
                </div>
            </div>

            {{-- Submit Button --}}
            <div class="pt-2">
                <button type="submit" class="w-full flex justify-center items-center gap-2 py-3.5 px-6 rounded-full text-xs font-extrabold text-white uppercase tracking-widest transition-all duration-300 bg-[#235347] hover:bg-[#163832] shadow-md hover:shadow-lg active:scale-[0.98] cursor-pointer group">
                    <span>Reset Password</span>
                    <span class="material-symbols-outlined text-sm transition-transform duration-200 group-hover:translate-x-1">check</span>
                </button>
            </div>
        </form>

        {{-- Back Navigation Link --}}
        <div class="mt-6 pt-4 border-t border-slate-100 text-center">
            <a href="{{ route('login') }}" class="text-xs text-[#235347] hover:text-[#163832] font-extrabold transition-colors inline-flex items-center gap-1.5">
                <span class="material-symbols-outlined text-sm">arrow_back</span>
                <span>Kembali ke Halaman Login</span>
            </a>
        </div>

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

            document.getElementById('reset-password-form').addEventListener('submit', function(e) {
                showLoader();
            });

            // Countdown Timer logic
            let countdown = 60;
            const countdownEl = document.getElementById('countdown');
            const topCountdownEl = document.getElementById('top-countdown');
            const resendBtn = document.getElementById('resend-code-btn');
            const bannerEl = document.getElementById('otp-timer-banner');
            
            if ((countdownEl || topCountdownEl) && resendBtn) {
                const timer = setInterval(() => {
                    countdown--;
                    if (countdown <= 0) {
                        clearInterval(timer);
                        if (resendBtn) {
                            resendBtn.disabled = false;
                            resendBtn.innerHTML = 'Kirim Ulang Sekarang';
                            resendBtn.classList.remove('text-slate-400', 'cursor-not-allowed');
                            resendBtn.classList.add('text-[#235347]', 'hover:text-[#163832]', 'underline');
                        }
                        if (bannerEl) {
                            bannerEl.style.opacity = '0';
                            setTimeout(() => { bannerEl.style.display = 'none'; }, 300);
                        }
                    } else {
                        if (countdownEl) countdownEl.textContent = countdown;
                        if (topCountdownEl) topCountdownEl.textContent = countdown;
                    }
                }, 1000);
            }

            window.resendCode = function() {
                if(resendBtn.disabled) return;
                const email = document.getElementById('email').value;
                
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = "{{ route('password.email') }}";
                
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = "{{ csrf_token() }}";
                
                const emailInput = document.createElement('input');
                emailInput.type = 'hidden';
                emailInput.name = 'email';
                emailInput.value = email;
                
                form.appendChild(csrfInput);
                form.appendChild(emailInput);
                document.body.appendChild(form);
                
                showLoader();
                form.submit();
            };

            window.addEventListener('pageshow', function() {
                hideLoader();
            });
        })();
    </script>
</body>
</html>
