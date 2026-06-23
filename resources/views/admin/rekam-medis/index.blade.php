@extends('layouts.admin')

@section('title', 'Rekam Medis')

@section('content')
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="w-full px-margin-mobile md:px-margin-desktop">
    <div class="max-w-container-max mx-auto space-y-stack-xl">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-stack-md mb-stack-lg">
        <div>
            <h1 class="font-h1 text-h1 text-on-surface mb-stack-xs">Rekam Medis & Pertumbuhan</h1>
            <p class="font-body-md text-body-md text-on-surface-variant">Ringkasan pemeriksaan kesehatan, vaksinasi, dan pemantauan pertumbuhan ternak.</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.rekam-medis.export-pdf') }}" download class="bg-primary text-on-primary flex items-center gap-2 px-4 py-2 rounded-lg font-label-sm text-label-sm hover:opacity-90 transition-opacity shadow-ambient">
                <span class="material-symbols-outlined text-[18px]">download</span>
                Unduh PDF
            </a>
            <button onclick="window.dispatchEvent(new CustomEvent('open-add-berat-modal'))" class="bg-surface-container-lowest border border-outline-variant text-on-surface flex items-center gap-2 px-4 py-2 rounded-lg font-label-sm text-label-sm hover:bg-surface-container-low transition-colors shadow-ambient">
                <span class="material-symbols-outlined text-[18px]">scale</span>
                Catat Berat
            </button>
            <button onclick="window.dispatchEvent(new CustomEvent('open-add-rekam-modal'))" class="bg-primary-container text-on-primary-container flex items-center gap-2 px-4 py-2 rounded-lg font-label-sm text-label-sm hover:bg-primary transition-colors shadow-ambient hover:text-white">
                <span class="material-symbols-outlined text-[18px]">add</span>
                Rekam Medis Baru
            </button>
        </div>
    </div>

    <!-- Growth Chart Section -->
    <div class="bg-surface-container-lowest p-6 rounded-xl border border-surface-variant shadow-ambient">
        <div class="flex justify-between items-center mb-4">
            <h3 class="font-h3 text-h3 text-on-background">Grafik Pertumbuhan Berat Badan</h3>
            <form action="{{ route('admin.rekam-medis.index') }}" method="GET" class="flex gap-2">
                <select name="inventaris_id" onchange="this.form.submit()" class="bg-surface-container border border-outline-variant text-on-surface-variant rounded-md px-3 py-1 text-sm focus:outline-none focus:border-primary">
                    <option value="">Pilih Ternak...</option>
                    @foreach($inventarisList as $inv)
                        <option value="{{ $inv->id }}" {{ $selectedInventarisId == $inv->id ? 'selected' : '' }}>
                            {{ $inv->jenis }} {{ $inv->ras ? '- '.$inv->ras : '' }} (#{{ str_pad($inv->id, 4, '0', STR_PAD_LEFT) }})
                        </option>
                    @endforeach
                </select>
            </form>
        </div>

        @if($selectedInventarisId && count($chartLabels) > 0)
        <div class="w-full h-64">
            <canvas id="growthChart"></canvas>
        </div>
        @else
        <div class="w-full h-64 flex items-center justify-center bg-surface-container/20 rounded-lg">
            <p class="text-on-surface-variant font-body-md">Belum ada data berat badan untuk ternak ini.</p>
        </div>
        @endif
    </div>

    <!-- Main Table Section -->
    <div class="bg-transparent mt-8 flex flex-col">
        <div class="mb-4 flex justify-between items-center px-2">
            <h3 class="font-h3 text-h3 text-slate-800 tracking-tight">Riwayat Rekam Medis</h3>
        </div>
        <div class="overflow-x-auto pb-8">
            <table class="w-full text-left border-separate whitespace-nowrap" style="border-spacing: 0 12px;">
                <thead>
                    <tr class="font-label-sm text-xs text-slate-400 uppercase tracking-widest font-bold">
                        <th class="pb-2 px-6">Tanggal</th>

                        <th class="pb-2 px-6">Hewan (ID)</th>
                        <th class="pb-2 px-6">Dokter Hewan</th>
                        <th class="pb-2 px-6">Diagnosa</th>
                        <th class="pb-2 px-6">Tindakan</th>
                        <th class="pb-2 px-6">Status</th>
                        <th class="pb-2 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="font-body-md text-sm">
                    @forelse($rekamMedis as $rekam)
                    <tr class="bg-white hover:bg-[#f8fdfa] transition-all duration-300 shadow-[0_4px_20px_rgba(0,0,0,0.03)] hover:shadow-[0_8px_30px_rgba(42,120,68,0.12)] group cursor-default transform hover:-translate-y-1">
                        <td class="py-5 px-6 rounded-l-2xl border-y border-l border-slate-100 group-hover:border-[#2A7844]/20 group-hover:text-[#2A7844] text-slate-500 font-semibold transition-colors">
                            {{ \Carbon\Carbon::parse($rekam->tanggal)->format('d M Y') }}
                        </td>

                        <td class="py-5 px-6 border-y border-slate-100 group-hover:border-[#2A7844]/20 transition-colors">
                            <div class="flex flex-col gap-1.5">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-gradient-to-r from-[#2A7844]/10 to-[#1e5c33]/5 border border-[#2A7844]/20 text-[#2A7844] font-mono font-black text-xs tracking-widest w-fit">
                                    <span class="material-symbols-outlined text-[14px]">tag</span>
                                    {{ $rekam->inventaris ? str_pad($rekam->inventaris->id, 4, '0', STR_PAD_LEFT) : '-' }}
                                </span>
                                <div class="font-bold text-slate-700">{{ $rekam->inventaris->jenis ?? '-' }}</div>
                            </div>
                        </td>
                        <td class="py-5 px-6 border-y border-slate-100 group-hover:border-[#2A7844]/20 text-slate-600 font-medium transition-colors">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center">
                                    <span class="material-symbols-outlined text-sm">stethoscope</span>
                                </div>
                                {{ $rekam->dokter_hewan ?? '-' }}
                            </div>
                        </td>
                        <td class="py-5 px-6 border-y border-slate-100 group-hover:border-[#2A7844]/20 text-slate-600 transition-colors max-w-[200px] truncate" title="{{ $rekam->diagnosa }}">
                            {{ $rekam->diagnosa }}
                        </td>
                        <td class="py-5 px-6 border-y border-slate-100 group-hover:border-[#2A7844]/20 text-slate-600 transition-colors max-w-[200px] truncate" title="{{ $rekam->tindakan }}">
                            {{ $rekam->tindakan }}
                        </td>
                        <td class="py-5 px-6 border-y border-slate-100 group-hover:border-[#2A7844]/20 transition-colors">
                            @php
                                $statusColor = 'bg-blue-50 text-blue-600 border-blue-200';
                                if(str_contains(strtolower($rekam->status), 'sakit') || str_contains(strtolower($rekam->status), 'perawatan')) {
                                    $statusColor = 'bg-amber-50 text-amber-600 border-amber-200';
                                }
                            @endphp
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full border text-xs font-bold shadow-sm {{ $statusColor }}">
                                {{ $rekam->status }}
                            </span>
                        </td>
                        <td class="py-5 px-6 rounded-r-2xl border-y border-r border-slate-100 group-hover:border-[#2A7844]/20 text-right transition-colors">
                            <div class="flex items-center justify-end gap-2 opacity-100 lg:opacity-0 lg:group-hover:opacity-100 transition-opacity translate-x-0 lg:translate-x-4 lg:group-hover:translate-x-0 duration-300">
                                <button onclick="openEditRekamModal({{ $rekam }})" class="w-8 h-8 flex items-center justify-center bg-white border border-slate-200 text-slate-400 hover:text-[#2A7844] hover:border-[#2A7844] hover:bg-[#2A7844]/5 rounded-xl shadow-sm transition-all">
                                    <span class="material-symbols-outlined text-[16px]">edit</span>
                                </button>
                                <form action="{{ route('admin.rekam-medis.destroy', $rekam->id) }}" method="POST" class="inline delete-form" data-message="Yakin ingin menghapus data rekam medis ini? Tindakan ini tidak bisa dibatalkan.">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-8 h-8 flex items-center justify-center bg-white border border-slate-200 text-slate-400 hover:text-red-500 hover:border-red-500 hover:bg-red-50 rounded-xl shadow-sm transition-all">
                                        <span class="material-symbols-outlined text-[16px]">delete</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="py-16 px-6 text-center text-slate-400 bg-white rounded-3xl border border-dashed border-slate-200">
                            <div class="w-16 h-16 mx-auto mb-4 bg-slate-50 rounded-full flex items-center justify-center">
                                <span class="material-symbols-outlined text-3xl opacity-50">medical_information</span>
                            </div>
                            <p class="font-medium text-slate-500">Belum ada data rekam medis.</p>
                            <p class="text-xs mt-1">Data yang ditambahkan akan muncul di sini.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    </div>
</div>

<!-- Modal Tambah Rekam Medis -->
<div x-data="{ open: false }" @open-add-rekam-modal.window="open = true" class="relative z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" style="display: none;"></div>

    <div class="fixed inset-0 z-10 w-screen overflow-y-auto" x-show="open" style="display: none;">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div x-show="open" @click.away="open = false" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="relative transform overflow-hidden rounded-[24px] bg-white text-left shadow-[0_20px_60px_rgba(5,31,32,0.15)] transition-all sm:my-8 sm:w-full sm:max-w-2xl border border-slate-100">
                <div class="px-8 py-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center">
                            <span class="material-symbols-outlined text-xl">medical_information</span>
                        </div>
                        <h3 class="text-xl font-bold text-slate-800">Tambah Rekam Medis</h3>
                    </div>
                    <button type="button" @click="open = false" class="w-8 h-8 flex items-center justify-center rounded-full text-slate-400 hover:bg-slate-100 hover:text-slate-600 transition-colors">
                        <span class="material-symbols-outlined text-xl">close</span>
                    </button>
                </div>
                <form action="{{ route('admin.rekam-medis.store') }}" method="POST" class="p-8 space-y-6">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Ternak</label>
                            <div class="relative">
                                <select name="inventaris_id" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-[#2A7844] focus:ring-2 focus:ring-[#2A7844]/20 bg-slate-50 focus:bg-white text-slate-800 font-medium transition-all outline-none appearance-none cursor-pointer">
                                    @foreach($inventarisList as $inv)
                                        <option value="{{ $inv->id }}">{{ $inv->jenis }} (#{{ str_pad($inv->id, 4, '0', STR_PAD_LEFT) }})</option>
                                    @endforeach
                                </select>
                                <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">expand_more</span>
                            </div>
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Tanggal</label>
                            <input type="date" name="tanggal" required value="{{ date('Y-m-d') }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-[#2A7844] focus:ring-2 focus:ring-[#2A7844]/20 bg-slate-50 focus:bg-white text-slate-800 font-medium transition-all outline-none">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Dokter Hewan</label>
                            <input type="text" name="dokter_hewan" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-[#2A7844] focus:ring-2 focus:ring-[#2A7844]/20 bg-slate-50 focus:bg-white text-slate-800 font-medium transition-all outline-none">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Status Keadaan</label>
                            <input type="text" name="status" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-[#2A7844] focus:ring-2 focus:ring-[#2A7844]/20 bg-slate-50 focus:bg-white text-slate-800 font-medium transition-all outline-none" placeholder="Sehat / Masa Pemulihan">
                        </div>
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Diagnosa</label>
                        <textarea name="diagnosa" required rows="2" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-[#2A7844] focus:ring-2 focus:ring-[#2A7844]/20 bg-slate-50 focus:bg-white text-slate-800 font-medium transition-all outline-none"></textarea>
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Tindakan</label>
                        <textarea name="tindakan" required rows="2" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-[#2A7844] focus:ring-2 focus:ring-[#2A7844]/20 bg-slate-50 focus:bg-white text-slate-800 font-medium transition-all outline-none"></textarea>
                    </div>
                    <div class="pt-6 mt-2 flex justify-end gap-3 border-t border-slate-100">
                        <button type="button" @click="open = false" class="px-6 py-2.5 font-bold text-sm text-slate-500 hover:bg-slate-100 rounded-xl transition-colors btn-batal">Batal</button>
                        <button type="submit" class="px-6 py-2.5 font-bold text-sm bg-gradient-to-r from-[#2A7844] to-[#1e5c33] text-white hover:shadow-lg hover:shadow-[#2A7844]/30 hover:-translate-y-0.5 rounded-xl transition-all">Simpan Baru</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Rekam Medis -->
<div x-data="{ open: false }" @open-edit-rekam-modal.window="open = true" class="relative z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" style="display: none;"></div>

    <div class="fixed inset-0 z-10 w-screen overflow-y-auto" x-show="open" style="display: none;">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div x-show="open" @click.away="open = false" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="relative transform overflow-hidden rounded-[24px] bg-white text-left shadow-[0_20px_60px_rgba(5,31,32,0.15)] transition-all sm:my-8 sm:w-full sm:max-w-2xl border border-slate-100">
                <div class="px-8 py-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center">
                            <span class="material-symbols-outlined text-xl">edit_document</span>
                        </div>
                        <h3 class="text-xl font-bold text-slate-800">Edit Rekam Medis</h3>
                    </div>
                    <button type="button" @click="open = false" class="w-8 h-8 flex items-center justify-center rounded-full text-slate-400 hover:bg-slate-100 hover:text-slate-600 transition-colors">
                        <span class="material-symbols-outlined text-xl">close</span>
                    </button>
                </div>
                <form id="editRekamForm" method="POST" class="p-8 space-y-6">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Tanggal</label>
                            <input type="date" name="tanggal" id="edit_tanggal" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-[#2A7844] focus:ring-2 focus:ring-[#2A7844]/20 bg-slate-50 focus:bg-white text-slate-800 font-medium transition-all outline-none">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Dokter Hewan</label>
                            <input type="text" name="dokter_hewan" id="edit_dokter" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-[#2A7844] focus:ring-2 focus:ring-[#2A7844]/20 bg-slate-50 focus:bg-white text-slate-800 font-medium transition-all outline-none">
                        </div>
                        <div class="space-y-1.5 md:col-span-2">
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Status Keadaan</label>
                            <input type="text" name="status" id="edit_status" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-[#2A7844] focus:ring-2 focus:ring-[#2A7844]/20 bg-slate-50 focus:bg-white text-slate-800 font-medium transition-all outline-none">
                        </div>
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Diagnosa</label>
                        <textarea name="diagnosa" id="edit_diagnosa" required rows="2" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-[#2A7844] focus:ring-2 focus:ring-[#2A7844]/20 bg-slate-50 focus:bg-white text-slate-800 font-medium transition-all outline-none"></textarea>
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Tindakan</label>
                        <textarea name="tindakan" id="edit_tindakan" required rows="2" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-[#2A7844] focus:ring-2 focus:ring-[#2A7844]/20 bg-slate-50 focus:bg-white text-slate-800 font-medium transition-all outline-none"></textarea>
                    </div>
                    <div class="pt-6 mt-2 flex justify-end gap-3 border-t border-slate-100">
                        <button type="button" @click="open = false" class="px-6 py-2.5 font-bold text-sm text-slate-500 hover:bg-slate-100 rounded-xl transition-colors btn-batal">Batal</button>
                        <button type="submit" class="px-6 py-2.5 font-bold text-sm bg-gradient-to-r from-[#2A7844] to-[#1e5c33] text-white hover:shadow-lg hover:shadow-[#2A7844]/30 hover:-translate-y-0.5 rounded-xl transition-all">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Catat Berat Badan -->
<div x-data="{ open: false }" @open-add-berat-modal.window="open = true" class="relative z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" style="display: none;"></div>

    <div class="fixed inset-0 z-10 w-screen overflow-y-auto" x-show="open" style="display: none;">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div x-show="open" @click.away="open = false" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="relative transform overflow-hidden rounded-[24px] bg-white text-left shadow-[0_20px_60px_rgba(5,31,32,0.15)] transition-all sm:my-8 sm:w-full sm:max-w-md border border-slate-100">
                <div class="px-8 py-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-orange-100 text-orange-600 flex items-center justify-center">
                            <span class="material-symbols-outlined text-xl">scale</span>
                        </div>
                        <h3 class="text-xl font-bold text-slate-800">Catat Berat Badan</h3>
                    </div>
                    <button type="button" @click="open = false" class="w-8 h-8 flex items-center justify-center rounded-full text-slate-400 hover:bg-slate-100 hover:text-slate-600 transition-colors">
                        <span class="material-symbols-outlined text-xl">close</span>
                    </button>
                </div>
                <form action="{{ route('admin.rekam-medis.berat') }}" method="POST" class="p-8 space-y-6">
                    @csrf
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Ternak</label>
                        <div class="relative">
                            <select name="inventaris_id" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-[#2A7844] focus:ring-2 focus:ring-[#2A7844]/20 bg-slate-50 focus:bg-white text-slate-800 font-medium transition-all outline-none appearance-none cursor-pointer">
                                @foreach($inventarisList as $inv)
                                    <option value="{{ $inv->id }}">{{ $inv->jenis }} (#{{ str_pad($inv->id, 4, '0', STR_PAD_LEFT) }})</option>
                                @endforeach
                            </select>
                            <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">expand_more</span>
                        </div>
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Tanggal</label>
                        <input type="date" name="tanggal_pencatatan" required value="{{ date('Y-m-d') }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-[#2A7844] focus:ring-2 focus:ring-[#2A7844]/20 bg-slate-50 focus:bg-white text-slate-800 font-medium transition-all outline-none">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Berat (Kg)</label>
                        <input type="number" step="0.01" name="berat" required min="0" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-[#2A7844] focus:ring-2 focus:ring-[#2A7844]/20 bg-slate-50 focus:bg-white text-slate-800 font-medium transition-all outline-none">
                    </div>
                    <div class="pt-6 mt-2 flex justify-end gap-3 border-t border-slate-100">
                        <button type="button" @click="open = false" class="px-6 py-2.5 font-bold text-sm text-slate-500 hover:bg-slate-100 rounded-xl transition-colors btn-batal">Batal</button>
                        <button type="submit" class="px-6 py-2.5 font-bold text-sm bg-gradient-to-r from-orange-500 to-orange-400 text-white hover:shadow-lg hover:shadow-orange-500/30 hover:-translate-y-0.5 rounded-xl transition-all">Simpan Berat</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function openEditRekamModal(rekam) {
        document.getElementById('editRekamForm').action = `/admin/rekam-medis/${rekam.id}`;
        document.getElementById('edit_tanggal').value = rekam.tanggal;
        document.getElementById('edit_dokter').value = rekam.dokter_hewan || '';
        document.getElementById('edit_diagnosa').value = rekam.diagnosa;
        document.getElementById('edit_tindakan').value = rekam.tindakan;
        document.getElementById('edit_status').value = rekam.status;
        window.dispatchEvent(new CustomEvent('open-edit-rekam-modal'));
    }

    // Chart.js implementation
    document.addEventListener('DOMContentLoaded', function() {
        @if($selectedInventarisId && count($chartLabels) > 0)
        const ctx = document.getElementById('growthChart').getContext('2d');
        const labels = {!! json_encode($chartLabels) !!};
        const data = {!! json_encode($chartData) !!};

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Berat Badan (Kg)',
                    data: data,
                    borderColor: '#4e7f58',
                    backgroundColor: 'rgba(78, 127, 88, 0.2)',
                    borderWidth: 2,
                    tension: 0.3,
                    fill: true,
                    pointBackgroundColor: '#4e7f58'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: false
                    }
                }
            }
        });
        @endif
    });
</script>
@endsection
