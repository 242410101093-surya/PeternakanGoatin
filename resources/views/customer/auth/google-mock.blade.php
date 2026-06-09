<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Google - Pilih Akun</title>
    <!-- Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet"/>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #F0F4F9;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">

    <!-- Card Container -->
    <div class="w-full max-w-[450px] bg-white rounded-3xl p-8 shadow-[0_4px_30px_rgba(0,0,0,0.03)] border border-slate-100 flex flex-col justify-between min-h-[500px]">
        
        <!-- Header -->
        <div class="space-y-6">
            <!-- Google Logo -->
            <div class="flex justify-center">
                <svg class="h-6 w-auto" viewBox="0 0 24 24">
                    <path fill="#EA4335" d="M12 5.04c1.66 0 3.2.57 4.38 1.69l3.27-3.27C17.68 1.54 14.98 1 12 1 7.35 1 3.37 3.67 1.39 7.56l3.85 2.99c.9-2.69 3.42-4.51 6.76-4.51z"/>
                    <path fill="#4285F4" d="M23.49 12.27c0-.81-.07-1.59-.2-2.36H12v4.51h6.46c-.28 1.48-1.11 2.73-2.36 3.58l3.66 2.84c2.14-1.98 3.39-4.88 3.39-8.57z"/>
                    <path fill="#FBBC05" d="M5.24 14.57c-.23-.69-.36-1.42-.36-2.18s.13-1.49.36-2.18L1.39 7.22C.5 9 .01 10.97.01 13c0 2.03.49 4 1.38 5.78l3.85-2.99c-.23-.69-.36-1.42-.36-2.21z"/>
                    <path fill="#34A853" d="M12 23c3.24 0 5.97-1.07 7.96-2.91l-3.66-2.84c-1.01.68-2.31 1.09-4.3 1.09-3.34 0-5.86-1.82-6.87-4.51l-3.85 2.99C3.37 20.33 7.35 23 12 23z"/>
                </svg>
            </div>
            
            <div class="text-center space-y-1.5">
                <h1 class="text-xl font-bold text-slate-800">Pilih akun</h1>
                <p class="text-xs text-slate-500">untuk melanjutkan ke <span class="font-semibold text-emerald-800">Goatin</span></p>
            </div>

            <!-- List of accounts -->
            <div class="space-y-2.5 pt-4" id="accounts-container">
                
                <!-- Account 1 -->
                <a href="{{ route('auth.google.callback', ['email' => 'ridwan.surya@gmail.com', 'name' => 'M Ridwan Surya Putra']) }}"
                   class="flex items-center gap-3 p-3.5 rounded-2xl hover:bg-slate-50 border border-transparent hover:border-slate-100 transition-all duration-200 group">
                    <div class="w-10 h-10 rounded-full bg-emerald-600 flex items-center justify-center text-white font-extrabold text-sm shadow-sm group-hover:scale-105 transition-transform">
                        R
                    </div>
                    <div class="flex-1 text-left">
                        <div class="text-sm font-semibold text-slate-800">M Ridwan Surya Putra</div>
                        <div class="text-xs text-slate-500">ridwan.surya@gmail.com</div>
                    </div>
                </a>

                <!-- Account 2 -->
                <a href="{{ route('auth.google.callback', ['email' => 'google.demo@goatin.id', 'name' => 'Google Demo User']) }}"
                   class="flex items-center gap-3 p-3.5 rounded-2xl hover:bg-slate-50 border border-transparent hover:border-slate-100 transition-all duration-200 group">
                    <div class="w-10 h-10 rounded-full bg-[#4285F4] flex items-center justify-center text-white font-extrabold text-sm shadow-sm group-hover:scale-105 transition-transform">
                        G
                    </div>
                    <div class="flex-1 text-left">
                        <div class="text-sm font-semibold text-slate-800">Google Demo User</div>
                        <div class="text-xs text-slate-500">google.demo@goatin.id</div>
                    </div>
                </a>

                <!-- Use another account button -->
                <button type="button" onclick="showCustomForm()"
                        class="w-full flex items-center gap-3 p-3.5 rounded-2xl hover:bg-slate-50 border border-transparent hover:border-slate-100 transition-all duration-200 group">
                    <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 group-hover:bg-slate-200 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                        </svg>
                    </div>
                    <div class="text-left">
                        <div class="text-sm font-semibold text-slate-700">Gunakan akun lain</div>
                    </div>
                </button>

            </div>

            <!-- Custom Account Form (Initially hidden) -->
            <form id="custom-account-form" action="{{ route('auth.google.callback') }}" method="GET" class="hidden space-y-4 pt-4">
                <div class="space-y-3">
                    <div>
                        <label for="custom-name" class="block text-xs font-bold text-slate-500 mb-1">Nama Lengkap</label>
                        <input type="text" name="name" id="custom-name" required
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-1 focus:ring-emerald-700/30 focus:border-emerald-700 outline-none text-sm text-slate-800 bg-slate-50/50"
                               placeholder="Nama Lengkap Anda">
                    </div>
                    <div>
                        <label for="custom-email" class="block text-xs font-bold text-slate-500 mb-1">Alamat Email Google</label>
                        <input type="email" name="email" id="custom-email" required
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-1 focus:ring-emerald-700/30 focus:border-emerald-700 outline-none text-sm text-slate-800 bg-slate-50/50"
                               placeholder="nama.anda@gmail.com">
                    </div>
                </div>
                
                <div class="flex items-center justify-between gap-3 pt-2">
                    <button type="button" onclick="hideCustomForm()"
                            class="px-4 py-2 text-xs font-bold text-slate-500 hover:text-slate-700 hover:bg-slate-100 rounded-xl transition-all">
                        Batal
                    </button>
                    <button type="submit"
                            class="px-5 py-2.5 bg-[#4285F4] hover:bg-[#357ae8] text-white text-xs font-extrabold rounded-xl transition-all shadow-sm">
                        Lanjutkan
                    </button>
                </div>
            </form>

        </div>

        <!-- Footer -->
        <footer class="pt-6 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-400">
            <div>
                <select class="bg-transparent border-none p-0 outline-none cursor-pointer text-slate-500 hover:text-slate-700" disabled>
                    <option value="id">Bahasa Indonesia</option>
                    <option value="en">English (US)</option>
                </select>
            </div>
            <div class="flex gap-4">
                <a href="#" class="hover:text-slate-600 transition-colors">Bantuan</a>
                <a href="#" class="hover:text-slate-600 transition-colors">Privasi</a>
                <a href="#" class="hover:text-slate-600 transition-colors">Persyaratan</a>
            </div>
        </footer>

    </div>

    <script>
        function showCustomForm() {
            document.getElementById('accounts-container').classList.add('hidden');
            const form = document.getElementById('custom-account-form');
            form.classList.remove('hidden');
            document.getElementById('custom-name').focus();
        }

        function hideCustomForm() {
            document.getElementById('custom-account-form').classList.add('hidden');
            document.getElementById('accounts-container').classList.remove('hidden');
        }
    </script>
</body>
</html>
