@extends('layouts.customer')

@section('title', 'Profil Pengguna')

@section('content')
<!-- Leaflet CSS & JS CDNs -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<style>
    /* ── Premium Modern OTP Styles ── */
    .otp-icon-wrapper {
        position: relative;
        width: 64px;
        height: 64px;
        border-radius: 20px;
        background: linear-gradient(135deg, rgba(42, 120, 68, 0.08) 0%, rgba(142, 182, 155, 0.15) 100%);
        border: 1px solid rgba(42, 120, 68, 0.15);
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 8px 20px rgba(42, 120, 68, 0.04);
        margin: 0 auto;
    }

    .otp-icon-wrapper .material-symbols-outlined {
        font-size: 28px;
        background: linear-gradient(135deg, #2A7844 0%, #1e5c33 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        animation: otp-icon-pulse 2s infinite ease-in-out;
    }

    @keyframes otp-icon-pulse {
        0%, 100% { transform: scale(1); filter: drop-shadow(0 0 0px rgba(42,120,68,0)); }
        50% { transform: scale(1.08); filter: drop-shadow(0 4px 8px rgba(42,120,68,0.2)); }
    }

    .otp-info-banner {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 14px 16px;
        border-radius: 16px;
        background: rgba(240, 253, 244, 0.65);
        border: 1px solid rgba(42, 120, 68, 0.08);
        box-shadow: 0 4px 12px rgba(42, 120, 68, 0.01);
    }

    .otp-digit-custom {
        width: 3.2rem !important;
        height: 3.8rem !important;
        text-align: center !important;
        font-size: 1.5rem !important;
        font-weight: 800 !important;
        border-radius: 14px !important;
        border: 2px solid #E2E8F0 !important;
        background: #F8FAFC !important;
        color: #0F172A !important;
        transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1) !important;
        outline: none !important;
        box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.01) !important;
    }

    .otp-digit-custom:focus {
        border-color: #2A7844 !important;
        background: #FFFFFF !important;
        color: #2A7844 !important;
        box-shadow: 0 0 0 4px rgba(42, 120, 68, 0.15), 0 4px 12px rgba(42, 120, 68, 0.08) !important;
        transform: translateY(-2px) scale(1.04) !important;
    }

    .otp-digit-custom.filled {
        border-color: #8EB69B !important;
        background: #F0FDF4 !important;
        color: #166534 !important;
        box-shadow: 0 4px 10px rgba(42, 120, 68, 0.04) !important;
    }
</style>
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

    {{-- Header Overview Dashboard Card --}}
    <div class="max-w-4xl mx-auto glass-card p-6 md:p-8 relative overflow-hidden" data-aos="fade-down">
        {{-- Decorative background accent --}}
        <div class="absolute -right-24 -top-24 w-48 h-48 rounded-full filter blur-3xl opacity-10 pointer-events-none" style="background:#2A7844;"></div>
        
        <div class="flex flex-col md:flex-row items-center gap-6 relative z-10">
            {{-- Profile Picture Frame with glowing ring --}}
            <div class="relative w-24 h-24 rounded-full p-0.5 shrink-0 shadow-lg"
                 style="background: linear-gradient(135deg, #2A7844 0%, #051F20 100%);">
                <div class="w-full h-full rounded-full overflow-hidden bg-white flex items-center justify-center" id="profile-snapshot-avatar-frame">
                    @if(auth()->user()->foto_profil)
                        @if(config('app.env') === 'production' || \Illuminate\Support\Str::startsWith(auth()->user()->foto_profil, 'profile_photos/'))
                            <img src="{{ Storage::disk('supabase')->url(auth()->user()->foto_profil) }}" alt="{{ auth()->user()->name }}" class="w-full h-full object-cover" id="profile-snapshot-avatar-img">
                        @else
                            <img src="{{ asset('storage/' . auth()->user()->foto_profil) }}" alt="{{ auth()->user()->name }}" class="w-full h-full object-cover" id="profile-snapshot-avatar-img">
                        @endif
                    @else
                        <div class="w-full h-full bg-slate-50 flex items-center justify-center text-slate-800 text-3xl font-extrabold uppercase" id="profile-snapshot-avatar-placeholder">
                            {{ substr(auth()->user()->name, 0, 2) }}
                        </div>
                    @endif
                </div>
            </div>

            {{-- Info Details Grid --}}
            <div class="flex-grow w-full space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-slate-100 pb-3">
                    <div>
                        <h2 class="text-xl font-extrabold" style="color:#051F20;" id="profile-snapshot-name">{{ auth()->user()->name }}</h2>
                        <span class="badge-premium-green py-0.5 px-3 text-[10px] mt-1.5 font-bold uppercase tracking-wider">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-ping"></span>
                            Pelanggan Aktif
                        </span>
                    </div>
                    <div class="text-xs text-slate-400 font-semibold sm:text-right">
                        <span>Bergabung Sejak: </span>
                        <strong class="text-slate-700 block sm:inline ml-1" id="profile-snapshot-joined">{{ auth()->user()->created_at->format('d M Y') }}</strong>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 text-xs font-medium">
                    <div>
                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block mb-0.5">Alamat Email</span>
                        <span class="font-bold text-slate-700 break-all" id="profile-snapshot-email">{{ auth()->user()->email }}</span>
                        
                        <div id="email-verification-status-area" class="mt-1">
                            @if(auth()->user()->email_verified_at)
                                <span class="badge-premium-green py-0.5 px-2 text-[9px] font-bold">
                                    <span class="material-symbols-outlined text-[10px]">verified</span>
                                    Terverifikasi
                                </span>
                            @else
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="badge-premium-amber py-0.5 px-2 text-[9px] font-bold">
                                        <span class="material-symbols-outlined text-[10px]">warning</span>
                                        Belum Verifikasi
                                    </span>
                                    <form action="{{ route('customer.profile.send-verification') }}" method="POST" id="sendEmailVerificationForm" class="inline">
                                        @csrf
                                        <button type="submit" class="text-[10px] text-emerald-700 hover:text-emerald-800 font-extrabold underline transition-colors">
                                            Kirim Verifikasi
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div>
                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block mb-0.5">Nomor WhatsApp</span>
                        <span class="font-bold text-slate-700 flex items-center gap-1">
                            <span class="material-symbols-outlined text-emerald-600" style="font-size:14px;">chat</span>
                            <span id="profile-snapshot-whatsapp">{{ auth()->user()->whatsapp ?? '-' }}</span>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Balanced Form Grid --}}
    <div class="max-w-4xl mx-auto">
        <form action="{{ route('customer.profile.update') }}" method="POST" enctype="multipart/form-data" id="profileUpdateForm" class="space-y-8">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                
                {{-- Left Column: Account Settings & Password --}}
                <div class="glass-card p-6 md:p-8 space-y-6 flex flex-col justify-between" data-aos="fade-right" data-aos-delay="100">
                    <div class="space-y-6">
                        <div>
                            <h3 class="text-base font-extrabold" style="color:#051F20;">Pengaturan Profil & Akun</h3>
                            <p class="text-xs text-slate-400 mt-0.5">Perbarui rincian data diri dan kata sandi akun pelanggan Anda.</p>
                        </div>

                        {{-- Foto Profil Upload --}}
                        <div>
                            <label for="foto_profil" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Foto Profil Baru</label>
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

                        {{-- Personal Data inputs --}}
                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5" for="name">Nama Lengkap</label>
                                <input type="text" name="name" id="name" required value="{{ old('name', auth()->user()->name) }}"
                                       class="premium-input text-xs font-semibold">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5" for="whatsapp">Nomor WhatsApp</label>
                                <input type="text" name="whatsapp" id="whatsapp" required value="{{ old('whatsapp', auth()->user()->whatsapp) }}"
                                       class="premium-input text-xs font-semibold" placeholder="Contoh: 08123456789">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5" for="email">Alamat Surat Elektronik (Email)</label>
                                <input type="email" name="email" id="email" required value="{{ old('email', auth()->user()->email) }}"
                                       class="premium-input text-xs font-semibold">
                            </div>
                        </div>

                        {{-- Password Update --}}
                        <div class="pt-4 border-t border-slate-100 space-y-4">
                            <div class="flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-slate-400" style="font-size:18px;">lock</span>
                                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-800">Ubah Kata Sandi (Opsional)</h4>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5" for="password">Password Baru</label>
                                    <input type="password" name="password" id="password"
                                           class="premium-input text-xs" placeholder="Min. 8 karakter">
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5" for="password_confirmation">Konfirmasi Password</label>
                                    <input type="password" name="password_confirmation" id="password_confirmation"
                                           class="premium-input text-xs" placeholder="Ulangi password">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Right Column: Address & Map Picker --}}
                <div class="glass-card p-6 md:p-8 space-y-6 flex flex-col justify-between" data-aos="fade-left" data-aos-delay="200">
                    <div class="space-y-4 flex-grow">
                        <div>
                            <h3 class="text-base font-extrabold" style="color:#051F20;">Alamat Anda</h3>
                            <p class="text-xs text-slate-400 mt-0.5">Atur alamat lengkap dan titik lokasi peta penerima pesanannya.</p>
                        </div>

                        {{-- Tipe Alamat --}}
                        <div>
                            <span class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Tipe Alamat</span>
                            <div class="flex gap-4">
                                <label class="flex items-center gap-2 cursor-pointer bg-slate-50 hover:bg-slate-100 border border-slate-200 rounded-xl px-4 py-2.5 transition-all w-fit text-xs font-semibold">
                                    <input type="radio" name="tipe_alamat" value="Rumah" class="text-emerald-700 focus:ring-emerald-700 border-slate-300" {{ old('tipe_alamat', auth()->user()->tipe_alamat) == 'Rumah' ? 'checked' : '' }}>
                                    <span class="material-symbols-outlined text-slate-500" style="font-size: 16px;">home</span>
                                    Rumah
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer bg-slate-50 hover:bg-slate-100 border border-slate-200 rounded-xl px-4 py-2.5 transition-all w-fit text-xs font-semibold">
                                    <input type="radio" name="tipe_alamat" value="Kantor" class="text-emerald-700 focus:ring-emerald-700 border-slate-300" {{ old('tipe_alamat', auth()->user()->tipe_alamat) == 'Kantor' ? 'checked' : '' }}>
                                    <span class="material-symbols-outlined text-slate-500" style="font-size: 16px;">corporate_fare</span>
                                    Kantor
                                </label>
                            </div>
                        </div>

                        {{-- Alamat Lengkap --}}
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5" for="alamat">Alamat Lengkap</label>
                            <textarea name="alamat" id="alamat" rows="2" required class="premium-input text-xs font-semibold" placeholder="Tuliskan nama jalan, RT/RW, nomor rumah, kecamatan, kota/kabupaten...">{{ old('alamat', auth()->user()->alamat) }}</textarea>
                        </div>

                        {{-- Map Picker --}}
                        <div class="space-y-2">
                            <span class="block text-xs font-bold text-slate-500 uppercase tracking-wider">Sesuaikan Titik Lokasi Peta</span>
                            
                            {{-- Search Box --}}
                            <div class="flex gap-2">
                                <input type="text" id="map-search-input" class="premium-input text-xs font-semibold flex-grow" placeholder="Cari kota, kecamatan, jalan...">
                                <button type="button" id="map-search-btn" class="btn-premium py-2 px-3 text-xs font-bold whitespace-nowrap">
                                    <span class="material-symbols-outlined" style="font-size: 16px;">search</span>
                                    Cari
                                </button>
                            </div>

                            {{-- Peta --}}
                            <div id="map-container" class="relative rounded-xl overflow-hidden border border-slate-200/80 shadow-sm" style="height: 180px;">
                                <div id="map" class="w-full h-full z-0"></div>
                            </div>
                        </div>

                        {{-- Coordinates inputs --}}
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1" for="latitude">Latitude</label>
                                <input type="text" name="latitude" id="latitude" required readonly value="{{ old('latitude', auth()->user()->latitude) }}"
                                       class="premium-input text-xs font-semibold bg-slate-50 cursor-not-allowed" placeholder="Belum ditandai">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1" for="longitude">Longitude</label>
                                <input type="text" name="longitude" id="longitude" required readonly value="{{ old('longitude', auth()->user()->longitude) }}"
                                       class="premium-input text-xs font-semibold bg-slate-50 cursor-not-allowed" placeholder="Belum ditandai">
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Submit Action Bar --}}
            <div class="flex justify-end pt-2" data-aos="fade-up">
                <button type="submit" class="btn-premium py-3 px-8 shadow-md hover:scale-[1.02] active:scale-[0.98] transition-all">
                    <span class="material-symbols-outlined" style="font-size:16px;">save</span>
                    Simpan Seluruh Perubahan
                </button>
            </div>
        </form>
    </div>

@push('modals')
    {{-- ── PREMIUM EMAIL VERIFICATION MODAL ── --}}
    <div id="emailVerifyModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4"
         style="background-color: rgba(5, 31, 32, 0.4); backdrop-filter: blur(8px); transition: all 0.3s ease;">
        <div class="bg-white rounded-[32px] max-w-md w-full p-6 md:p-8 shadow-2xl relative overflow-hidden border border-slate-100 flex flex-col space-y-6 animate-modal-content-in">
            
            {{-- Close Button --}}
            <button type="button" onclick="closeModal('emailVerifyModal')" class="absolute top-6 right-6 p-2 rounded-2xl hover:bg-slate-100/80 transition-all text-slate-400 hover:text-slate-600 z-10">
                <span class="material-symbols-outlined" style="font-size:20px;">close</span>
            </button>

            {{-- Decorative Gradient Line --}}
            <div class="absolute top-0 left-0 right-0 h-1.5 rounded-t-[32px]" style="background: linear-gradient(90deg, #2A7844 0%, #8EB69B 50%, #2A7844 100%);"></div>

            {{-- Centered Header with Pulsing Icon --}}
            <div class="flex flex-col items-center text-center space-y-3 pt-3">
                <div class="otp-icon-wrapper">
                    <div class="absolute inset-0 rounded-[20px] bg-emerald-500/10 animate-ping opacity-75"></div>
                    <span class="material-symbols-outlined text-emerald-700 relative z-10" style="font-size:28px;">verified_user</span>
                </div>
                <div class="space-y-1">
                    <h3 class="text-lg font-extrabold text-slate-800 tracking-tight">Verifikasi Email Anda</h3>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Masukkan Kode Otentikasi</p>
                </div>
            </div>

            {{-- Info Alert Banner --}}
            <div class="otp-info-banner">
                <span class="material-symbols-outlined text-emerald-600 shrink-0 mt-0.5" style="font-size:18px;">mail</span>
                <div class="text-xs font-semibold text-emerald-800 leading-relaxed text-left">
                    Kode OTP unik (6 digit) telah dikirimkan ke email terdaftar Anda. Silakan masukkan kode untuk memverifikasi akun Anda.
                </div>
            </div>

            <form action="{{ route('customer.profile.verify-email') }}" method="POST" class="space-y-6" id="verifyEmailForm">
                @csrf
                <div class="space-y-4">
                    {{-- 6 Digit Input Grid --}}
                    <div class="flex justify-center gap-2.5 my-2" id="email-otp-inputs">
                        <input type="text" maxlength="1" class="otp-digit otp-digit-custom w-12 h-14 text-center text-xl font-bold rounded-xl border border-slate-200 focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/20 outline-none transition-all bg-slate-50 text-slate-800" data-index="0">
                        <input type="text" maxlength="1" class="otp-digit otp-digit-custom w-12 h-14 text-center text-xl font-bold rounded-xl border border-slate-200 focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/20 outline-none transition-all bg-slate-50 text-slate-800" data-index="1">
                        <input type="text" maxlength="1" class="otp-digit otp-digit-custom w-12 h-14 text-center text-xl font-bold rounded-xl border border-slate-200 focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/20 outline-none transition-all bg-slate-50 text-slate-800" data-index="2">
                        <input type="text" maxlength="1" class="otp-digit otp-digit-custom w-12 h-14 text-center text-xl font-bold rounded-xl border border-slate-200 focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/20 outline-none transition-all bg-slate-50 text-slate-800" data-index="3">
                        <input type="text" maxlength="1" class="otp-digit otp-digit-custom w-12 h-14 text-center text-xl font-bold rounded-xl border border-slate-200 focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/20 outline-none transition-all bg-slate-50 text-slate-800" data-index="4">
                        <input type="text" maxlength="1" class="otp-digit otp-digit-custom w-12 h-14 text-center text-xl font-bold rounded-xl border border-slate-200 focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/20 outline-none transition-all bg-slate-50 text-slate-800" data-index="5">
                    </div>
                    
                    <p class="code-error text-xs text-red-600 mt-1 font-semibold text-center hidden"></p>
                </div>

                <div class="flex flex-col gap-2.5 pt-1">
                    <button type="submit" class="w-full btn-premium py-3.5 text-xs justify-center font-bold shadow-lg flex items-center gap-2 hover:scale-[1.02] active:scale-[0.98] transition-all" style="background: linear-gradient(135deg, #2A7844 0%, #1e5c33 100%); box-shadow: 0 10px 20px rgba(42, 120, 68, 0.25);">
                        <span class="material-symbols-outlined text-[16px]">verified</span>
                        Verifikasi Akun Saya
                    </button>
                    <button type="button" onclick="closeModal('emailVerifyModal')" 
                            class="w-full py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-500 hover:bg-slate-100 transition-all btn-batal">Batal</button>
                </div>
            </form>
            
            <div class="text-center bg-slate-50/50 py-3 rounded-2xl border border-slate-100">
                <button type="button" id="resendEmailVerificationBtn" class="text-emerald-700 font-extrabold hover:underline text-[11px] transition-colors flex items-center justify-center gap-1.5 mx-auto">
                    <span class="material-symbols-outlined text-[14px]">sync</span>
                    Tidak menerima kode OTP? Kirim Ulang
                </button>
            </div>

        </div>
    </div>

    {{-- ── PREMIUM PASSWORD VERIFICATION MODAL ── --}}
    <div id="passwordVerifyModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4"
         style="background-color: rgba(5, 31, 32, 0.4); backdrop-filter: blur(8px); transition: all 0.3s ease;">
        <div class="bg-white rounded-[32px] max-w-md w-full p-6 md:p-8 shadow-2xl relative overflow-hidden border border-slate-100 flex flex-col space-y-6 animate-modal-content-in">
            
            {{-- Close Button --}}
            <button type="button" onclick="closeModal('passwordVerifyModal')" class="absolute top-6 right-6 p-2 rounded-2xl hover:bg-slate-100/80 transition-all text-slate-400 hover:text-slate-600 z-10">
                <span class="material-symbols-outlined" style="font-size:20px;">close</span>
            </button>

            {{-- Decorative Gradient Line --}}
            <div class="absolute top-0 left-0 right-0 h-1.5 rounded-t-[32px]" style="background: linear-gradient(90deg, #2A7844 0%, #8EB69B 50%, #2A7844 100%);"></div>

            {{-- Centered Header with Pulsing Icon --}}
            <div class="flex flex-col items-center text-center space-y-3 pt-3">
                <div class="otp-icon-wrapper">
                    <div class="absolute inset-0 rounded-[20px] bg-emerald-500/10 animate-ping opacity-75"></div>
                    <span class="material-symbols-outlined text-emerald-700 relative z-10" style="font-size:28px;">shield</span>
                </div>
                <div class="space-y-1">
                    <h3 class="text-lg font-extrabold text-slate-800 tracking-tight">Verifikasi 2 Langkah</h3>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Ubah Kata Sandi Akun</p>
                </div>
            </div>

            {{-- Info Alert Banner --}}
            <div class="otp-info-banner">
                <span class="material-symbols-outlined text-emerald-600 shrink-0 mt-0.5" style="font-size:18px;">mail</span>
                <div class="text-xs font-semibold text-emerald-800 leading-relaxed text-left">
                    Kode OTP telah dikirimkan ke email terdaftar Anda untuk memproses pergantian kata sandi baru.
                </div>
            </div>

            <div class="space-y-6">
                <div class="space-y-4">
                    {{-- 6 Digit Input Grid --}}
                    <div class="flex justify-center gap-2.5 my-2" id="password-otp-inputs">
                        <input type="text" maxlength="1" class="otp-digit otp-digit-custom w-12 h-14 text-center text-xl font-bold rounded-xl border border-slate-200 focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/20 outline-none transition-all bg-slate-50 text-slate-800" data-index="0">
                        <input type="text" maxlength="1" class="otp-digit otp-digit-custom w-12 h-14 text-center text-xl font-bold rounded-xl border border-slate-200 focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/20 outline-none transition-all bg-slate-50 text-slate-800" data-index="1">
                        <input type="text" maxlength="1" class="otp-digit otp-digit-custom w-12 h-14 text-center text-xl font-bold rounded-xl border border-slate-200 focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/20 outline-none transition-all bg-slate-50 text-slate-800" data-index="2">
                        <input type="text" maxlength="1" class="otp-digit otp-digit-custom w-12 h-14 text-center text-xl font-bold rounded-xl border border-slate-200 focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/20 outline-none transition-all bg-slate-50 text-slate-800" data-index="3">
                        <input type="text" maxlength="1" class="otp-digit otp-digit-custom w-12 h-14 text-center text-xl font-bold rounded-xl border border-slate-200 focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/20 outline-none transition-all bg-slate-50 text-slate-800" data-index="4">
                        <input type="text" maxlength="1" class="otp-digit otp-digit-custom w-12 h-14 text-center text-xl font-bold rounded-xl border border-slate-200 focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/20 outline-none transition-all bg-slate-50 text-slate-800" data-index="5">
                    </div>
                    
                    <p id="password_otp_error" class="text-xs text-red-600 mt-1 font-semibold text-center hidden"></p>
                </div>

                {{-- Actions --}}
                <div class="flex flex-col gap-2.5 pt-1">
                    <button type="button" id="confirmPasswordChangeBtn" class="w-full btn-premium py-3.5 text-xs justify-center font-bold shadow-lg flex items-center gap-2 hover:scale-[1.02] active:scale-[0.98] transition-all" style="background: linear-gradient(135deg, #2A7844 0%, #1e5c33 100%); box-shadow: 0 10px 20px rgba(42, 120, 68, 0.25);">
                        <span class="material-symbols-outlined text-[16px]">lock_open</span>
                        Verifikasi & Ubah Sandi
                    </button>
                    <button type="button" onclick="closeModal('passwordVerifyModal')" 
                            class="w-full py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-500 hover:bg-slate-100 transition-all btn-batal">Batal</button>
                </div>
            </div>
            
            <div class="text-center bg-slate-50/50 py-3 rounded-2xl border border-slate-100">
                <button type="button" id="resendPasswordOtpBtn" class="text-emerald-700 font-extrabold hover:underline text-[11px] transition-colors flex items-center justify-center gap-1.5 mx-auto">
                    <span class="material-symbols-outlined text-[14px]">sync</span>
                    Tidak menerima kode OTP? Kirim Ulang
                </button>
            </div>
        </div>
    </div>
@endpush

    @if(session('open_verify_modal'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() { window.openModal('emailVerifyModal'); }, 150);
        });
    </script>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Setup modern OTP inputs
            setupOtpInputs('email-otp-inputs');
            setupOtpInputs('password-otp-inputs');

            // Close modals on backdrop click
            document.getElementById('emailVerifyModal').addEventListener('click', function(e) {
                if (e.target === this) window.closeModal('emailVerifyModal');
            });
            document.getElementById('passwordVerifyModal').addEventListener('click', function(e) {
                if (e.target === this) window.closeModal('passwordVerifyModal');
            });

            // AJAX logic for email verification sending
            bindEmailVerifySendListener();
            
            // Resend email verification in modal
            const resendEmailVerificationBtn = document.getElementById('resendEmailVerificationBtn');
            if (resendEmailVerificationBtn) {
                resendEmailVerificationBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    document.getElementById('global-page-loader').style.display = 'flex';
                    fetch("{{ route('customer.profile.send-verification') }}", {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        document.getElementById('global-page-loader').style.display = 'none';
                        if (data.success) {
                            window.showToast(data.message, 'success');
                            clearOtpInputs('email-otp-inputs');
                            startResendCountdown('resendEmailVerificationBtn', 60);
                        } else {
                            window.showToast(data.message || 'Gagal mengirim kode verifikasi.', 'error');
                        }
                    })
                    .catch(err => {
                        document.getElementById('global-page-loader').style.display = 'none';
                        window.showToast('Terjadi kesalahan jaringan.', 'error');
                    });
                });
            }

            // AJAX logic for verify email OTP code submission
            const verifyForm = document.getElementById('verifyEmailForm');
            if (verifyForm) {
                verifyForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const codeDigits = Array.from(document.querySelectorAll('#email-otp-inputs .otp-digit')).map(inp => inp.value).join('');
                    const errEl = verifyForm.querySelector('.code-error');
                    
                    if (codeDigits.length !== 6) {
                        errEl.textContent = 'Harap isi seluruh 6 digit kode OTP.';
                        errEl.classList.remove('hidden');
                        window.showToast('Harap isi seluruh 6 digit kode OTP.', 'error');
                        return;
                    }
                    
                    errEl.classList.add('hidden');
                    document.getElementById('global-page-loader').style.display = 'flex';
                    
                    fetch("{{ route('customer.profile.verify-email') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ code: codeDigits })
                    })
                    .then(res => res.json())
                    .then(data => {
                        document.getElementById('global-page-loader').style.display = 'none';
                        if (data.errors) {
                            errEl.textContent = data.errors.code[0];
                            errEl.classList.remove('hidden');
                            window.showToast(data.errors.code[0], 'error');
                        } else if (data.success) {
                            window.showToast(data.message, 'success');
                            window.closeModal('emailVerifyModal');
                            
                            // Update verification UI
                            const statusArea = document.getElementById('email-verification-status-area');
                            if (statusArea) {
                                statusArea.innerHTML = `
                                    <span class="badge-premium-green py-0.5 px-2.5 text-[9px] w-fit font-bold">
                                        <span class="material-symbols-outlined text-[12px]">verified</span>
                                        Terverifikasi
                                    </span>`;
                            }
                        }
                    })
                    .catch(err => {
                        document.getElementById('global-page-loader').style.display = 'none';
                        window.showToast('Terjadi kesalahan sistem saat verifikasi email.', 'error');
                    });
                });
            }

            // AJAX logic for Profile Edit Form
            const profileForm = document.getElementById('profileUpdateForm');
            if (profileForm) {
                profileForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const passwordInput = document.getElementById('password');
                    const confirmInput = document.getElementById('password_confirmation');
                    
                    if (passwordInput && passwordInput.value.trim() !== '') {
                        if (passwordInput.value !== confirmInput.value) {
                            window.showToast('Password baru dan konfirmasi password tidak cocok.', 'error');
                            return;
                        }
                        
                        // Send OTP code via AJAX first
                        document.getElementById('global-page-loader').style.display = 'flex';
                        
                        fetch("{{ route('customer.profile.send-password-otp') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            }
                        })
                        .then(res => res.json())
                        .then(data => {
                            document.getElementById('global-page-loader').style.display = 'none';
                            if (data.success) {
                                window.showToast(data.message, 'success');
                                window.openModal('passwordVerifyModal');
                                clearOtpInputs('password-otp-inputs');
                                startResendCountdown('resendPasswordOtpBtn', 60);
                            } else {
                                window.showToast(data.message || 'Gagal mengirim kode OTP.', 'error');
                            }
                        })
                        .catch(err => {
                            document.getElementById('global-page-loader').style.display = 'none';
                            window.showToast('Terjadi kesalahan jaringan.', 'error');
                        });
                    } else {
                        // Submit update immediately (no password change)
                        submitProfileData();
                    }
                });
            }

            // Confirm password update OTP
            const confirmPasswordChangeBtn = document.getElementById('confirmPasswordChangeBtn');
            if (confirmPasswordChangeBtn) {
                confirmPasswordChangeBtn.addEventListener('click', function() {
                    const otpDigits = Array.from(document.querySelectorAll('#password-otp-inputs .otp-digit')).map(inp => inp.value).join('');
                    const errEl = document.getElementById('password_otp_error');
                    
                    if (otpDigits.length !== 6) {
                        errEl.textContent = 'Harap isi seluruh 6 digit kode OTP.';
                        errEl.classList.remove('hidden');
                        return;
                    }
                    
                    errEl.classList.add('hidden');
                    submitProfileData(otpDigits);
                });
            }

            // Resend password OTP
            const resendPasswordOtpBtn = document.getElementById('resendPasswordOtpBtn');
            if (resendPasswordOtpBtn) {
                resendPasswordOtpBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    document.getElementById('global-page-loader').style.display = 'flex';
                    
                    fetch("{{ route('customer.profile.send-password-otp') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        document.getElementById('global-page-loader').style.display = 'none';
                        if (data.success) {
                            window.showToast(data.message, 'success');
                            clearOtpInputs('password-otp-inputs');
                            startResendCountdown('resendPasswordOtpBtn', 60);
                        } else {
                            window.showToast(data.message || 'Gagal mengirim ulang kode OTP.', 'error');
                        }
                    })
                    .catch(err => {
                        document.getElementById('global-page-loader').style.display = 'none';
                        window.showToast('Terjadi kesalahan jaringan.', 'error');
                    });
                });
            }

            // Reusable Timer Countdown for OTP Resend Buttons
            let resendTimers = {};
            function startResendCountdown(btnId, durationSeconds) {
                const btn = document.getElementById(btnId);
                if (!btn) return;
                
                clearInterval(resendTimers[btnId]);
                btn.disabled = true;
                btn.classList.add('opacity-50', 'pointer-events-none');
                btn.style.textDecoration = 'none';
                
                let timeLeft = durationSeconds;
                btn.innerHTML = `Kirim ulang OTP dalam <span class="font-black text-emerald-800">${timeLeft}s</span>`;
                
                resendTimers[btnId] = setInterval(() => {
                    timeLeft--;
                    if (timeLeft <= 0) {
                        clearInterval(resendTimers[btnId]);
                        btn.disabled = false;
                        btn.classList.remove('opacity-50', 'pointer-events-none');
                        btn.innerHTML = `Tidak menerima kode OTP? <strong>Kirim Ulang</strong>`;
                        btn.style.textDecoration = 'underline';
                    } else {
                        btn.innerHTML = `Kirim ulang OTP dalam <span class="font-black text-emerald-800">${timeLeft}s</span>`;
                    }
                }, 1000);
            }

            // Helper to auto-advance focus on 6 separate OTP input boxes
            function setupOtpInputs(wrapperId) {
                const wrapper = document.getElementById(wrapperId);
                if (!wrapper) return;
                
                const inputs = wrapper.querySelectorAll('.otp-digit');
                
                inputs.forEach((input, index) => {
                    // Handle keyboard input values
                    input.addEventListener('input', (e) => {
                        const val = e.target.value;
                        // Filter non-digits
                        e.target.value = val.replace(/\D/g, '');
                        if (e.target.value.length > 0) {
                            e.target.value = e.target.value.substring(0, 1);
                            input.classList.add('filled');
                            if (index < inputs.length - 1) {
                                inputs[index + 1].focus();
                            }
                        } else {
                            input.classList.remove('filled');
                        }
                    });
                    
                    // Handle backspaces to jump back
                    input.addEventListener('keydown', (e) => {
                        if (e.key === 'Backspace') {
                            if (input.value.length === 0 && index > 0) {
                                inputs[index - 1].focus();
                                inputs[index - 1].value = '';
                                inputs[index - 1].classList.remove('filled');
                            } else {
                                input.value = '';
                                input.classList.remove('filled');
                            }
                            e.preventDefault();
                        }
                    });
                    
                    // Handle copy pasting a full 6-digit code
                    input.addEventListener('paste', (e) => {
                        e.preventDefault();
                        const pasteData = (e.clipboardData || window.clipboardData).getData('text').trim();
                        if (pasteData.length === 6 && /^\d+$/.test(pasteData)) {
                            inputs.forEach((inp, idx) => {
                                inp.value = pasteData[idx];
                                inp.classList.add('filled');
                            });
                            inputs[5].focus();
                        }
                    });
                });
            }

            // Helper to clear digits
            function clearOtpInputs(wrapperId) {
                const wrapper = document.getElementById(wrapperId);
                if (wrapper) {
                    wrapper.querySelectorAll('.otp-digit').forEach(inp => {
                        inp.value = '';
                        inp.classList.remove('filled');
                    });
                    const firstInput = wrapper.querySelector('.otp-digit');
                    if (firstInput) setTimeout(() => firstInput.focus(), 150);
                }
            }

            // Function to submit profile details
            function submitProfileData(otpCode = null) {
                document.getElementById('global-page-loader').style.display = 'flex';
                
                const formData = new FormData(profileForm);
                if (otpCode) {
                    formData.append('otp_code', otpCode);
                }
                
                fetch("{{ route('customer.profile.update') }}", {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    document.getElementById('global-page-loader').style.display = 'none';
                    
                    if (data.errors) {
                        removeFormErrors(profileForm);
                        
                        if (data.errors.otp_code) {
                            const errEl = document.getElementById('password_otp_error');
                            if (errEl) {
                                errEl.textContent = data.errors.otp_code[0];
                                errEl.classList.remove('hidden');
                            }
                            window.showToast(data.errors.otp_code[0], 'error');
                        } else {
                            showFormErrors(profileForm, data.errors);
                            window.closeModal('passwordVerifyModal');
                            window.showToast('Harap periksa kembali isian form Anda.', 'error');
                        }
                    } else if (data.success) {
                        const user = data.user;
                        
                        // Update UI
                        const snapName = document.getElementById('profile-snapshot-name');
                        if (snapName) snapName.textContent = user.name;
                        
                        const snapEmail = document.getElementById('profile-snapshot-email');
                        if (snapEmail) snapEmail.textContent = user.email;
                        
                        const snapWa = document.getElementById('profile-snapshot-whatsapp');
                        if (snapWa) snapWa.textContent = user.whatsapp;
                        
                        if (user.foto_profil) {
                            const avatarFrame = document.getElementById('profile-snapshot-avatar-frame');
                            if (avatarFrame) {
                                avatarFrame.innerHTML = `<img src="${user.foto_profil}" alt="${user.name}" class="w-full h-full object-cover" id="profile-snapshot-avatar-img">`;
                            }
                            const navAvatarContainer = document.getElementById('customer-navbar-avatar-container');
                            if (navAvatarContainer) {
                                navAvatarContainer.innerHTML = `<img src="${user.foto_profil}" alt="${user.name}" class="w-full h-full object-cover" id="customer-navbar-avatar-img">`;
                            }
                        } else {
                            const initials = user.name.substring(0, 2).toUpperCase();
                            const avatarFrame = document.getElementById('profile-snapshot-avatar-frame');
                            if (avatarFrame) {
                                avatarFrame.innerHTML = `<div class="w-full h-full bg-slate-50 flex items-center justify-center text-slate-800 text-3xl font-extrabold uppercase" id="profile-snapshot-avatar-placeholder">${initials}</div>`;
                            }
                            const navAvatarContainer = document.getElementById('customer-navbar-avatar-container');
                            if (navAvatarContainer) {
                                navAvatarContainer.innerHTML = `<div class="w-full h-full bg-emerald-50 flex items-center justify-center text-emerald-800 text-xs font-bold uppercase" id="customer-navbar-avatar-placeholder">${initials}</div>`;
                            }
                        }
                        
                        document.getElementById('password').value = '';
                        document.getElementById('password_confirmation').value = '';
                        
                        const statusArea = document.getElementById('email-verification-status-area');
                        if (statusArea) {
                            if (user.email_verified) {
                                statusArea.innerHTML = `
                                    <span class="badge-premium-green py-0.5 px-2.5 text-[9px] w-fit font-bold">
                                        <span class="material-symbols-outlined text-[12px]">verified</span>
                                        Terverifikasi
                                    </span>`;
                            } else {
                                statusArea.innerHTML = `
                                    <div class="flex flex-col gap-1">
                                        <span class="badge-premium-amber py-0.5 px-2.5 text-[9px] w-fit font-bold">
                                            <span class="material-symbols-outlined text-[12px]">warning</span>
                                            Belum Verifikasi
                                        </span>
                                        <form action="{{ route('customer.profile.send-verification') }}" method="POST" class="mt-1" id="sendEmailVerificationForm">
                                            @csrf
                                            <button type="submit" class="text-[10px] text-emerald-700 hover:text-emerald-800 font-bold underline transition-colors">
                                                Kirim Kode Verifikasi
                                            </button>
                                        </form>
                                    </div>`;
                                bindEmailVerifySendListener();
                            }
                        }
                        
                        window.closeModal('passwordVerifyModal');
                        window.showToast(data.message, 'success');
                        removeFormErrors(profileForm);
                    }
                })
                .catch(err => {
                    document.getElementById('global-page-loader').style.display = 'none';
                    window.showToast('Terjadi kesalahan sistem saat menyimpan profil.', 'error');
                });
            }

            function bindEmailVerifySendListener() {
                const sendForm = document.getElementById('sendEmailVerificationForm');
                if (sendForm) {
                    sendForm.addEventListener('submit', function(e) {
                        e.preventDefault();
                        document.getElementById('global-page-loader').style.display = 'flex';
                        
                        fetch("{{ route('customer.profile.send-verification') }}", {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            }
                        })
                        .then(res => res.json())
                        .then(data => {
                            document.getElementById('global-page-loader').style.display = 'none';
                            if (data.success) {
                                window.showToast(data.message, 'success');
                                window.openModal('emailVerifyModal');
                            } else {
                                window.showToast(data.message || 'Gagal mengirim kode verifikasi.', 'error');
                            }
                        })
                        .catch(err => {
                            document.getElementById('global-page-loader').style.display = 'none';
                            window.showToast('Terjadi kesalahan jaringan.', 'error');
                        });
                    });
                }
            }

            function showFormErrors(form, errors) {
                for (const field in errors) {
                    const input = form.querySelector(`[name="${field}"]`) || document.getElementById(field);
                    if (input) {
                        input.classList.add('border-red-500');
                        input.classList.add('focus:border-red-500');
                        
                        let errorMsg = input.parentNode.querySelector('.form-field-error');
                        if (!errorMsg) {
                            errorMsg = document.createElement('p');
                            errorMsg.className = 'form-field-error text-xs text-red-600 mt-1 font-semibold';
                            input.parentNode.appendChild(errorMsg);
                        }
                        errorMsg.textContent = errors[field][0];
                    }
                }
            }

            function removeFormErrors(form) {
                form.querySelectorAll('.border-red-500').forEach(input => {
                    input.classList.remove('border-red-500');
                    input.classList.remove('focus:border-red-500');
                });
                form.querySelectorAll('.form-field-error').forEach(msg => {
                    msg.remove();
                });
            }

            // Initialize Leaflet Map
            let defaultLat = parseFloat(document.getElementById('latitude').value) || -0.789275;
            let defaultLng = parseFloat(document.getElementById('longitude').value) || 113.921327;
            let defaultZoom = (document.getElementById('latitude').value && document.getElementById('longitude').value) ? 15 : 5;

            let map = L.map('map', {
                scrollWheelZoom: true
            }).setView([defaultLat, defaultLng], defaultZoom);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            let marker = L.marker([defaultLat, defaultLng], {
                draggable: true
            }).addTo(map);

            function updateCoordinates(lat, lng) {
                document.getElementById('latitude').value = parseFloat(lat).toFixed(8);
                document.getElementById('longitude').value = parseFloat(lng).toFixed(8);
            }

            // Update on drag end
            marker.on('dragend', function(e) {
                let position = marker.getLatLng();
                updateCoordinates(position.lat, position.lng);
            });

            // Update on map click
            map.on('click', function(e) {
                marker.setLatLng(e.latlng);
                updateCoordinates(e.latlng.lat, e.latlng.lng);
            });

            // Force map layout recalculation to prevent gray tiles
            setTimeout(() => {
                map.invalidateSize();
            }, 300);

            // Address Geocoding Search
            const searchInput = document.getElementById('map-search-input');
            const searchBtn = document.getElementById('map-search-btn');

            if (searchBtn && searchInput) {
                searchBtn.addEventListener('click', function() {
                    const query = searchInput.value.trim();
                    if (!query) return;

                    searchBtn.disabled = true;
                    searchBtn.innerHTML = '<span class="material-symbols-outlined text-[16px] animate-spin">sync</span> Mencari...';

                    fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&limit=1`)
                        .then(res => res.json())
                        .then(data => {
                            searchBtn.disabled = false;
                            searchBtn.innerHTML = '<span class="material-symbols-outlined text-[16px]">search</span> Cari Lokasi';
                            
                            if (data && data.length > 0) {
                                const lat = parseFloat(data[0].lat);
                                const lon = parseFloat(data[0].lon);
                                
                                map.setView([lat, lon], 15);
                                marker.setLatLng([lat, lon]);
                                updateCoordinates(lat, lon);
                                window.showToast('Lokasi ditemukan pada peta!', 'success');
                            } else {
                                window.showToast('Lokasi tidak ditemukan. Coba masukkan nama kota/jalan yang lebih spesifik.', 'warning');
                            }
                        })
                        .catch(err => {
                            searchBtn.disabled = false;
                            searchBtn.innerHTML = '<span class="material-symbols-outlined text-[16px]">search</span> Cari Lokasi';
                            window.showToast('Gagal menghubungi layanan pencarian peta.', 'error');
                        });
                });

                // Trigger search on Enter key
                searchInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        searchBtn.click();
                    }
                });
            }
        });
    </script>

</main>
@endsection
