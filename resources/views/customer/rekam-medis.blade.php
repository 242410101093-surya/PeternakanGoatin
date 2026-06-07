@extends('layouts.customer')

@section('title', 'Rekam Medis')

@section('content')
<main class="max-w-[1200px] mx-auto px-6 py-10 space-y-8" data-aos="fade-up">
    <div class="flex items-center gap-2">
        <div class="w-1.5 h-5 rounded-full" style="background:#2A7844;"></div>
        <h2 class="text-xs font-bold uppercase tracking-wider" style="color:#64748B;">Rekam Medis Ternak Anda</h2>
    </div>

    <div class="glass-card p-6 md:p-8 overflow-hidden">
        <div class="overflow-x-auto rounded-xl border border-slate-100 shadow-sm bg-white">
            <table class="w-full text-left border-collapse premium-table-cust">
                <thead>
                    <tr>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-200">ID Ternak</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-200">Nama/Jenis</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-200">Tanggal Periksa</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-200">Diagnosa</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-200">Tindakan/Vaksin</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-200">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($rekamMedis as $rm)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4 text-xs font-extrabold text-slate-800">
                            #{{ $rm['id_ternak'] }}
                        </td>
                        <td class="px-6 py-4 text-xs font-bold text-slate-700">
                            {{ $rm['jenis'] }}
                        </td>
                        <td class="px-6 py-4 text-xs font-semibold text-slate-500">
                            {{ \Carbon\Carbon::parse($rm['tanggal'])->translatedFormat('d M Y') }}
                        </td>
                        <td class="px-6 py-4 text-xs font-medium text-slate-600">
                            {{ $rm['diagnosa'] }}
                        </td>
                        <td class="px-6 py-4 text-xs font-medium text-slate-600">
                            {{ $rm['tindakan'] }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="py-0.5 px-2.5 text-[10px] {{ $rm['status'] != 'Sehat' ? 'badge-premium-amber' : 'badge-premium-green' }}">
                                {{ $rm['status'] }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</main>
@endsection
