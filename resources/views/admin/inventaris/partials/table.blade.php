    <!-- Table Container -->
    <div class="overflow-x-auto pb-8 pt-2">
        <table class="w-full text-left border-separate whitespace-nowrap" style="border-spacing: 0 12px;">
            <thead>
                <tr class="font-label-sm text-xs text-slate-400 uppercase tracking-widest font-bold">
                    <th class="pb-2 px-6">ID Kambing</th>
                    <th class="pb-2 px-6">Jenis & Ras</th>
                    <th class="pb-2 px-6">Kelamin</th>
                    <th class="pb-2 px-6">Umur (Bulan)</th>
                    <th class="pb-2 px-6">Bobot (Kg)</th>
                    <th class="pb-2 px-6">Status</th>
                    <th class="pb-2 px-6 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="font-body-md text-sm">
                @forelse ($inventaris as $item)
                <tr class="bg-white hover:bg-[#f8fdfa] transition-all duration-300 shadow-[0_4px_20px_rgba(0,0,0,0.03)] hover:shadow-[0_8px_30px_rgba(42,120,68,0.12)] group cursor-default transform hover:-translate-y-1">
                    <td class="py-5 px-6 rounded-l-2xl border-y border-l border-slate-100 group-hover:border-[#2A7844]/20">
                        <div class="flex flex-col items-start gap-1">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-gradient-to-r from-[#2A7844]/10 to-[#1e5c33]/5 border border-[#2A7844]/20 text-[#2A7844] font-mono font-black text-xs tracking-widest w-fit shadow-sm">
                                <span class="material-symbols-outlined text-[14px]">tag</span>
                                {{ str_pad($item->id, 4, '0', STR_PAD_LEFT) }}
                            </span>
                            <span class="font-caption text-caption text-slate-400 font-medium ml-1 mt-1">ID Ternak</span>
                        </div>
                    </td>
                    <td class="py-5 px-6 border-y border-slate-100 group-hover:border-[#2A7844]/20 transition-colors">
                        <div class="flex items-center gap-4">
                            <div class="h-10 w-10 rounded-full bg-slate-50 border border-slate-100 flex items-center justify-center flex-shrink-0 text-slate-400 group-hover:bg-[#2A7844]/10 group-hover:text-[#2A7844] group-hover:border-[#2A7844]/20 transition-colors">
                                <span class="material-symbols-outlined text-lg">pets</span>
                            </div>
                            <div>
                                <p class="font-bold text-slate-700 text-base">{{ $item->jenis }}</p>
                                <p class="font-caption text-caption text-slate-500 mt-0.5">Ras: <span class="font-semibold">{{ $item->ras ?? '-' }}</span></p>
                            </div>
                        </div>
                    </td>
                    <td class="py-5 px-6 border-y border-slate-100 group-hover:border-[#2A7844]/20 text-slate-600 font-semibold transition-colors">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-lg {{ $item->gender == 'Jantan' ? 'text-blue-500' : 'text-pink-500' }}">{{ $item->gender == 'Jantan' ? 'male' : 'female' }}</span>
                            {{ $item->gender }}
                        </div>
                    </td>
                    <td class="py-5 px-6 border-y border-slate-100 group-hover:border-[#2A7844]/20 text-slate-600 font-medium transition-colors">
                        {{ $item->umur }} Bulan
                    </td>
                    <td class="py-5 px-6 border-y border-slate-100 group-hover:border-[#2A7844]/20 text-slate-600 font-bold transition-colors">
                        {{ $item->berat }} Kg
                    </td>
                    <td class="py-5 px-6 border-y border-slate-100 group-hover:border-[#2A7844]/20 transition-colors">
                        @php
                            $statusColor = 'bg-blue-50 text-blue-600 border-blue-200 shadow-sm';
                            if ($item->status_stok == 'Terjual') $statusColor = 'bg-emerald-50 text-emerald-600 border-emerald-200 shadow-sm';
                            if ($item->status_stok == 'Terbooking') $statusColor = 'bg-orange-50 text-orange-600 border-orange-200 shadow-sm';
                            if ($item->status_stok == 'Dalam Perawatan') $statusColor = 'bg-red-50 text-red-600 border-red-200 shadow-sm';
                        @endphp
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full border text-xs font-bold {{ $statusColor }}">
                            {{ $item->status_stok }}
                        </span>
                    </td>
                    <td class="py-5 px-6 rounded-r-2xl border-y border-r border-slate-100 group-hover:border-[#2A7844]/20 text-right transition-colors">
                        <div class="flex items-center justify-end gap-2 opacity-100 lg:opacity-0 lg:group-hover:opacity-100 transition-opacity translate-x-0 lg:translate-x-4 lg:group-hover:translate-x-0 duration-300">
                            @if($item->status_stok == 'Tersedia' || $item->status_stok == 'Dalam Perawatan')
                            <button onclick="openJualModal({{ $item }})" class="w-8 h-8 flex items-center justify-center bg-white border border-slate-200 text-slate-400 hover:text-emerald-500 hover:border-emerald-500 hover:bg-emerald-50 rounded-xl shadow-sm transition-all" title="Jual ke Katalog">
                                <span class="material-symbols-outlined text-[16px]">storefront</span>
                            </button>
                            @endif
                            <button onclick="openEditModal({{ $item }})" class="w-8 h-8 flex items-center justify-center bg-white border border-slate-200 text-slate-400 hover:text-[#2A7844] hover:border-[#2A7844] hover:bg-[#2A7844]/5 rounded-xl shadow-sm transition-all" title="Edit Data">
                                <span class="material-symbols-outlined text-[16px]">edit</span>
                            </button>
                            <form action="{{ route('admin.inventaris.destroy', $item->id) }}" method="POST" class="inline delete-form" data-message="Yakin ingin menghapus {{ $item->jenis }} ({{ $item->ras ?? 'tanpa ras' }}) dari inventaris? Tindakan ini tidak bisa dibatalkan.">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-8 h-8 flex items-center justify-center bg-white border border-slate-200 text-slate-400 hover:text-red-500 hover:border-red-500 hover:bg-red-50 rounded-xl shadow-sm transition-all" title="Hapus Permanen">
                                    <span class="material-symbols-outlined text-[16px]">delete</span>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="py-16 px-6 text-center text-slate-400 bg-white rounded-3xl border border-dashed border-slate-200">
                        <div class="w-16 h-16 mx-auto mb-4 bg-slate-50 rounded-full flex items-center justify-center">
                            <span class="material-symbols-outlined text-3xl opacity-50">pets</span>
                        </div>
                        <p class="font-medium text-slate-500">Belum ada data inventaris ternak.</p>
                        <p class="text-xs mt-1">Gunakan tombol "Tambah Inventaris" untuk mendaftarkan ternak baru.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Table Footer / Pagination -->
    <div class="py-2">
        {{ $inventaris->links() }}
    </div>
