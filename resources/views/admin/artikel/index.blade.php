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
        <button onclick="window.dispatchEvent(new CustomEvent('open-add-artikel-modal'))" class="bg-primary text-on-primary font-label-sm text-label-sm px-5 py-2.5 rounded-lg flex items-center gap-2 hover:bg-primary-container transition-colors ambient-shadow whitespace-nowrap">
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
                                <form action="{{ route('admin.artikel.destroy', $artikel->id) }}" method="POST" class="inline delete-form" data-message="Yakin ingin menghapus artikel '{{ $artikel->judul }}'? Tindakan ini tidak bisa dibatalkan.">
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
<div x-data="{ open: false }" @open-add-artikel-modal.window="open = true" class="relative z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" style="display: none;"></div>

    <div class="fixed inset-0 z-10 w-screen overflow-y-auto" x-show="open" style="display: none;">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div x-show="open" @click.away="open = false" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="relative transform overflow-hidden rounded-[24px] bg-white text-left shadow-[0_20px_60px_rgba(5,31,32,0.15)] transition-all sm:my-8 sm:w-full sm:max-w-3xl border border-slate-100">
                <div class="px-8 py-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center">
                            <span class="material-symbols-outlined text-xl">article</span>
                        </div>
                        <h3 class="text-xl font-bold text-slate-800">Tambah Artikel</h3>
                    </div>
                    <button type="button" @click="open = false" class="w-8 h-8 flex items-center justify-center rounded-full text-slate-400 hover:bg-slate-100 hover:text-slate-600 transition-colors">
                        <span class="material-symbols-outlined text-xl">close</span>
                    </button>
                </div>
                <form action="{{ route('admin.artikel.store') }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-6">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-1.5 md:col-span-2">
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Judul Artikel</label>
                            <input type="text" name="judul" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-[#2A7844] focus:ring-2 focus:ring-[#2A7844]/20 bg-slate-50 focus:bg-white text-slate-800 font-medium transition-all outline-none">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Kategori</label>
                            <input type="text" name="kategori" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-[#2A7844] focus:ring-2 focus:ring-[#2A7844]/20 bg-slate-50 focus:bg-white text-slate-800 font-medium transition-all outline-none">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Foto Artikel (Opsional)</label>
                            <input type="file" name="foto" accept="image/*" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-[#2A7844] focus:ring-2 focus:ring-[#2A7844]/20 bg-slate-50 focus:bg-white text-slate-800 font-medium transition-all outline-none">
                        </div>
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Konten Artikel</label>
                        <textarea name="konten" required rows="8" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-[#2A7844] focus:ring-2 focus:ring-[#2A7844]/20 bg-slate-50 focus:bg-white text-slate-800 font-medium transition-all outline-none"></textarea>
                    </div>
                    <div class="pt-6 mt-2 flex justify-end gap-3 border-t border-slate-100">
                        <button type="button" @click="open = false" class="px-6 py-2.5 font-bold text-sm text-slate-500 hover:bg-slate-100 rounded-xl transition-colors">Batal</button>
                        <button type="submit" class="px-6 py-2.5 font-bold text-sm bg-gradient-to-r from-[#2A7844] to-[#1e5c33] text-white hover:shadow-lg hover:shadow-[#2A7844]/30 hover:-translate-y-0.5 rounded-xl transition-all">Publish Artikel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Artikel -->
<div x-data="{ open: false }" @open-edit-artikel-modal.window="open = true" class="relative z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" style="display: none;"></div>

    <div class="fixed inset-0 z-10 w-screen overflow-y-auto" x-show="open" style="display: none;">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div x-show="open" @click.away="open = false" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="relative transform overflow-hidden rounded-[24px] bg-white text-left shadow-[0_20px_60px_rgba(5,31,32,0.15)] transition-all sm:my-8 sm:w-full sm:max-w-3xl border border-slate-100">
                <div class="px-8 py-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center">
                            <span class="material-symbols-outlined text-xl">edit_document</span>
                        </div>
                        <h3 class="text-xl font-bold text-slate-800">Edit Artikel</h3>
                    </div>
                    <button type="button" @click="open = false" class="w-8 h-8 flex items-center justify-center rounded-full text-slate-400 hover:bg-slate-100 hover:text-slate-600 transition-colors">
                        <span class="material-symbols-outlined text-xl">close</span>
                    </button>
                </div>
                <form id="editArtikelForm" method="POST" enctype="multipart/form-data" class="p-8 space-y-6">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-1.5 md:col-span-2">
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Judul Artikel</label>
                            <input type="text" name="judul" id="edit_judul" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-[#2A7844] focus:ring-2 focus:ring-[#2A7844]/20 bg-slate-50 focus:bg-white text-slate-800 font-medium transition-all outline-none">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Kategori</label>
                            <input type="text" name="kategori" id="edit_kategori" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-[#2A7844] focus:ring-2 focus:ring-[#2A7844]/20 bg-slate-50 focus:bg-white text-slate-800 font-medium transition-all outline-none">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Foto Artikel Baru <span class="text-[10px] font-normal normal-case">(Biarkan kosong jika tidak diubah)</span></label>
                            <input type="file" name="foto" accept="image/*" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-[#2A7844] focus:ring-2 focus:ring-[#2A7844]/20 bg-slate-50 focus:bg-white text-slate-800 font-medium transition-all outline-none">
                        </div>
                    </div>
                    
                    <div id="edit_foto_preview_container" class="hidden flex items-center gap-4 p-4 bg-slate-50 rounded-xl border border-slate-100">
                        <img id="edit_foto_preview" src="" class="w-20 h-20 object-cover rounded-lg shadow-sm border border-slate-200">
                        <div class="flex-1">
                            <span class="text-sm font-bold text-slate-700 block">Foto Saat Ini</span>
                            <p class="text-xs text-slate-500 mb-2">Ini adalah foto artikel yang saat ini ditampilkan.</p>
                            <button type="button" onclick="hapusFotoArtikel()" class="text-xs text-red-500 font-bold flex items-center gap-1 hover:text-red-600 bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-lg transition-colors">
                                <span class="material-symbols-outlined text-sm">delete</span>
                                Hapus Foto
                            </button>
                        </div>
                        <input type="hidden" name="hapus_foto" id="edit_hapus_foto" value="0">
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Konten Artikel</label>
                        <textarea name="konten" id="edit_konten" required rows="8" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-[#2A7844] focus:ring-2 focus:ring-[#2A7844]/20 bg-slate-50 focus:bg-white text-slate-800 font-medium transition-all outline-none"></textarea>
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
    function openEditArtikelModal(artikel) {
        document.getElementById('editArtikelForm').action = `/admin/artikel/${artikel.id}`;
        document.getElementById('edit_judul').value = artikel.judul;
        document.getElementById('edit_kategori').value = artikel.kategori || '';
        document.getElementById('edit_konten').value = artikel.konten;
        
        document.getElementById('edit_hapus_foto').value = "0";

        // Show/hide preview container based on whether the article has a photo
        const previewContainer = document.getElementById('edit_foto_preview_container');
        const previewImg = document.getElementById('edit_foto_preview');
        
        if (artikel.foto) {
            previewImg.src = `/storage/${artikel.foto}`;
            previewContainer.classList.remove('hidden');
        } else {
            previewImg.src = '';
            previewContainer.classList.add('hidden');
        }

        window.dispatchEvent(new CustomEvent('open-edit-artikel-modal'));
    }

    function hapusFotoArtikel() {
        const container = document.getElementById('edit_foto_preview_container');
        openDeleteModal('Yakin ingin menghapus foto artikel ini?', () => {
            document.getElementById('edit_hapus_foto').value = "1";
            container.classList.add('hidden');
        });
    }
</script>

@endsection
