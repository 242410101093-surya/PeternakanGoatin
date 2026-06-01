@extends('layouts.admin')

@section('title', 'Educational Articles')

@section('content')
<div class="w-full px-margin-mobile md:px-margin-desktop">
    <div class="max-w-container-max mx-auto w-full flex flex-col gap-stack-lg">
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-stack-md">
        <div>
            <h2 class="font-h2 text-h2 text-on-surface">Educational Articles</h2>
            <p class="font-body-md text-body-md text-on-surface-variant mt-1">Manage and publish animal care stewardship content.</p>
        </div>
        <button onclick="document.getElementById('addArtikelModal').classList.remove('hidden')" class="bg-primary text-on-primary font-label-sm text-label-sm px-5 py-2.5 rounded-lg flex items-center gap-2 hover:bg-primary-container transition-colors ambient-shadow whitespace-nowrap">
            <span class="material-symbols-outlined text-sm">add</span>
            Create New Article
        </button>
    </div>
    
    <!-- Stats/Filters Row -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-surface-container-lowest p-6 rounded-xl border border-surface-variant ambient-shadow flex flex-col gap-2 relative overflow-hidden">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-primary-fixed/20 rounded-full blur-2xl"></div>
            <span class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Total Published</span>
            <div class="flex items-end gap-3">
                <span class="font-h1 text-h1 text-primary-container">{{ number_format($totalArtikels) }}</span>
            </div>
        </div>
        <div class="bg-surface-container-lowest p-6 rounded-xl border border-surface-variant ambient-shadow flex flex-col gap-4">
            <form method="GET" action="{{ route('admin.artikel.index') }}" class="flex gap-2">
                <div class="relative flex-1">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-[18px]">search</span>
                    <input type="text" name="search" placeholder="Cari artikel..." value="{{ request('search') }}" class="pl-10 pr-4 py-2 rounded-lg border border-outline-variant bg-surface-bright text-on-surface font-body-sm w-full focus:border-primary outline-none">
                </div>
                <button type="submit" class="px-3 py-2 rounded-lg border border-primary bg-primary-container text-on-primary hover:bg-primary transition-colors">
                    <span class="material-symbols-outlined text-sm">search</span>
                </button>
            </form>
        </div>
    </div>
    
    <!-- Articles Table Card -->
    <div class="bg-surface-container-lowest rounded-xl border border-surface-variant ambient-shadow overflow-hidden flex flex-col">
        <div class="p-6 border-b border-surface-variant flex justify-between items-center bg-surface-bright">
            <h3 class="font-h3 text-h3 text-on-surface">Recent Articles</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-surface-variant bg-surface-container-low/50">
                        <th class="p-4 font-label-sm text-label-sm text-on-surface-variant font-semibold">Article Title</th>
                        <th class="p-4 font-label-sm text-label-sm text-on-surface-variant font-semibold">Category</th>
                        <th class="p-4 font-label-sm text-label-sm text-on-surface-variant font-semibold">Date</th>
                        <th class="p-4 font-label-sm text-label-sm text-on-surface-variant font-semibold text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-variant font-body-md text-body-md">
                    @forelse($artikels as $artikel)
                    <tr class="hover:bg-surface-container/50 transition-colors group">
                        <td class="p-4">
                            <div class="flex items-center gap-4">
                                <div class="w-16 h-16 rounded bg-surface-container flex-shrink-0 overflow-hidden flex items-center justify-center">
                                    @if($artikel->foto)
                                        <img alt="{{ $artikel->judul }}" class="w-full h-full object-cover" src="{{ asset('storage/' . $artikel->foto) }}"/>
                                    @else
                                        <span class="material-symbols-outlined text-outline-variant">image</span>
                                    @endif
                                </div>
                                <div>
                                    <span class="font-semibold text-on-surface block mb-1 group-hover:text-primary transition-colors cursor-pointer">{{ $artikel->judul }}</span>
                                    <span class="font-caption text-caption text-on-surface-variant line-clamp-1">{{ Str::limit($artikel->konten, 50) }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="p-4">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-surface-container text-on-surface font-caption text-caption">
                                {{ $artikel->kategori ?? 'Umum' }}
                            </span>
                        </td>
                        <td class="p-4">
                            <span class="text-on-surface-variant text-sm">{{ $artikel->created_at->format('M d, Y') }}</span>
                        </td>
                        <td class="p-4 text-center">
                            <div class="flex items-center justify-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <button onclick="openEditArtikelModal({{ $artikel }})" class="text-on-surface-variant hover:text-primary-container p-1 rounded transition-colors">
                                    <span class="material-symbols-outlined">edit</span>
                                </button>
                                <form action="{{ route('admin.artikel.destroy', $artikel->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus artikel ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-on-surface-variant hover:text-error p-1 rounded transition-colors">
                                        <span class="material-symbols-outlined">delete</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="p-8 text-center text-on-surface-variant">Belum ada artikel.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="p-4 border-t border-surface-variant flex items-center justify-between bg-surface-container/30">
            {{ $artikels->links() }}
        </div>
    </div>
    </div>
</div>

<!-- Modal Tambah Artikel -->
<div id="addArtikelModal" class="fixed inset-0 z-50 hidden bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-surface-container-lowest rounded-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto shadow-2xl border border-surface-variant">
        <div class="p-6 border-b border-surface-variant flex items-center justify-between sticky top-0 bg-surface-container-lowest z-10">
            <h3 class="font-h3 text-h3 text-on-surface">Tambah Artikel</h3>
            <button onclick="document.getElementById('addArtikelModal').classList.add('hidden')" class="text-on-surface-variant hover:text-error transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form action="{{ route('admin.artikel.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="block font-label-sm text-label-sm text-on-surface-variant mb-1">Judul Artikel</label>
                <input type="text" name="judul" required class="w-full px-3 py-2 rounded-lg border border-outline-variant focus:border-primary bg-surface-bright text-on-surface">
            </div>
            <div>
                <label class="block font-label-sm text-label-sm text-on-surface-variant mb-1">Kategori</label>
                <input type="text" name="kategori" class="w-full px-3 py-2 rounded-lg border border-outline-variant focus:border-primary bg-surface-bright text-on-surface">
            </div>
            <div>
                <label class="block font-label-sm text-label-sm text-on-surface-variant mb-1">Konten Artikel</label>
                <textarea name="konten" required rows="6" class="w-full px-3 py-2 rounded-lg border border-outline-variant focus:border-primary bg-surface-bright text-on-surface"></textarea>
            </div>
            <div>
                <label class="block font-label-sm text-label-sm text-on-surface-variant mb-1">Foto Artikel (Opsional)</label>
                <input type="file" name="foto" accept="image/*" class="w-full px-3 py-2 rounded-lg border border-outline-variant focus:border-primary bg-surface-bright text-on-surface">
            </div>
            <div class="pt-4 flex justify-end gap-3 border-t border-surface-variant">
                <button type="button" onclick="document.getElementById('addArtikelModal').classList.add('hidden')" class="px-4 py-2 font-label-sm text-label-sm text-on-surface-variant hover:bg-surface-container rounded-lg transition-colors">Batal</button>
                <button type="submit" class="px-4 py-2 font-label-sm text-label-sm bg-primary text-on-primary hover:bg-primary-container rounded-lg transition-colors shadow-sm">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Artikel -->
<div id="editArtikelModal" class="fixed inset-0 z-50 hidden bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-surface-container-lowest rounded-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto shadow-2xl border border-surface-variant">
        <div class="p-6 border-b border-surface-variant flex items-center justify-between sticky top-0 bg-surface-container-lowest z-10">
            <h3 class="font-h3 text-h3 text-on-surface">Edit Artikel</h3>
            <button onclick="document.getElementById('editArtikelModal').classList.add('hidden')" class="text-on-surface-variant hover:text-error transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form id="editArtikelForm" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block font-label-sm text-label-sm text-on-surface-variant mb-1">Judul Artikel</label>
                <input type="text" name="judul" id="edit_judul" required class="w-full px-3 py-2 rounded-lg border border-outline-variant focus:border-primary bg-surface-bright text-on-surface">
            </div>
            <div>
                <label class="block font-label-sm text-label-sm text-on-surface-variant mb-1">Kategori</label>
                <input type="text" name="kategori" id="edit_kategori" class="w-full px-3 py-2 rounded-lg border border-outline-variant focus:border-primary bg-surface-bright text-on-surface">
            </div>
            <div>
                <label class="block font-label-sm text-label-sm text-on-surface-variant mb-1">Konten Artikel</label>
                <textarea name="konten" id="edit_konten" required rows="6" class="w-full px-3 py-2 rounded-lg border border-outline-variant focus:border-primary bg-surface-bright text-on-surface"></textarea>
            </div>
            <div>
                <label class="block font-label-sm text-label-sm text-on-surface-variant mb-1">Foto Artikel (Biarkan kosong jika tidak ingin mengubah)</label>
                <input type="file" name="foto" accept="image/*" class="w-full px-3 py-2 rounded-lg border border-outline-variant focus:border-primary bg-surface-bright text-on-surface">
            </div>
            <div class="pt-4 flex justify-end gap-3 border-t border-surface-variant">
                <button type="button" onclick="document.getElementById('editArtikelModal').classList.add('hidden')" class="px-4 py-2 font-label-sm text-label-sm text-on-surface-variant hover:bg-surface-container rounded-lg transition-colors">Batal</button>
                <button type="submit" class="px-4 py-2 font-label-sm text-label-sm bg-primary text-on-primary hover:bg-primary-container rounded-lg transition-colors shadow-sm">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openEditArtikelModal(artikel) {
        document.getElementById('editArtikelForm').action = `/admin/artikel/${artikel.id}`;
        document.getElementById('edit_judul').value = artikel.judul;
        document.getElementById('edit_kategori').value = artikel.kategori || '';
        document.getElementById('edit_konten').value = artikel.konten;
        document.getElementById('editArtikelModal').classList.remove('hidden');
    }
</script>

@endsection
