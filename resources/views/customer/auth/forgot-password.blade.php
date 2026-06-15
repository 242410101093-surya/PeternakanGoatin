<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Goatin - Lupa Password</title>
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
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen antialiased flex items-center justify-center text-slate-800 p-4 md:p-6 relative select-none bg-slate-950 overflow-x-hidden">

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
        <div class="flex items-center justify-center mb-6">
            <img src="{{ asset('images/logo-auth.png') }}" alt="Goatin Logo" class="h-14 sm:h-16 w-auto object-contain">
        </div>

        {{-- Form Title & Info Text --}}
        <div class="text-center mb-6 space-y-2">
            <h1 class="text-2xl font-black tracking-tight text-[#051F20]" style="letter-spacing:-0.03em;">LUPA PASSWORD</h1>
            <p class="text-xs text-slate-500 font-medium leading-relaxed">Masukkan alamat email akun Anda untuk mendapatkan kode reset password.</p>
        </div>

        {{-- Success Session Alert --}}
        @if(session('status'))
            <div class="mb-5 p-4 rounded-xl text-xs font-semibold border" 
                 style="background:#f0faf3; color:#1e5c33; border-color:rgba(42, 120, 68, 0.15);">
                {{ session('status') }}
            </div>
        @endif

        {{-- Form Area --}}
        <form id="forgot-password-form" action="{{ route('password.email') }}" method="POST" class="space-y-5">
            @csrf
            
            {{-- Email Input --}}
            <div class="space-y-1.5">
                <label class="block text-[9.5px] font-extrabold text-slate-400 uppercase tracking-widest pl-1" for="email">
                    Alamat Email
                </label>
                <div class="flex items-center capsule-input-container rounded-full px-5 py-3.5 gap-3 border border-transparent">
                    <span class="material-symbols-outlined text-[20px] text-[#235347]">mail</span>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required
                           class="bg-transparent border-none p-0 text-sm w-full text-slate-800 placeholder-slate-400 focus:ring-0 focus:outline-none" 
                           placeholder="Masukkan email terdaftar">
                </div>
            </div>

            {{-- Submit Button --}}
            <div class="pt-2">
                <button type="submit" class="w-full flex justify-center items-center gap-2 py-3.5 px-6 rounded-full text-xs font-extrabold text-white uppercase tracking-widest transition-all duration-300 bg-[#235347] hover:bg-[#163832] shadow-md hover:shadow-lg active:scale-[0.98] cursor-pointer group">
                    <span>Kirim Kode Verifikasi</span>
                    <span class="material-symbols-outlined text-sm transition-transform duration-200 group-hover:translate-x-1">send</span>
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

    {{-- ── MODERN POPUP ERROR MODAL ── --}}
    @error('email')
    <div id="email-error-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="background:rgba(5,31,32,.75);backdrop-filter:blur(8px);">
        <div class="bg-white w-full max-w-sm rounded-[32px] p-8 text-center shadow-2xl border border-white/20 relative animate-container">
            <div class="absolute -top-12 left-1/2 -translate-x-1/2 w-24 h-24 rounded-full bg-red-50 flex items-center justify-center border-[8px] border-white shadow-lg">
                <span class="material-symbols-outlined text-red-500 text-[40px] animate-pulse">error</span>
            </div>
            
            <div class="mt-8 space-y-3">
                <h3 class="text-xl font-black text-slate-800 tracking-tight">Email Tidak Ditemukan</h3>
                <p class="text-xs text-slate-500 leading-relaxed font-medium px-2">
                    {{ $message }} Silakan periksa kembali penulisan alamat email Anda atau daftar jika belum memiliki akun.
                </p>
            </div>
            
            <div class="mt-8">
                <button type="button" onclick="closeEmailErrorModal()" class="w-full flex justify-center items-center py-3.5 rounded-2xl text-xs font-extrabold text-white uppercase tracking-widest transition-all duration-300 shadow-md active:scale-[0.98] cursor-pointer" style="background: linear-gradient(135deg, #ef4444 0%, #b91c1c 100%);">
                    Coba Lagi
                </button>
            </div>
        </div>
    </div>
    <script>
        function closeEmailErrorModal() {
            const modal = document.getElementById('email-error-modal');
            if (modal) {
                modal.classList.add('opacity-0', 'transition-opacity', 'duration-300');
                setTimeout(() => { modal.style.display = 'none'; }, 300);
            }
        }
    </script>
    @enderror

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

            document.getElementById('forgot-password-form').addEventListener('submit', function(e) {
                showLoader();
            });

            window.addEventListener('pageshow', function() {
                hideLoader();
            });
        })();
    </script>
</body>
</html>
