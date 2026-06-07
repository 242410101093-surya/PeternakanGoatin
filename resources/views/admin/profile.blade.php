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
                    <span class="badge-premium-blue-profile py-0.5 px-3 text-[10px] mt-1.5 font-bold uppercase tracking-wider">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-ping"></span>
                        Administrator
                    </span>

                    <div class="w-full h-px bg-slate-100 my-5"></div>

                    {{-- Detail Fields --}}
                    <div class="w-full space-y-4 text-left text-xs">
                        <div>
                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block mb-1">Alamat Email</span>
                            <span class="font-semibold text-slate-700 break-all leading-normal">{{ auth()->user()->email }}</span>
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
            <div class="md:col-span-8">
                <div class="glass-card-profile p-6 md:p-8 space-y-6">
                    
                    <div>
                        <h3 class="text-base font-extrabold" style="color:#051F20;">Pengaturan Profil & Akun</h3>
                        <p class="text-xs text-slate-400 mt-0.5">Perbarui rincian data diri dan kata sandi akun administrator Anda.</p>
                    </div>

                    <form action="{{ route('admin.profile.update') }}" method="POST" class="space-y-6" enctype="multipart/form-data">
                        @csrf

                        {{-- Dynamic Avatar drag-and-drop box --}}
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Foto Profil Baru</label>
                            <div class="border-2 border-dashed border-slate-200 rounded-xl p-4 text-center hover:border-emerald-600 hover:bg-slate-50/50 transition-all relative cursor-pointer"
                                 onclick="document.getElementById('foto_profil_file').click()">
                                <span class="material-symbols-outlined text-slate-400 text-3xl mb-1.5">cloud_upload</span>
                                <p class="text-xs font-bold text-slate-700">Unggah Gambar</p>
                                <p class="text-[10px] text-slate-400 mt-0.5">Pilih file JPG, PNG, atau JPEG (Maks. 5MB)</p>
                                <input type="file" name="foto_profil" id="foto_profil_file" class="hidden"
                                       onchange="document.getElementById('selected-file-name-admin').textContent = this.files[0] ? this.files[0].name : ''">
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
                                <input type="text" name="whatsapp" id="whatsapp" value="{{ old('whatsapp', auth()->user()->whatsapp) }}"
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
@endsection
