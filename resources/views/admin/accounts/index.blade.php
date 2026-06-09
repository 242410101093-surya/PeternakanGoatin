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
                <button onclick="document.getElementById('addAccountModal').classList.remove('hidden')" class="flex items-center gap-2 px-5 py-2.5 rounded-lg bg-primary text-on-primary font-label-sm text-label-sm hover:bg-primary-container transition-colors shadow-sm">
                    <span class="material-symbols-outlined text-sm">add</span>
                    Tambah Pengguna Baru
                </button>
            </div>
        </div>

        <!-- Bento Grid / Glassmorphism approach for Table Area -->
        <div class="bg-surface-container-lowest rounded-xl border border-surface-variant ambient-shadow overflow-hidden">
            <!-- Toolbar -->
            <div class="p-4 border-b border-surface-variant flex flex-col sm:flex-row gap-4 items-center justify-between bg-surface-bright">
                <div class="flex items-center gap-2 w-full sm:w-auto">
                    <div class="relative w-full sm:w-64">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-sm">search</span>
                        <input id="search_input" onkeypress="handleSearchKeyPress(event)" value="{{ request('search') }}" class="w-full pl-9 pr-3 py-1.5 rounded-md bg-surface-container border border-outline-variant focus:border-primary text-body-md font-body-md outline-none text-sm transition-all" placeholder="Cari nama atau email..." type="text"/>
                    </div>
                    <button onclick="filterUsers()" class="px-3 py-1.5 bg-primary text-on-primary rounded-md font-label-sm text-sm hover:bg-primary-container transition-colors">
                        Cari
                    </button>
                </div>
                <div class="flex items-center gap-2 w-full sm:w-auto overflow-x-auto pb-1 sm:pb-0">
                    <span class="font-label-sm text-label-sm text-on-surface-variant whitespace-nowrap">Role:</span>
                    <select id="filter_role" onchange="filterUsers()" class="pl-3 pr-8 py-1.5 rounded-md bg-surface-container border border-outline-variant text-body-md font-body-md text-sm outline-none focus:border-primary">
                        <option value="">Semua Peran</option>
                        <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="user" {{ request('role') === 'user' ? 'selected' : '' }}>Pelanggan</option>
                    </select>

                    <span class="font-label-sm text-label-sm text-on-surface-variant whitespace-nowrap ml-2">Status:</span>
                    <select id="filter_status" onchange="filterUsers()" class="pl-3 pr-8 py-1.5 rounded-md bg-surface-container border border-outline-variant text-body-md font-body-md text-sm outline-none focus:border-primary">
                        <option value="">Semua Status</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                    </select>
                </div>
            </div>
            
            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-surface-container-low border-b border-surface-variant">
                            <th class="py-3 px-6 font-label-sm text-label-sm text-on-surface-variant font-semibold">Pengguna</th>
                            <th class="py-3 px-6 font-label-sm text-label-sm text-on-surface-variant font-semibold">Peran</th>
                            <th class="py-3 px-6 font-label-sm text-label-sm text-on-surface-variant font-semibold">Status</th>
                            <th class="py-3 px-6 font-label-sm text-label-sm text-on-surface-variant font-semibold">Tanggal Daftar</th>
                            <th class="py-3 px-6 font-label-sm text-label-sm text-on-surface-variant font-semibold text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-surface-variant bg-surface-container-lowest">
                        @forelse ($users as $user)
                        <tr class="hover:bg-surface-container/50 transition-colors group">
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-primary-container/10 flex items-center justify-center text-primary font-bold text-sm uppercase">
                                        {{ substr($user->name, 0, 2) }}
                                    </div>
                                    <div>
                                        <p class="font-body-md text-body-md text-on-surface font-medium">{{ $user->name }}</p>
                                        <p class="font-caption text-caption text-on-surface-variant">{{ $user->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md {{ $user->role === 'admin' ? 'bg-primary-container/20 text-primary' : 'bg-secondary-container text-on-secondary-container' }} text-xs font-semibold uppercase">
                                    {{ $user->role === 'admin' ? 'Admin' : 'Pelanggan' }}
                                </span>
                            </td>
                            <td class="py-4 px-6">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full {{ $user->status === 'active' ? 'bg-primary/10 text-primary' : 'bg-error/10 text-error' }} font-caption text-caption">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $user->status === 'active' ? 'bg-primary' : 'bg-error' }}"></span>
                                    {{ $user->status === 'active' ? 'Aktif' : 'Tidak Aktif' }}
                                </span>
                            </td>
                            <td class="py-4 px-6 font-body-md text-body-md text-on-surface-variant text-sm">
                                {{ $user->created_at->format('d M Y') }}
                            </td>
                            <td class="py-4 px-6 text-right">
                                <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <button onclick="openEditAccountModal({{ json_encode($user) }})" class="p-1.5 text-on-surface-variant hover:text-primary rounded transition-colors">
                                        <span class="material-symbols-outlined text-sm">edit</span>
                                    </button>
                                    @if($user->id !== auth()->id())
                                        <form action="{{ route('admin.accounts.destroy', $user->id) }}" method="POST" class="inline delete-form" data-message="Yakin ingin menghapus akun {{ $user->name }}? Tindakan ini tidak bisa dibatalkan.">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 text-on-surface-variant hover:text-error rounded transition-colors">
                                                <span class="material-symbols-outlined text-sm">delete</span>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-on-surface-variant">
                                Tidak ada pengguna ditemukan.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="p-4 border-t border-surface-variant bg-surface-bright">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Modal Add Account -->
<div id="addAccountModal" class="fixed inset-0 z-50 hidden bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-surface-container-lowest rounded-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto shadow-2xl border border-surface-variant">
        <div class="p-6 border-b border-surface-variant flex items-center justify-between sticky top-0 bg-surface-container-lowest z-10">
            <h3 class="font-h3 text-h3 text-on-surface">Tambah Akun</h3>
            <button onclick="document.getElementById('addAccountModal').classList.add('hidden')" class="text-on-surface-variant hover:text-error transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form action="{{ route('admin.accounts.store') }}" method="POST" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="block font-label-sm text-label-sm text-on-surface-variant mb-1">Nama Lengkap</label>
                <input type="text" name="name" required class="w-full px-3 py-2 rounded-lg border border-outline-variant focus:border-primary bg-surface-bright text-on-surface outline-none">
            </div>
            <div>
                <label class="block font-label-sm text-label-sm text-on-surface-variant mb-1">Email</label>
                <input type="email" name="email" required class="w-full px-3 py-2 rounded-lg border border-outline-variant focus:border-primary bg-surface-bright text-on-surface outline-none">
            </div>
            <div>
                <label class="block font-label-sm text-label-sm text-on-surface-variant mb-1">Password</label>
                <input type="password" name="password" required class="w-full px-3 py-2 rounded-lg border border-outline-variant focus:border-primary bg-surface-bright text-on-surface outline-none">
            </div>
            <div>
                <label class="block font-label-sm text-label-sm text-on-surface-variant mb-1">Role</label>
                <select name="role" required class="w-full px-3 py-2 rounded-lg border border-outline-variant focus:border-primary bg-surface-bright text-on-surface outline-none">
                    <option value="user">Pelanggan</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <div>
                <label class="block font-label-sm text-label-sm text-on-surface-variant mb-1">Nomor WhatsApp</label>
                <input type="text" name="whatsapp" class="w-full px-3 py-2 rounded-lg border border-outline-variant focus:border-primary bg-surface-bright text-on-surface outline-none" placeholder="Contoh: 08123456789">
            </div>
            <div class="pt-4 flex justify-end gap-3 border-t border-surface-variant">
                <button type="button" onclick="document.getElementById('addAccountModal').classList.add('hidden')" class="px-4 py-2 font-label-sm text-label-sm text-on-surface-variant hover:bg-surface-container rounded-lg transition-colors">Batal</button>
                <button type="submit" class="px-4 py-2 font-label-sm text-label-sm bg-primary text-on-primary hover:bg-primary-container rounded-lg transition-colors shadow-sm">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Account -->
<div id="editAccountModal" class="fixed inset-0 z-50 hidden bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-surface-container-lowest rounded-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto shadow-2xl border border-surface-variant">
        <div class="p-6 border-b border-surface-variant flex items-center justify-between sticky top-0 bg-surface-container-lowest z-10">
            <h3 class="font-h3 text-h3 text-on-surface">Edit Akun</h3>
            <button onclick="document.getElementById('editAccountModal').classList.add('hidden')" class="text-on-surface-variant hover:text-error transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form id="editAccountForm" method="POST" class="p-6 space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block font-label-sm text-label-sm text-on-surface-variant mb-1">Nama Lengkap</label>
                <input type="text" name="name" id="edit_name" required class="w-full px-3 py-2 rounded-lg border border-outline-variant focus:border-primary bg-surface-bright text-on-surface outline-none">
            </div>
            <div>
                <label class="block font-label-sm text-label-sm text-on-surface-variant mb-1">Email</label>
                <input type="email" name="email" id="edit_email" required class="w-full px-3 py-2 rounded-lg border border-outline-variant focus:border-primary bg-surface-bright text-on-surface outline-none">
            </div>
            <div>
                <label class="block font-label-sm text-label-sm text-on-surface-variant mb-1">Password Baru (Biarkan kosong jika tidak ingin mengubah)</label>
                <input type="password" name="password" placeholder="Minimal 6 karakter" class="w-full px-3 py-2 rounded-lg border border-outline-variant focus:border-primary bg-surface-bright text-on-surface outline-none">
            </div>
            <div>
                <label class="block font-label-sm text-label-sm text-on-surface-variant mb-1">Role</label>
                <select name="role" id="edit_role" required class="w-full px-3 py-2 rounded-lg border border-outline-variant focus:border-primary bg-surface-bright text-on-surface outline-none">
                    <option value="user">Pelanggan</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <div>
                <label class="block font-label-sm text-label-sm text-on-surface-variant mb-1">Nomor WhatsApp</label>
                <input type="text" name="whatsapp" id="edit_whatsapp" class="w-full px-3 py-2 rounded-lg border border-outline-variant focus:border-primary bg-surface-bright text-on-surface outline-none" placeholder="Contoh: 08123456789">
            </div>
            <div class="pt-4 flex justify-end gap-3 border-t border-surface-variant">
                <button type="button" onclick="document.getElementById('editAccountModal').classList.add('hidden')" class="px-4 py-2 font-label-sm text-label-sm text-on-surface-variant hover:bg-surface-container rounded-lg transition-colors">Batal</button>
                <button type="submit" class="px-4 py-2 font-label-sm text-label-sm bg-primary text-on-primary hover:bg-primary-container rounded-lg transition-colors shadow-sm">Simpan Perubahan</button>
            </div>
        </form>
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
        document.getElementById('editAccountModal').classList.remove('hidden');
    }
</script>
@endsection
