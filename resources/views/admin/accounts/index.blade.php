@extends('layouts.admin')

@section('title', 'Manajemen Akun')

@section('content')
<div class="w-full px-margin-mobile md:px-margin-desktop py-stack-md">
    <div class="max-w-container-max mx-auto space-y-stack-xl">
        <!-- Alerts -->
        @if(session('success'))
            <div class="bg-primary/10 border border-primary text-on-primary-container px-4 py-3 rounded-lg relative" role="alert">
                <span class="block sm:inline font-body-md text-body-md">{{ session('success') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="bg-error/10 border border-error text-error px-4 py-3 rounded-lg relative" role="alert">
                <span class="block sm:inline font-body-md text-body-md">{{ session('error') }}</span>
            </div>
        @endif

        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <h2 class="font-h2 text-h2 text-on-surface mb-1">Manajemen Akun</h2>
                <p class="font-body-md text-body-md text-on-surface-variant">Kelola pengguna, peran, dan hak akses platform.</p>
            </div>
            <div class="flex items-center gap-3">
                <button onclick="window.dispatchEvent(new CustomEvent('open-add-account-modal'))" class="flex items-center gap-2 px-5 py-2.5 rounded-lg bg-primary text-on-primary font-label-sm text-label-sm hover:bg-primary-container transition-colors shadow-sm">
                    <span class="material-symbols-outlined text-sm">add</span>
                    Tambah Pengguna Baru
                </button>
            </div>
        </div>

        <!-- Bento Grid / Glassmorphism approach for Table Area -->
        <div class="bg-transparent flex flex-col mt-4">
            <!-- Toolbar -->
            <div class="px-2 mb-4 flex flex-col sm:flex-row gap-4 items-center justify-between">
                <div class="flex items-center gap-2 w-full sm:w-auto">
                    <div class="relative w-full sm:w-64">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-[18px]">search</span>
                        <input id="search_input" onkeypress="handleSearchKeyPress(event)" value="{{ request('search') }}" class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-white border border-slate-200 focus:border-[#2A7844] focus:ring-2 focus:ring-[#2A7844]/20 text-slate-800 font-body-md shadow-sm outline-none text-sm transition-all" placeholder="Cari nama atau email..." type="text"/>
                    </div>
                    <button onclick="filterUsers()" class="w-10 h-10 bg-[#2A7844] text-white rounded-xl hover:bg-[#1e5c33] transition-colors flex items-center justify-center whitespace-nowrap shadow-sm hover:shadow-md">
                        <span class="material-symbols-outlined text-[20px]">search</span>
                    </button>
                </div>
                <div class="flex items-center flex-wrap gap-3 w-full sm:w-auto">
                    <!-- Filter Role (Custom Dropdown) -->
                    <div x-data="{ 
                        open: false, 
                        value: '{{ request('role') }}', 
                        label: '{{ request('role') === 'admin' ? 'Admin' : (request('role') === 'user' ? 'Pelanggan' : 'Semua Peran') }}',
                        select(val, lbl) { 
                            this.value = val; 
                            this.label = lbl; 
                            this.open = false; 
                            document.getElementById('filter_role').value = val; 
                            filterUsers(); 
                        }
                    }" class="relative w-40">
                        <input type="hidden" id="filter_role" value="{{ request('role') }}">
                        <button @click="open = !open" @click.away="open = false" type="button" class="w-full flex items-center justify-between pl-4 pr-3 py-2.5 rounded-xl bg-white border border-slate-200 text-slate-700 font-bold text-sm shadow-sm hover:border-[#2A7844] focus:ring-2 focus:ring-[#2A7844]/20 transition-all cursor-pointer">
                            <span x-text="label" class="truncate"></span>
                            <span class="material-symbols-outlined text-[20px] text-slate-400 transition-transform duration-300" :class="open ? 'rotate-180' : ''">expand_more</span>
                        </button>
                        <div x-show="open" x-transition.opacity.duration.200ms class="absolute z-50 mt-2 w-full bg-white rounded-xl shadow-xl border border-slate-100 py-1 overflow-hidden" style="display: none;">
                            <button @click="select('', 'Semua Peran')" type="button" class="w-full text-left px-4 py-2.5 text-sm font-medium hover:bg-emerald-50 hover:text-[#2A7844] transition-colors" :class="value === '' ? 'bg-emerald-50 text-[#2A7844]' : 'text-slate-600'">Semua Peran</button>
                            <button @click="select('admin', 'Admin')" type="button" class="w-full text-left px-4 py-2.5 text-sm font-medium hover:bg-emerald-50 hover:text-[#2A7844] transition-colors" :class="value === 'admin' ? 'bg-emerald-50 text-[#2A7844]' : 'text-slate-600'">Admin</button>
                            <button @click="select('user', 'Pelanggan')" type="button" class="w-full text-left px-4 py-2.5 text-sm font-medium hover:bg-emerald-50 hover:text-[#2A7844] transition-colors" :class="value === 'user' ? 'bg-emerald-50 text-[#2A7844]' : 'text-slate-600'">Pelanggan</button>
                        </div>
                    </div>

                    <!-- Filter Status (Custom Dropdown) -->
                    <div x-data="{ 
                        open: false, 
                        value: '{{ request('status') }}', 
                        label: '{{ request('status') === 'active' ? 'Aktif' : (request('status') === 'inactive' ? 'Tidak Aktif' : 'Semua Status') }}',
                        select(val, lbl) { 
                            this.value = val; 
                            this.label = lbl; 
                            this.open = false; 
                            document.getElementById('filter_status').value = val; 
                            filterUsers(); 
                        }
                    }" class="relative w-44">
                        <input type="hidden" id="filter_status" value="{{ request('status') }}">
                        <button @click="open = !open" @click.away="open = false" type="button" class="w-full flex items-center justify-between pl-4 pr-3 py-2.5 rounded-xl bg-white border border-slate-200 text-slate-700 font-bold text-sm shadow-sm hover:border-[#2A7844] focus:ring-2 focus:ring-[#2A7844]/20 transition-all cursor-pointer">
                            <span x-text="label" class="truncate"></span>
                            <span class="material-symbols-outlined text-[20px] text-slate-400 transition-transform duration-300" :class="open ? 'rotate-180' : ''">expand_more</span>
                        </button>
                        <div x-show="open" x-transition.opacity.duration.200ms class="absolute z-50 mt-2 w-full bg-white rounded-xl shadow-xl border border-slate-100 py-1 overflow-hidden" style="display: none;">
                            <button @click="select('', 'Semua Status')" type="button" class="w-full text-left px-4 py-2.5 text-sm font-medium hover:bg-emerald-50 hover:text-[#2A7844] transition-colors" :class="value === '' ? 'bg-emerald-50 text-[#2A7844]' : 'text-slate-600'">Semua Status</button>
                            <button @click="select('active', 'Aktif')" type="button" class="w-full text-left px-4 py-2.5 text-sm font-medium hover:bg-emerald-50 hover:text-[#2A7844] transition-colors" :class="value === 'active' ? 'bg-emerald-50 text-[#2A7844]' : 'text-slate-600'">Aktif</button>
                            <button @click="select('inactive', 'Tidak Aktif')" type="button" class="w-full text-left px-4 py-2.5 text-sm font-medium hover:bg-emerald-50 hover:text-[#2A7844] transition-colors" :class="value === 'inactive' ? 'bg-emerald-50 text-[#2A7844]' : 'text-slate-600'">Tidak Aktif</button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Table Container -->
            <div class="overflow-x-auto pb-8 pt-2">
                <table class="w-full text-left border-separate whitespace-nowrap" style="border-spacing: 0 12px;">
                    <thead>
                        <tr class="font-label-sm text-xs text-slate-400 uppercase tracking-widest font-bold">
                            <th class="pb-2 px-6">Pengguna</th>
                            <th class="pb-2 px-6">Peran</th>
                            <th class="pb-2 px-6">Status</th>
                            <th class="pb-2 px-6">Tanggal Daftar</th>
                            <th class="pb-2 px-6 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="font-body-md text-sm">
                        @forelse ($users as $user)
                        <tr class="bg-white hover:bg-[#f8fdfa] transition-all duration-300 shadow-[0_4px_20px_rgba(0,0,0,0.03)] hover:shadow-[0_8px_30px_rgba(42,120,68,0.12)] group cursor-default transform hover:-translate-y-1">
                            <td class="py-5 px-6 rounded-l-2xl border-y border-l border-slate-100 group-hover:border-[#2A7844]/20 transition-colors">
                                <div class="flex items-center gap-4">
                                    <div class="w-11 h-11 rounded-full bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-400 font-bold text-sm uppercase group-hover:bg-[#2A7844]/10 group-hover:text-[#2A7844] group-hover:border-[#2A7844]/20 transition-colors">
                                        {{ substr($user->name, 0, 2) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-700 text-base">{{ $user->name }}</p>
                                        <p class="font-caption text-caption text-slate-500 mt-0.5">{{ $user->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-5 px-6 border-y border-slate-100 group-hover:border-[#2A7844]/20 transition-colors">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl border text-xs font-bold shadow-sm {{ $user->role === 'admin' ? 'bg-indigo-50 text-indigo-600 border-indigo-200' : 'bg-slate-50 text-slate-600 border-slate-200' }} uppercase">
                                    {{ $user->role === 'admin' ? 'Admin' : 'Pelanggan' }}
                                </span>
                            </td>
                            <td class="py-5 px-6 border-y border-slate-100 group-hover:border-[#2A7844]/20 transition-colors">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full border text-xs font-bold shadow-sm {{ $user->status === 'active' ? 'bg-emerald-50 text-emerald-600 border-emerald-200' : 'bg-red-50 text-red-600 border-red-200' }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $user->status === 'active' ? 'bg-emerald-500' : 'bg-red-500' }}"></span>
                                    {{ $user->status === 'active' ? 'Aktif' : 'Tidak Aktif' }}
                                </span>
                            </td>
                            <td class="py-5 px-6 border-y border-slate-100 group-hover:border-[#2A7844]/20 text-slate-600 font-medium transition-colors">
                                {{ $user->created_at->format('d M Y') }}
                            </td>
                            <td class="py-5 px-6 rounded-r-2xl border-y border-r border-slate-100 group-hover:border-[#2A7844]/20 text-right transition-colors">
                                <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity translate-x-4 group-hover:translate-x-0 duration-300">
                                    <button onclick="openEditAccountModal({{ json_encode($user) }})" class="w-8 h-8 flex items-center justify-center bg-white border border-slate-200 text-slate-400 hover:text-[#2A7844] hover:border-[#2A7844] hover:bg-[#2A7844]/5 rounded-xl shadow-sm transition-all" title="Edit Data">
                                        <span class="material-symbols-outlined text-[16px]">edit</span>
                                    </button>
                                    @if($user->id !== auth()->id())
                                        <form action="{{ route('admin.accounts.destroy', $user->id) }}" method="POST" class="inline delete-form" data-message="Yakin ingin menghapus akun {{ $user->name }}? Tindakan ini tidak bisa dibatalkan.">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-8 h-8 flex items-center justify-center bg-white border border-slate-200 text-slate-400 hover:text-red-500 hover:border-red-500 hover:bg-red-50 rounded-xl shadow-sm transition-all" title="Hapus Permanen">
                                                <span class="material-symbols-outlined text-[16px]">delete</span>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-16 px-6 text-center text-slate-400 bg-white rounded-3xl border border-dashed border-slate-200">
                                <div class="w-16 h-16 mx-auto mb-4 bg-slate-50 rounded-full flex items-center justify-center">
                                    <span class="material-symbols-outlined text-3xl opacity-50">group_off</span>
                                </div>
                                <p class="font-medium text-slate-500">Tidak ada pengguna ditemukan.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="py-2">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Modal Add Account -->
<div x-data="{ open: false }" @open-add-account-modal.window="open = true" class="relative z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" style="display: none;"></div>

    <div class="fixed inset-0 z-10 w-screen overflow-y-auto" x-show="open" style="display: none;">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div x-show="open" @click.away="open = false" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="relative transform overflow-hidden rounded-[24px] bg-white text-left shadow-[0_20px_60px_rgba(5,31,32,0.15)] transition-all sm:my-8 sm:w-full sm:max-w-lg border border-slate-100">
                <div class="px-8 py-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center">
                            <span class="material-symbols-outlined text-xl">person_add</span>
                        </div>
                        <h3 class="text-xl font-bold text-slate-800">Tambah Akun</h3>
                    </div>
                    <button type="button" @click="open = false" class="w-8 h-8 flex items-center justify-center rounded-full text-slate-400 hover:bg-slate-100 hover:text-slate-600 transition-colors">
                        <span class="material-symbols-outlined text-xl">close</span>
                    </button>
                </div>
                <form action="{{ route('admin.accounts.store') }}" method="POST" class="p-8 space-y-6">
                    @csrf
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Nama Lengkap</label>
                        <input type="text" name="name" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-[#2A7844] focus:ring-2 focus:ring-[#2A7844]/20 bg-slate-50 focus:bg-white text-slate-800 font-medium transition-all outline-none">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Email</label>
                        <input type="email" name="email" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-[#2A7844] focus:ring-2 focus:ring-[#2A7844]/20 bg-slate-50 focus:bg-white text-slate-800 font-medium transition-all outline-none">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Password</label>
                        <input type="password" name="password" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-[#2A7844] focus:ring-2 focus:ring-[#2A7844]/20 bg-slate-50 focus:bg-white text-slate-800 font-medium transition-all outline-none">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Role</label>
                        <div class="relative">
                            <select name="role" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-[#2A7844] focus:ring-2 focus:ring-[#2A7844]/20 bg-slate-50 focus:bg-white text-slate-800 font-medium transition-all outline-none appearance-none cursor-pointer">
                                <option value="user">Pelanggan</option>
                                <option value="admin">Admin</option>
                            </select>
                            <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">expand_more</span>
                        </div>
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Nomor WhatsApp</label>
                        <input type="text" name="whatsapp" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-[#2A7844] focus:ring-2 focus:ring-[#2A7844]/20 bg-slate-50 focus:bg-white text-slate-800 font-medium transition-all outline-none" placeholder="Contoh: 08123456789">
                    </div>
                    <div class="pt-6 mt-2 flex justify-end gap-3 border-t border-slate-100">
                        <button type="button" @click="open = false" class="px-6 py-2.5 font-bold text-sm text-slate-500 hover:bg-slate-100 rounded-xl transition-colors">Batal</button>
                        <button type="submit" class="px-6 py-2.5 font-bold text-sm bg-gradient-to-r from-[#2A7844] to-[#1e5c33] text-white hover:shadow-lg hover:shadow-[#2A7844]/30 hover:-translate-y-0.5 rounded-xl transition-all">Simpan Akun</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Account -->
<div x-data="{ open: false }" @open-edit-account-modal.window="open = true" class="relative z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" style="display: none;"></div>

    <div class="fixed inset-0 z-10 w-screen overflow-y-auto" x-show="open" style="display: none;">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div x-show="open" @click.away="open = false" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="relative transform overflow-hidden rounded-[24px] bg-white text-left shadow-[0_20px_60px_rgba(5,31,32,0.15)] transition-all sm:my-8 sm:w-full sm:max-w-lg border border-slate-100">
                <div class="px-8 py-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center">
                            <span class="material-symbols-outlined text-xl">manage_accounts</span>
                        </div>
                        <h3 class="text-xl font-bold text-slate-800">Edit Akun</h3>
                    </div>
                    <button type="button" @click="open = false" class="w-8 h-8 flex items-center justify-center rounded-full text-slate-400 hover:bg-slate-100 hover:text-slate-600 transition-colors">
                        <span class="material-symbols-outlined text-xl">close</span>
                    </button>
                </div>
                <form id="editAccountForm" method="POST" class="p-8 space-y-6">
                    @csrf
                    @method('PUT')
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Nama Lengkap</label>
                        <input type="text" name="name" id="edit_name" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-[#2A7844] focus:ring-2 focus:ring-[#2A7844]/20 bg-slate-50 focus:bg-white text-slate-800 font-medium transition-all outline-none">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Email</label>
                        <input type="email" name="email" id="edit_email" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-[#2A7844] focus:ring-2 focus:ring-[#2A7844]/20 bg-slate-50 focus:bg-white text-slate-800 font-medium transition-all outline-none">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Password Baru <span class="text-[10px] font-normal normal-case">(Biarkan kosong jika tidak diubah)</span></label>
                        <input type="password" name="password" placeholder="Minimal 6 karakter" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-[#2A7844] focus:ring-2 focus:ring-[#2A7844]/20 bg-slate-50 focus:bg-white text-slate-800 font-medium transition-all outline-none">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Role</label>
                        <div class="relative">
                            <select name="role" id="edit_role" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-[#2A7844] focus:ring-2 focus:ring-[#2A7844]/20 bg-slate-50 focus:bg-white text-slate-800 font-medium transition-all outline-none appearance-none cursor-pointer">
                                <option value="user">Pelanggan</option>
                                <option value="admin">Admin</option>
                            </select>
                            <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">expand_more</span>
                        </div>
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Nomor WhatsApp</label>
                        <input type="text" name="whatsapp" id="edit_whatsapp" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-[#2A7844] focus:ring-2 focus:ring-[#2A7844]/20 bg-slate-50 focus:bg-white text-slate-800 font-medium transition-all outline-none" placeholder="Contoh: 08123456789">
                    </div>
                    <div class="pt-6 mt-2 flex justify-end gap-3 border-t border-slate-100">
                        <button type="button" @click="open = false" class="px-6 py-2.5 font-bold text-sm text-slate-500 hover:bg-slate-100 rounded-xl transition-colors">Batal</button>
                        <button type="submit" class="px-6 py-2.5 font-bold text-sm bg-gradient-to-r from-[#2A7844] to-[#1e5c33] text-white hover:shadow-lg hover:shadow-[#2A7844]/30 hover:-translate-y-0.5 rounded-xl transition-all">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function filterUsers() {
        const search = document.getElementById('search_input').value;
        const role = document.getElementById('filter_role').value;
        const status = document.getElementById('filter_status').value;
        
        let url = new URL(window.location.href);
        if (search) {
            url.searchParams.set('search', search);
        } else {
            url.searchParams.delete('search');
        }
        
        if (role) {
            url.searchParams.set('role', role);
        } else {
            url.searchParams.delete('role');
        }

        if (status) {
            url.searchParams.set('status', status);
        } else {
            url.searchParams.delete('status');
        }
        
        url.searchParams.delete('page');
        
        window.location.href = url.toString();
    }

    function handleSearchKeyPress(event) {
        if (event.key === 'Enter') {
            filterUsers();
        }
    }

    function openEditAccountModal(user) {
        document.getElementById('editAccountForm').action = `/admin/accounts/${user.id}`;
        document.getElementById('edit_name').value = user.name;
        document.getElementById('edit_email').value = user.email;
        document.getElementById('edit_role').value = user.role;
        document.getElementById('edit_whatsapp').value = user.whatsapp || '';
        window.dispatchEvent(new CustomEvent('open-edit-account-modal'));
    }
</script>
@endsection
