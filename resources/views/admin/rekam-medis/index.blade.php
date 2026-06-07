@extends('layouts.admin')

@section('title', 'Medical Records')

@section('content')
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="w-full px-margin-mobile md:px-margin-desktop">
    <div class="max-w-container-max mx-auto space-y-stack-xl">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-stack-md mb-stack-lg">
        <div>
            <h1 class="font-h1 text-h1 text-on-surface mb-stack-xs">Medical Records & Growth</h1>
            <p class="font-body-md text-body-md text-on-surface-variant">Overview of livestock health checks, vaccinations, and growth monitoring.</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.rekam-medis.export-pdf') }}" download class="bg-primary text-on-primary flex items-center gap-2 px-4 py-2 rounded-lg font-label-sm text-label-sm hover:opacity-90 transition-opacity shadow-ambient">
                <span class="material-symbols-outlined text-[18px]">download</span>
                Export Report
            </a>
            <button onclick="document.getElementById('addBeratModal').classList.remove('hidden')" class="bg-surface-container-lowest border border-outline-variant text-on-surface flex items-center gap-2 px-4 py-2 rounded-lg font-label-sm text-label-sm hover:bg-surface-container-low transition-colors shadow-ambient">
                <span class="material-symbols-outlined text-[18px]">scale</span>
                Catat Berat
            </button>
            <button onclick="document.getElementById('addRekamModal').classList.remove('hidden')" class="bg-primary-container text-on-primary-container flex items-center gap-2 px-4 py-2 rounded-lg font-label-sm text-label-sm hover:bg-primary transition-colors shadow-ambient hover:text-white">
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
                            {{ $inv->jenis }} {{ $inv->ras ? '- '.$inv->ras : '' }} (ID: {{ $inv->id }})
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
    <div class="bg-surface-container-lowest rounded-xl border border-surface-variant shadow-ambient overflow-hidden flex flex-col">
        <div class="p-6 border-b border-surface-variant flex justify-between items-center bg-surface-bright">
            <h3 class="font-h3 text-h3 text-on-background">Riwayat Rekam Medis</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-surface-container-low border-b border-surface-variant font-label-sm text-label-sm text-on-surface-variant">
                        <th class="py-4 px-6 font-medium">Tanggal</th>
                        <th class="py-4 px-6 font-medium">Hewan (ID)</th>
                        <th class="py-4 px-6 font-medium">Dokter Hewan</th>
                        <th class="py-4 px-6 font-medium">Diagnosa</th>
                        <th class="py-4 px-6 font-medium">Tindakan</th>
                        <th class="py-4 px-6 font-medium">Status</th>
                        <th class="py-4 px-6 font-medium text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="font-body-md text-body-md text-on-surface divide-y divide-surface-variant">
                    @forelse($rekamMedis as $rekam)
                    <tr class="hover:bg-surface-container-lowest/50 transition-colors group">
                        <td class="py-4 px-6 text-on-surface-variant">{{ \Carbon\Carbon::parse($rekam->tanggal)->format('d M Y') }}</td>
                        <td class="py-4 px-6">
                            <div class="font-bold text-on-background">{{ $rekam->inventaris->jenis }} ({{ $rekam->inventaris->id }})</div>
                        </td>
                        <td class="py-4 px-6 text-on-surface-variant">{{ $rekam->dokter_hewan ?? '-' }}</td>
                        <td class="py-4 px-6 text-on-surface-variant">{{ $rekam->diagnosa }}</td>
                        <td class="py-4 px-6 text-on-surface-variant">{{ $rekam->tindakan }}</td>
                        <td class="py-4 px-6">
                            @php
                                $statusColor = 'bg-secondary-container/30 text-secondary border-secondary-container';
                                if(str_contains(strtolower($rekam->status), 'sakit') || str_contains(strtolower($rekam->status), 'perawatan')) {
                                    $statusColor = 'bg-tertiary-fixed/30 text-tertiary border-tertiary-fixed';
                                }
                            @endphp
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full border font-caption text-caption font-semibold {{ $statusColor }}">
                                {{ $rekam->status }}
                            </span>
                        </td>
                        <td class="py-4 px-6 text-right">
                            <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <button onclick="openEditRekamModal({{ $rekam }})" class="p-1.5 text-on-surface-variant hover:text-primary rounded">
                                    <span class="material-symbols-outlined text-sm">edit</span>
                                </button>
                                <form action="{{ route('admin.rekam-medis.destroy', $rekam->id) }}" method="POST" class="inline delete-form" data-message="Yakin ingin menghapus data rekam medis ini? Tindakan ini tidak bisa dibatalkan.">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-on-surface-variant hover:text-error rounded">
                                        <span class="material-symbols-outlined text-sm">delete</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-8 px-6 text-center text-on-surface-variant">Belum ada data rekam medis.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    </div>
</div>

<!-- Modal Tambah Rekam Medis -->
<div id="addRekamModal" class="fixed inset-0 z-50 hidden bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-surface-container-lowest rounded-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto shadow-2xl border border-surface-variant">
        <div class="p-6 border-b border-surface-variant flex items-center justify-between sticky top-0 bg-surface-container-lowest z-10">
            <h3 class="font-h3 text-h3 text-on-surface">Tambah Rekam Medis</h3>
            <button onclick="document.getElementById('addRekamModal').classList.add('hidden')" class="text-on-surface-variant hover:text-error transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form action="{{ route('admin.rekam-medis.store') }}" method="POST" class="p-6 space-y-4">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant mb-1">Ternak</label>
                    <select name="inventaris_id" required class="w-full px-3 py-2 rounded-lg border border-outline-variant focus:border-primary bg-surface-bright text-on-surface">
                        @foreach($inventarisList as $inv)
                            <option value="{{ $inv->id }}">{{ $inv->jenis }} (ID: {{ $inv->id }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant mb-1">Tanggal</label>
                    <input type="date" name="tanggal" required value="{{ date('Y-m-d') }}" class="w-full px-3 py-2 rounded-lg border border-outline-variant focus:border-primary bg-surface-bright text-on-surface">
                </div>
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant mb-1">Dokter Hewan</label>
                    <input type="text" name="dokter_hewan" class="w-full px-3 py-2 rounded-lg border border-outline-variant focus:border-primary bg-surface-bright text-on-surface">
                </div>
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant mb-1">Status Keadaan</label>
                    <input type="text" name="status" required class="w-full px-3 py-2 rounded-lg border border-outline-variant focus:border-primary bg-surface-bright text-on-surface" placeholder="Sehat / Masa Pemulihan">
                </div>
            </div>
            <div>
                <label class="block font-label-sm text-label-sm text-on-surface-variant mb-1">Diagnosa</label>
                <textarea name="diagnosa" required rows="2" class="w-full px-3 py-2 rounded-lg border border-outline-variant focus:border-primary bg-surface-bright text-on-surface"></textarea>
            </div>
            <div>
                <label class="block font-label-sm text-label-sm text-on-surface-variant mb-1">Tindakan</label>
                <textarea name="tindakan" required rows="2" class="w-full px-3 py-2 rounded-lg border border-outline-variant focus:border-primary bg-surface-bright text-on-surface"></textarea>
            </div>
            <div class="pt-4 flex justify-end gap-3 border-t border-surface-variant">
                <button type="button" onclick="document.getElementById('addRekamModal').classList.add('hidden')" class="px-4 py-2 font-label-sm text-label-sm text-on-surface-variant hover:bg-surface-container rounded-lg transition-colors">Batal</button>
                <button type="submit" class="px-4 py-2 font-label-sm text-label-sm bg-primary text-on-primary hover:bg-primary-container rounded-lg transition-colors shadow-sm">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Rekam Medis -->
<div id="editRekamModal" class="fixed inset-0 z-50 hidden bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-surface-container-lowest rounded-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto shadow-2xl border border-surface-variant">
        <div class="p-6 border-b border-surface-variant flex items-center justify-between sticky top-0 bg-surface-container-lowest z-10">
            <h3 class="font-h3 text-h3 text-on-surface">Edit Rekam Medis</h3>
            <button onclick="document.getElementById('editRekamModal').classList.add('hidden')" class="text-on-surface-variant hover:text-error transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form id="editRekamForm" method="POST" class="p-6 space-y-4">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant mb-1">Tanggal</label>
                    <input type="date" name="tanggal" id="edit_tanggal" required class="w-full px-3 py-2 rounded-lg border border-outline-variant focus:border-primary bg-surface-bright text-on-surface">
                </div>
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant mb-1">Dokter Hewan</label>
                    <input type="text" name="dokter_hewan" id="edit_dokter" class="w-full px-3 py-2 rounded-lg border border-outline-variant focus:border-primary bg-surface-bright text-on-surface">
                </div>
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant mb-1">Status Keadaan</label>
                    <input type="text" name="status" id="edit_status" required class="w-full px-3 py-2 rounded-lg border border-outline-variant focus:border-primary bg-surface-bright text-on-surface">
                </div>
            </div>
            <div>
                <label class="block font-label-sm text-label-sm text-on-surface-variant mb-1">Diagnosa</label>
                <textarea name="diagnosa" id="edit_diagnosa" required rows="2" class="w-full px-3 py-2 rounded-lg border border-outline-variant focus:border-primary bg-surface-bright text-on-surface"></textarea>
            </div>
            <div>
                <label class="block font-label-sm text-label-sm text-on-surface-variant mb-1">Tindakan</label>
                <textarea name="tindakan" id="edit_tindakan" required rows="2" class="w-full px-3 py-2 rounded-lg border border-outline-variant focus:border-primary bg-surface-bright text-on-surface"></textarea>
            </div>
            <div class="pt-4 flex justify-end gap-3 border-t border-surface-variant">
                <button type="button" onclick="document.getElementById('editRekamModal').classList.add('hidden')" class="px-4 py-2 font-label-sm text-label-sm text-on-surface-variant hover:bg-surface-container rounded-lg transition-colors">Batal</button>
                <button type="submit" class="px-4 py-2 font-label-sm text-label-sm bg-primary text-on-primary hover:bg-primary-container rounded-lg transition-colors shadow-sm">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Catat Berat Badan -->
<div id="addBeratModal" class="fixed inset-0 z-50 hidden bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-surface-container-lowest rounded-2xl w-full max-w-sm shadow-2xl border border-surface-variant">
        <div class="p-6 border-b border-surface-variant flex items-center justify-between">
            <h3 class="font-h3 text-h3 text-on-surface">Catat Berat Badan</h3>
            <button onclick="document.getElementById('addBeratModal').classList.add('hidden')" class="text-on-surface-variant hover:text-error transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form action="{{ route('admin.rekam-medis.berat') }}" method="POST" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="block font-label-sm text-label-sm text-on-surface-variant mb-1">Ternak</label>
                <select name="inventaris_id" required class="w-full px-3 py-2 rounded-lg border border-outline-variant focus:border-primary bg-surface-bright text-on-surface">
                    @foreach($inventarisList as $inv)
                        <option value="{{ $inv->id }}">{{ $inv->jenis }} (ID: {{ $inv->id }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block font-label-sm text-label-sm text-on-surface-variant mb-1">Tanggal</label>
                <input type="date" name="tanggal_pencatatan" required value="{{ date('Y-m-d') }}" class="w-full px-3 py-2 rounded-lg border border-outline-variant focus:border-primary bg-surface-bright text-on-surface">
            </div>
            <div>
                <label class="block font-label-sm text-label-sm text-on-surface-variant mb-1">Berat (Kg)</label>
                <input type="number" step="0.01" name="berat" required min="0" class="w-full px-3 py-2 rounded-lg border border-outline-variant focus:border-primary bg-surface-bright text-on-surface">
            </div>
            <div class="pt-4 flex justify-end gap-3 border-t border-surface-variant">
                <button type="button" onclick="document.getElementById('addBeratModal').classList.add('hidden')" class="px-4 py-2 font-label-sm text-label-sm text-on-surface-variant hover:bg-surface-container rounded-lg transition-colors">Batal</button>
                <button type="submit" class="px-4 py-2 font-label-sm text-label-sm bg-primary text-on-primary hover:bg-primary-container rounded-lg transition-colors shadow-sm">Simpan</button>
            </div>
        </form>
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
        document.getElementById('editRekamModal').classList.remove('hidden');
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
