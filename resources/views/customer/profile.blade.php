@extends('layouts.customer')

@section('title', 'Profil Pengguna')

@section('content')
<main class="max-w-[1200px] mx-auto px-6 py-10 space-y-10">

    {{-- Alerts and Notifications --}}
    @if(session('success'))
        <div class="max-w-4xl mx-auto flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium animate-pulse"
             style="background:#DCFCE7; color:#166534; border:1px solid rgba(34, 197, 94, 0.2);">
            <span class="material-symbols-outlined text-emerald-600" style="font-size:20px;">check_circle</span>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if ($errors->any())
        <div class="max-w-4xl mx-auto p-4 rounded-xl space-y-2"
             style="background:#FEE2E2; color:#991B1B; border:1px solid rgba(239, 68, 68, 0.2);">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-red-600" style="font-size:20px;">error</span>
                <span class="font-bold text-sm">Terjadi Kesalahan Validasi:</span>
            </div>
            <ul class="list-disc list-inside text-xs space-y-1 font-medium">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Twin-Column Account Setup Grid --}}
    <div class="max-w-4xl mx-auto grid grid-cols-1 md:grid-cols-12 gap-8">

        {{-- Left Column: High-End Profile Snapshot --}}
        <div class="md:col-span-4 space-y-6" data-aos="fade-right">
            <div class="glass-card p-6 flex flex-col items-center text-center relative overflow-hidden">
                
                {{-- Decorative background accent --}}
                <div class="absolute -right-12 -top-12 w-28 h-28 rounded-full filter blur-2xl opacity-20 pointer-events-none" style="background:#2A7844;"></div>

                {{-- Profile Picture Frame with glowing ring --}}
                <div class="relative w-28 h-28 rounded-full p-1 mb-4 flex items-center justify-center shadow-lg"
                     style="background: linear-gradient(135deg, #2A7844 0%, #051F20 100%);">
                    <div class="w-full h-full rounded-full overflow-hidden bg-white flex items-center justify-center">
                        @if(auth()->user()->foto_profil)
                            <img src="{{ asset('storage/' . auth()->user()->foto_profil) }}" alt="{{ auth()->user()->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-slate-50 flex items-center justify-center text-slate-800 text-3xl font-extrabold uppercase">
                                {{ substr(auth()->user()->name, 0, 2) }}
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Basic Names --}}
                <h2 class="text-base font-extrabold" style="color:#051F20;">{{ auth()->user()->name }}</h2>
                <span class="badge-premium-green py-0.5 px-3 text-[10px] mt-1.5 font-bold uppercase tracking-wider">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-ping"></span>
                    Pelanggan Aktif
                </span>

                <div class="w-full h-px bg-slate-100 my-5"></div>

                {{-- Detail Fields --}}
                <div class="w-full space-y-4 text-left text-xs">
                    <div>
                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block mb-1">Alamat Email</span>
                        <div class="flex flex-col gap-1.5">
                            <span class="font-semibold text-slate-700 break-all leading-normal">{{ auth()->user()->email }}</span>
                            @if(auth()->user()->email_verified_at)
                                <span class="badge-premium-green py-0.5 px-2.5 text-[9px] w-fit font-bold">
                                    <span class="material-symbols-outlined text-[12px]">verified</span>
                                    Terverifikasi
                                </span>
                            @else
                                <div class="flex flex-col gap-1">
                                    <span class="badge-premium-amber py-0.5 px-2.5 text-[9px] w-fit font-bold">
                                        <span class="material-symbols-outlined text-[12px]">warning</span>
                                        Belum Verifikasi
                                    </span>
                                    <form action="{{ route('customer.profile.send-verification') }}" method="POST" class="mt-1">
                                        @csrf
                                        <button type="submit" class="text-[10px] text-emerald-700 hover:text-emerald-800 font-bold underline transition-colors">
                                            Kirim Kode Verifikasi
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div>
                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block mb-0.5">Nomor WhatsApp</span>
                        <span class="font-semibold text-slate-700 flex items-center gap-1">
                            <span class="material-symbols-outlined text-emerald-600" style="font-size:14px;">chat</span>
                            {{ auth()->user()->whatsapp ?? '-' }}
                        </span>
                    </div>

                    <div>
                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block mb-0.5">Bergabung Sejak</span>
                        <span class="font-semibold text-slate-700">{{ auth()->user()->created_at->format('d M Y') }}</span>
                    </div>
                </div>

            </div>
        </div>

        {{-- Right Column: Edit Profile Settings Form --}}
        <div class="md:col-span-8" data-aos="fade-left" data-aos-delay="100">
            <div class="glass-card p-6 md:p-8 space-y-6">
                
                <div>
                    <h3 class="text-base font-extrabold" style="color:#051F20;">Pengaturan Profil & Akun</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Perbarui rincian data diri dan kata sandi akun pelanggan Anda.</p>
                </div>

                <form action="{{ route('customer.profile.update') }}" method="POST" class="space-y-6" enctype="multipart/form-data">
                    @csrf

                    {{-- Dynamic Avatar drag-and-drop box --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Foto Profil Baru</label>
                        <div class="border-2 border-dashed border-slate-200 rounded-xl p-4 text-center hover:border-emerald-600 hover:bg-slate-50/50 transition-all relative cursor-pointer"
                             onclick="document.getElementById('foto_profil').click()">
                            <span class="material-symbols-outlined text-slate-400 text-3xl mb-1.5">cloud_upload</span>
                            <p class="text-xs font-bold text-slate-700">Unggah Gambar</p>
                            <p class="text-[10px] text-slate-400 mt-0.5">Pilih file JPG, PNG, atau JPEG (Maks. 5MB)</p>
                            <input type="file" name="foto_profil" id="foto_profil" class="hidden"
                                   onchange="document.getElementById('selected-file-name').textContent = this.files[0] ? this.files[0].name : ''">
                            <p id="selected-file-name" class="text-xs font-bold text-emerald-700 mt-2"></p>
                        </div>
                    </div>

                    {{-- Form Fields --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2" for="name">Nama Lengkap</label>
                            <input type="text" name="name" id="name" required value="{{ old('name', auth()->user()->name) }}"
                                   class="premium-input text-xs font-semibold">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2" for="whatsapp">Nomor WhatsApp</label>
                            <input type="text" name="whatsapp" id="whatsapp" value="{{ old('whatsapp', auth()->user()->whatsapp) }}"
                                   class="premium-input text-xs font-semibold" placeholder="Contoh: 08123456789">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2" for="email">Alamat Surat Elektronik (Email)</label>
                        <input type="email" name="email" id="email" required value="{{ old('email', auth()->user()->email) }}"
                               class="premium-input text-xs font-semibold">
                    </div>

                    {{-- Password Area --}}
                    <div class="pt-4 border-t border-slate-100 space-y-4">
                        <div class="flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-slate-400" style="font-size:18px;">lock</span>
                            <h4 class="text-xs font-bold uppercase tracking-wider text-slate-800">Ubah Kata Sandi (Opsional)</h4>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2" for="password">Password Baru</label>
                                <input type="password" name="password" id="password"
                                       class="premium-input text-xs" placeholder="Minimal 8 karakter">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2" for="password_confirmation">Konfirmasi Password Baru</label>
                                <input type="password" name="password_confirmation" id="password_confirmation"
                                       class="premium-input text-xs" placeholder="Ulangi password baru">
                            </div>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="pt-4 flex justify-end">
                        <button type="submit" class="btn-premium text-xs py-3 px-6 shadow-md">
                            <span class="material-symbols-outlined" style="font-size:16px;">save</span>
                            Simpan Seluruh Perubahan
                        </button>
                    </div>

                </form>
            </div>
        </div>

    </div>

    {{-- ── PREMIUM EMAIL VERIFICATION MODAL ── --}}
    @if(session('open_verify_modal'))
    <div id="emailVerifyModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4"
         style="background-color: rgba(0,0,0,0.45); backdrop-filter: blur(8px);">
        <div class="bg-white rounded-[28px] max-w-md w-full p-6 md:p-8 shadow-2xl relative overflow-hidden border border-slate-100 flex flex-col space-y-6">
            
            {{-- Decorative Gradient Header --}}
            <div class="absolute top-0 left-0 right-0 h-1 rounded-t-[28px]" style="background: linear-gradient(90deg, #2A7844 0%, #8EB69B 50%, #2A7844 100%);"></div>

            <div class="flex items-center justify-between pt-1">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0" style="background: linear-gradient(135deg, #2A7844, #1e5c33);">
                        <span class="material-symbols-outlined text-white" style="font-size:18px;">verified_user</span>
                    </div>
                    <div>
                        <h3 class="text-sm font-extrabold text-slate-800">Konfirmasi Email</h3>
                        <p class="text-[10px] text-slate-400 font-medium">Masukkan kode OTP Anda</p>
                    </div>
                </div>
                <button onclick="closeModal('emailVerifyModal')" class="p-1.5 rounded-xl hover:bg-slate-100 transition-colors">
                    <span class="material-symbols-outlined" style="color:#94A3B8; font-size:20px;">close</span>
                </button>
            </div>

            <p class="text-xs text-slate-500 leading-relaxed font-medium bg-slate-50 rounded-xl p-3 border border-slate-100">
                <span class="material-symbols-outlined text-emerald-600 align-middle mr-1" style="font-size:14px;">mail</span>
                Kode OTP unik (6 digit) telah dikirimkan ke <strong class="text-slate-700">{{ auth()->user()->email }}</strong>. Silakan masukkan kode untuk memverifikasi akun Anda.
            </p>

            <form action="{{ route('customer.profile.verify-email') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="verification_code" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Kode Otentikasi (6 Digit)</label>
                    <input type="text" name="code" id="verification_code" required maxlength="6" placeholder="• • • • • •" 
                           class="w-full py-3.5 rounded-xl border border-slate-200 focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/20 outline-none text-center text-2xl font-black tracking-[0.5em] text-slate-800 transition-all bg-slate-50">
                    @error('code')
                        <p class="text-xs text-red-600 mt-1 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex flex-col gap-2 pt-1">
                    <button type="submit" class="w-full btn-premium py-3 text-xs justify-center font-bold">
                        <span class="material-symbols-outlined" style="font-size:16px;">verified</span>
                        Verifikasi Akun Saya
                    </button>
                    <button type="button" onclick="closeModal('emailVerifyModal')" 
                            class="w-full py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-500 hover:bg-slate-100 transition-all">
                        Tutup
                    </button>
                </div>
            </form>
            
            <div class="text-center">
                <form action="{{ route('customer.profile.send-verification') }}" method="POST">
                    @csrf
                    <p class="text-[11px] text-slate-400 font-medium">
                        Tidak menerima kode OTP? 
                        <button type="submit" class="text-emerald-700 font-extrabold hover:underline">
                            Kirim Ulang Kode
                        </button>
                    </p>
                </form>
            </div>

        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-open with animation on page load
            setTimeout(function() { openModal('emailVerifyModal'); }, 150);

            // Close on backdrop click
            document.getElementById('emailVerifyModal').addEventListener('click', function(e) {
                if (e.target === this) closeModal('emailVerifyModal');
            });
        });
    </script>
    @endif

</main>
@endsection
