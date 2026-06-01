@extends('layouts.admin')

@section('title', 'Financial Reports')

@section('content')
<div class="w-full px-margin-mobile md:px-margin-desktop">
    <div class="max-w-container-max mx-auto space-y-stack-xl">
    <!-- Page Header & Actions -->
    <div class="flex flex-col md:flex-row md:items-end justify-between mb-stack-lg gap-stack-md">
        <div>
            <h2 class="font-h2 text-h2 text-on-surface mb-stack-xs">Laporan Keuangan</h2>
            <p class="font-body-md text-body-md text-on-surface-variant">Review revenue, expenses, and overall stewardship health.</p>
        </div>
        <div class="flex flex-wrap items-center gap-stack-sm whitespace-nowrap">
            <button onclick="document.getElementById('addKeuanganModal').classList.remove('hidden')" class="flex items-center bg-primary text-on-primary font-label-sm text-label-sm px-4 py-2 rounded-lg hover:bg-surface-tint transition-colors ambient-shadow">
                <span class="material-symbols-outlined mr-2 text-[18px]">add</span>
                Catat Transaksi Baru
            </button>
        </div>
    </div>
    
    <!-- Bento Grid: Key Metrics -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-gutter">
        <!-- Revenue Card -->
        <div class="bg-surface-container-lowest rounded-xl p-6 border border-outline-variant hover:ambient-shadow transition-shadow group">
            <div class="flex justify-between items-start mb-stack-md">
                <div class="w-10 h-10 rounded-full bg-secondary-container text-on-secondary-container flex items-center justify-center">
                    <span class="material-symbols-outlined">payments</span>
                </div>
            </div>
            <h3 class="font-caption text-caption text-on-surface-variant uppercase tracking-wider mb-1">Total Pemasukan</h3>
            <p class="font-h2 text-h2 text-on-surface group-hover:text-primary transition-colors">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
        </div>
        <!-- Expenses Card -->
        <div class="bg-surface-container-lowest rounded-xl p-6 border border-outline-variant hover:ambient-shadow transition-shadow group">
            <div class="flex justify-between items-start mb-stack-md">
                <div class="w-10 h-10 rounded-full bg-tertiary-fixed text-on-tertiary-fixed flex items-center justify-center">
                    <span class="material-symbols-outlined">receipt_long</span>
                </div>
            </div>
            <h3 class="font-caption text-caption text-on-surface-variant uppercase tracking-wider mb-1">Total Pengeluaran</h3>
            <p class="font-h2 text-h2 text-on-surface group-hover:text-tertiary transition-colors">Rp {{ number_format($totalExpenses, 0, ',', '.') }}</p>
        </div>
        <!-- Net Profit Card -->
        <div class="bg-surface-container-lowest rounded-xl p-6 border border-outline-variant hover:ambient-shadow transition-shadow relative overflow-hidden group">
            <!-- Decorative bg -->
            <div class="absolute -right-6 -top-6 w-32 h-32 bg-primary-fixed rounded-full blur-3xl opacity-20 group-hover:opacity-40 transition-opacity"></div>
            <div class="flex justify-between items-start mb-stack-md relative z-10">
                <div class="w-10 h-10 rounded-full bg-primary-container text-on-primary-container flex items-center justify-center">
                    <span class="material-symbols-outlined">account_balance</span>
                </div>
            </div>
            <h3 class="font-caption text-caption text-on-surface-variant uppercase tracking-wider mb-1 relative z-10">Laba Bersih</h3>
            <p class="font-h2 text-h2 text-on-surface group-hover:text-primary transition-colors relative z-10">Rp {{ number_format($netProfit, 0, ',', '.') }}</p>
        </div>
    </div>
    
    <!-- Main Content Split: Transactions Table -->
    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl flex flex-col">
        <div class="p-6 border-b border-surface-variant flex justify-between items-center bg-surface-bright">
            <h3 class="font-h3 text-h3 text-on-surface">Riwayat Transaksi</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-surface-container-low border-b border-surface-variant font-label-sm text-label-sm text-on-surface-variant">
                        <th class="py-4 px-6 font-medium">Tanggal</th>
                        <th class="py-4 px-6 font-medium">Jenis Transaksi</th>
                        <th class="py-4 px-6 font-medium">Keterangan</th>
                        <th class="py-4 px-6 font-medium">Jumlah</th>
                        <th class="py-4 px-6 font-medium text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="font-body-md text-body-md text-on-surface divide-y divide-surface-variant">
                    @forelse($laporans as $laporan)
                    <tr class="hover:bg-surface-container-lowest/50 transition-colors group">
                        <td class="py-4 px-6 text-on-surface-variant">{{ \Carbon\Carbon::parse($laporan->tanggal)->format('d M Y') }}</td>
                        <td class="py-4 px-6">
                            @if($laporan->jenis_transaksi == 'Pemasukan')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full border font-caption text-caption font-semibold bg-secondary-container/30 text-secondary border-secondary-container">
                                    Pemasukan
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full border font-caption text-caption font-semibold bg-tertiary-fixed/30 text-tertiary border-tertiary-fixed">
                                    Pengeluaran
                                </span>
                            @endif
                        </td>
                        <td class="py-4 px-6 text-on-surface-variant">{{ $laporan->keterangan }}</td>
                        <td class="py-4 px-6">
                            <span class="font-bold {{ $laporan->jenis_transaksi == 'Pemasukan' ? 'text-primary' : 'text-on-surface' }}">
                                {{ $laporan->jenis_transaksi == 'Pemasukan' ? '+' : '-' }} Rp {{ number_format($laporan->jumlah, 0, ',', '.') }}
                            </span>
                        </td>
                        <td class="py-4 px-6 text-right">
                            <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <button onclick="openEditKeuanganModal({{ $laporan }})" class="p-1.5 text-on-surface-variant hover:text-primary rounded">
                                    <span class="material-symbols-outlined text-sm">edit</span>
                                </button>
                                <form action="{{ route('admin.keuangan.destroy', $laporan->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus transaksi ini?');">
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
                        <td colspan="5" class="py-8 px-6 text-center text-on-surface-variant">Belum ada transaksi tercatat.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-surface-variant bg-surface-container/30">
            {{ $laporans->links() }}
        </div>
    </div>
    </div>
</div>

<!-- Modal Tambah Transaksi -->
<div id="addKeuanganModal" class="fixed inset-0 z-50 hidden bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-surface-container-lowest rounded-2xl w-full max-w-lg shadow-2xl border border-surface-variant">
        <div class="p-6 border-b border-surface-variant flex items-center justify-between">
            <h3 class="font-h3 text-h3 text-on-surface">Catat Transaksi</h3>
            <button onclick="document.getElementById('addKeuanganModal').classList.add('hidden')" class="text-on-surface-variant hover:text-error transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form action="{{ route('admin.keuangan.store') }}" method="POST" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="block font-label-sm text-label-sm text-on-surface-variant mb-1">Tanggal</label>
                <input type="date" name="tanggal" required value="{{ date('Y-m-d') }}" class="w-full px-3 py-2 rounded-lg border border-outline-variant focus:border-primary bg-surface-bright text-on-surface">
            </div>
            <div>
                <label class="block font-label-sm text-label-sm text-on-surface-variant mb-1">Jenis Transaksi</label>
                <select name="jenis_transaksi" required class="w-full px-3 py-2 rounded-lg border border-outline-variant focus:border-primary bg-surface-bright text-on-surface">
                    <option value="Pemasukan">Pemasukan</option>
                    <option value="Pengeluaran">Pengeluaran</option>
                </select>
            </div>
            <div>
                <label class="block font-label-sm text-label-sm text-on-surface-variant mb-1">Keterangan</label>
                <input type="text" name="keterangan" required class="w-full px-3 py-2 rounded-lg border border-outline-variant focus:border-primary bg-surface-bright text-on-surface" placeholder="Contoh: Penjualan Susu">
            </div>
            <div>
                <label class="block font-label-sm text-label-sm text-on-surface-variant mb-1">Jumlah (Rp)</label>
                <input type="number" name="jumlah" required min="0" class="w-full px-3 py-2 rounded-lg border border-outline-variant focus:border-primary bg-surface-bright text-on-surface">
            </div>
            <div class="pt-4 flex justify-end gap-3 border-t border-surface-variant">
                <button type="button" onclick="document.getElementById('addKeuanganModal').classList.add('hidden')" class="px-4 py-2 font-label-sm text-label-sm text-on-surface-variant hover:bg-surface-container rounded-lg transition-colors">Batal</button>
                <button type="submit" class="px-4 py-2 font-label-sm text-label-sm bg-primary text-on-primary hover:bg-primary-container rounded-lg transition-colors shadow-sm">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Transaksi -->
<div id="editKeuanganModal" class="fixed inset-0 z-50 hidden bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-surface-container-lowest rounded-2xl w-full max-w-lg shadow-2xl border border-surface-variant">
        <div class="p-6 border-b border-surface-variant flex items-center justify-between">
            <h3 class="font-h3 text-h3 text-on-surface">Edit Transaksi</h3>
            <button onclick="document.getElementById('editKeuanganModal').classList.add('hidden')" class="text-on-surface-variant hover:text-error transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form id="editKeuanganForm" method="POST" class="p-6 space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block font-label-sm text-label-sm text-on-surface-variant mb-1">Tanggal</label>
                <input type="date" name="tanggal" id="edit_tanggal" required class="w-full px-3 py-2 rounded-lg border border-outline-variant focus:border-primary bg-surface-bright text-on-surface">
            </div>
            <div>
                <label class="block font-label-sm text-label-sm text-on-surface-variant mb-1">Jenis Transaksi</label>
                <select name="jenis_transaksi" id="edit_jenis" required class="w-full px-3 py-2 rounded-lg border border-outline-variant focus:border-primary bg-surface-bright text-on-surface">
                    <option value="Pemasukan">Pemasukan</option>
                    <option value="Pengeluaran">Pengeluaran</option>
                </select>
            </div>
            <div>
                <label class="block font-label-sm text-label-sm text-on-surface-variant mb-1">Keterangan</label>
                <input type="text" name="keterangan" id="edit_keterangan" required class="w-full px-3 py-2 rounded-lg border border-outline-variant focus:border-primary bg-surface-bright text-on-surface">
            </div>
            <div>
                <label class="block font-label-sm text-label-sm text-on-surface-variant mb-1">Jumlah (Rp)</label>
                <input type="number" name="jumlah" id="edit_jumlah" required min="0" class="w-full px-3 py-2 rounded-lg border border-outline-variant focus:border-primary bg-surface-bright text-on-surface">
            </div>
            <div class="pt-4 flex justify-end gap-3 border-t border-surface-variant">
                <button type="button" onclick="document.getElementById('editKeuanganModal').classList.add('hidden')" class="px-4 py-2 font-label-sm text-label-sm text-on-surface-variant hover:bg-surface-container rounded-lg transition-colors">Batal</button>
                <button type="submit" class="px-4 py-2 font-label-sm text-label-sm bg-primary text-on-primary hover:bg-primary-container rounded-lg transition-colors shadow-sm">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openEditKeuanganModal(laporan) {
        document.getElementById('editKeuanganForm').action = `/admin/keuangan/${laporan.id}`;
        document.getElementById('edit_tanggal').value = laporan.tanggal;
        document.getElementById('edit_jenis').value = laporan.jenis_transaksi;
        document.getElementById('edit_keterangan').value = laporan.keterangan;
        document.getElementById('edit_jumlah').value = laporan.jumlah;
        document.getElementById('editKeuanganModal').classList.remove('hidden');
    }
</script>

@endsection
