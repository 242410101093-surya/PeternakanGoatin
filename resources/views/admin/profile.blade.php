@extends('layouts.admin')

@section('title', 'Profil Administrator')

@section('content')
<style>
    /* ── Premium Glassmorphism Cards ── */
    .glass-card-profile {
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid rgba(226, 232, 240, 0.8);
        border-radius: 16px;
        box-shadow: 0 4px 30px rgba(5, 31, 32, 0.03), 0 1px 3px rgba(0, 0, 0, 0.02);
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .glass-card-profile:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 40px rgba(5, 31, 32, 0.07), 0 1px 3px rgba(0, 0, 0, 0.02);
        border-color: rgba(42, 120, 68, 0.2);
    }

    /* ── UI Buttons ── */
    .btn-premium-profile {
        background: linear-gradient(135deg, #2A7844 0%, #1e5c33 100%);
        color: #ffffff;
        border-radius: 12px;
        padding: 10px 22px;
        font-weight: 600;
        font-size: 14px;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 4px 14px rgba(42, 120, 68, 0.25);
        transition: all 0.2s ease;
        text-decoration: none;
    }
    .btn-premium-profile:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(42, 120, 68, 0.35);
        filter: brightness(1.05);
    }
    .btn-premium-profile:active {
        transform: translateY(0);
    }

    /* ── Modern Badges ── */
    .badge-premium-blue-profile {
        background: rgba(219, 234, 254, 0.7);
        color: #1E40AF;
        border: 1px solid rgba(59, 130, 246, 0.2);
        backdrop-filter: blur(4px);
        border-radius: 999px;
        padding: 4px 12px;
        font-size: 12px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    /* ── Input styling ── */
    .premium-input-profile {
        width: 100%;
        padding: 11px 16px;
        border-radius: 12px;
        border: 1px solid #E2E8F0;
        background: rgba(255, 255, 255, 0.8);
        font-size: 14px;
        color: #1E293B;
        transition: all 0.2s ease;
    }
    .premium-input-profile:focus {
        outline: none;
        border-color: #2A7844 !important;
        box-shadow: 0 0 0 3px rgba(42, 120, 68, 0.12);
        background: #ffffff;
    }

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

<div class="w-full px-margin-mobile md:px-margin-desktop py-10">
    <div class="max-w-4xl mx-auto space-y-10">

        {{-- Alerts and Notifications --}}
        @if(session('success'))
            <div class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium animate-pulse"
                 style="background:#DCFCE7; color:#166534; border:1px solid rgba(34, 197, 94, 0.2);">
                <span class="material-symbols-outlined text-emerald-600" style="font-size:20px;">check_circle</span>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="p-4 rounded-xl space-y-2"
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
        <div class="grid grid-cols-1 md:grid-cols-12 gap-8">

            {{-- Left Column: High-End Profile Snapshot --}}
            <div class="md:col-span-4 space-y-6">
                <div class="glass-card-profile p-6 flex flex-col items-center text-center relative overflow-hidden">
                    
                    {{-- Decorative background accent --}}
                    <div class="absolute -right-12 -top-12 w-28 h-28 rounded-full filter blur-2xl opacity-20 pointer-events-none" style="background:#0B2B26;"></div>

                    {{-- Profile Picture Frame with glowing ring --}}
                    <div class="relative w-28 h-28 rounded-full p-1 mb-4 flex items-center justify-center shadow-lg"
                         style="background: linear-gradient(135deg, #235347 0%, #051F20 100%);">
                        <div class="w-full h-full rounded-full overflow-hidden bg-white flex items-center justify-center" id="profile-snapshot-avatar-frame">
                            <img src="{{ auth()->user()->foto_profil ? env('SUPABASE_URL') . '/storage/v1/object/public/' . env('SUPABASE_BUCKET') . '/' . auth()->user()->foto_profil . '?render=image' : asset('images/default-avatar.png') }}" alt="{{ auth()->user()->name }}" class="w-full h-full object-cover" id="profile-snapshot-avatar-img" onerror="this.onerror=null; this.src='{{ asset('images/default-avatar.png') }}';">
                        </div>
                    </div>

                    {{-- Basic Names --}}
                    <h2 class="text-base font-extrabold" style="color:#051F20;" id="profile-snapshot-name">{{ auth()->user()->name }}</h2>
                    <span class="badge-premium-blue-profile py-0.5 px-3 text-[10px] mt-1.5 font-bold uppercase tracking-wider">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-ping"></span>
                        Administrator
                    </span>

                    <div class="w-full h-px bg-slate-100 my-5"></div>

                    {{-- Detail Fields --}}
                    <div class="w-full space-y-4 text-left text-xs">
                        <div>
                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block mb-1">Alamat Email</span>
                            <span class="font-semibold text-slate-700 break-all leading-normal" id="profile-snapshot-email">{{ auth()->user()->email }}</span>
                        </div>

                        <div>
                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block mb-0.5">Nomor WhatsApp</span>
                            <span class="font-semibold text-slate-700 flex items-center gap-1">
                                <span class="material-symbols-outlined text-emerald-600" style="font-size:14px;">chat</span>
                                <span id="profile-snapshot-whatsapp">{{ auth()->user()->whatsapp ?? '-' }}</span>
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
            <div class="md:col-span-8">
                <div class="glass-card-profile p-6 md:p-8 space-y-6">
                    
                    <div>
                        <h3 class="text-base font-extrabold" style="color:#051F20;">Pengaturan Profil & Akun</h3>
                        <p class="text-xs text-slate-400 mt-0.5">Perbarui rincian data diri dan kata sandi akun administrator Anda.</p>
                    </div>

                    <form action="{{ route('admin.profile.update') }}" method="POST" class="space-y-6" enctype="multipart/form-data" id="profileUpdateForm">
                        @csrf

                        {{-- Dynamic Avatar drag-and-drop box --}}
                        <div>
                            <label for="foto_profil_file" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Foto Profil Baru</label>
                            <div class="border-2 border-dashed border-slate-200 rounded-xl p-4 text-center hover:border-emerald-600 hover:bg-slate-50/50 transition-all relative cursor-pointer"
                                 onclick="document.getElementById('foto_profil_file').click()">
                                <span class="material-symbols-outlined text-slate-400 text-3xl mb-1.5">cloud_upload</span>
                                <p class="text-xs font-bold text-slate-700">Unggah Gambar</p>
                                <p class="text-[10px] text-slate-400 mt-0.5">Pilih file JPG, PNG, atau JPEG (Maks. 5MB)</p>
                                <input type="file" name="foto_profil" id="foto_profil_file" class="hidden"
                                       onchange="handleProfileImageSelect(this)">
                                <p id="selected-file-name-admin" class="text-xs font-bold text-emerald-700 mt-2"></p>
                            </div>
                        </div>

                        {{-- Form Fields --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2" for="name">Nama Lengkap</label>
                                <input type="text" name="name" id="name" required value="{{ old('name', auth()->user()->name) }}"
                                       class="premium-input-profile text-xs font-semibold">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2" for="whatsapp">Nomor WhatsApp</label>
                                <input type="text" name="whatsapp" id="whatsapp" required value="{{ old('whatsapp', auth()->user()->whatsapp) }}"
                                       class="premium-input-profile text-xs font-semibold" placeholder="Contoh: 08123456789">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2" for="email">Alamat Surat Elektronik (Email)</label>
                            <input type="email" name="email" id="email" required value="{{ old('email', auth()->user()->email) }}"
                                   class="premium-input-profile text-xs font-semibold">
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
                                           class="premium-input-profile text-xs" placeholder="Minimal 8 karakter">
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2" for="password_confirmation">Konfirmasi Password Baru</label>
                                    <input type="password" name="password_confirmation" id="password_confirmation"
                                           class="premium-input-profile text-xs" placeholder="Ulangi password baru">
                                </div>
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="pt-4 flex justify-end">
                            <button type="submit" class="btn-premium-profile text-xs py-3 px-6 shadow-md">
                                <span class="material-symbols-outlined" style="font-size:16px;">save</span>
                                Simpan Seluruh Perubahan
                            </button>
                        </div>

                    </form>
                </div>
            </div>

        </div>

    </div>
</div>

@push('modals')
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
        
        {{-- Resend OTP Section --}}
        <div class="text-center bg-slate-50/50 py-3 rounded-2xl border border-slate-100">
            <button type="button" id="resendPasswordOtpBtn" class="text-emerald-700 font-extrabold hover:underline text-[11px] transition-colors flex items-center justify-center gap-1.5 mx-auto">
                <span class="material-symbols-outlined text-[14px]">sync</span>
                Tidak menerima kode OTP? Kirim Ulang
            </button>
        </div>
    </div>
</div>
@endpush

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Setup modern OTP inputs
        setupOtpInputs('password-otp-inputs');

        // Close modal on backdrop click
        document.getElementById('passwordVerifyModal').addEventListener('click', function(e) {
            if (e.target === this) window.closeModal('passwordVerifyModal');
        });

        // Handle Profile Image Selection and Size Validation
        window.handleProfileImageSelect = function(input) {
            const fileNameEl = document.getElementById('selected-file-name-admin');
            if (input.files && input.files[0]) {
                const file = input.files[0];
                if (file.size > 5 * 1024 * 1024) { // 5MB
                    if (window.showToast) window.showToast('Ukuran foto profil maksimal adalah 5MB.', 'error');
                    else alert('Ukuran foto profil maksimal adalah 5MB.');
                    input.value = '';
                    fileNameEl.textContent = '';
                    return;
                }
                fileNameEl.textContent = file.name;
            } else {
                fileNameEl.textContent = '';
            }
        };

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
                    
                    fetch("{{ route('admin.profile.send-password-otp') }}", {
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
                
                fetch("{{ route('admin.profile.send-password-otp') }}", {
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
                    btn.innerHTML = `<span class="material-symbols-outlined text-[14px]">sync</span> Tidak menerima kode OTP? <strong>Kirim Ulang</strong>`;
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

        function submitProfileData(otpCode = null) {
            document.getElementById('global-page-loader').style.display = 'flex';
            
            const formData = new FormData(profileForm);
            if (otpCode) {
                formData.append('otp_code', otpCode);
            }
            
            fetch("{{ route('admin.profile.update') }}", {
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
                    
                    if (user.foto_profil_raw) {
                        const avatarUrl = `{{ env('SUPABASE_URL') }}/storage/v1/object/public/{{ env('SUPABASE_BUCKET') }}/${user.foto_profil_raw}?render=image`;
                        const avatarFrame = document.getElementById('profile-snapshot-avatar-frame');
                        if (avatarFrame) {
                            avatarFrame.innerHTML = `<img src="${avatarUrl}" alt="${user.name}" class="w-full h-full object-cover" id="profile-snapshot-avatar-img" onerror="this.onerror=null; this.src='/images/default-avatar.png';">`;
                        }
                        const navAvatarContainer = document.getElementById('admin-navbar-avatar-container');
                        if (navAvatarContainer) {
                            navAvatarContainer.innerHTML = `<img src="${avatarUrl}" alt="${user.name}" class="w-full h-full object-cover" id="admin-navbar-avatar-img" onerror="this.onerror=null; this.src='/images/default-avatar.png';">`;
                        }
                    } else {
                        const initials = user.name.substring(0, 2).toUpperCase();
                        const avatarFrame = document.getElementById('profile-snapshot-avatar-frame');
                        if (avatarFrame) {
                            avatarFrame.innerHTML = `<div class="w-full h-full bg-slate-50 flex items-center justify-center text-slate-800 text-3xl font-extrabold uppercase" id="profile-snapshot-avatar-placeholder">${initials}</div>`;
                        }
                        const navAvatarContainer = document.getElementById('admin-navbar-avatar-container');
                        if (navAvatarContainer) {
                            navAvatarContainer.innerHTML = `<div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-primary-green to-emerald-700 text-white font-extrabold" id="admin-navbar-avatar-placeholder">${initials}</div>`;
                        }
                    }
                    
                    document.getElementById('password').value = '';
                    document.getElementById('password_confirmation').value = '';
                    
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
            // Batasi hanya menghapus elemen teks span/p error agar tidak merusak DOM kontainer avatar
            form.querySelectorAll('span.form-field-error, p.form-field-error').forEach(msg => {
                msg.remove();
            });
        }
    });
</script>
@endsection
