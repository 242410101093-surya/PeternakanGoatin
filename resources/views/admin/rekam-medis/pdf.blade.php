<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Rekam Medis Ternak - GOATIN</title>
    <style>
        @page {
            margin: 0cm;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #1e293b;
            line-height: 1.5;
            margin: 0;
            padding: 1.5cm;
            background-color: #ffffff;
        }
        .header-container {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 20px 24px;
            margin-bottom: 25px;
            border-left: 6px solid #2A7844;
            position: relative;
        }
        .logo-title {
            float: left;
        }
        .logo-title h1 {
            color: #ffffff;
            margin: 0;
            font-size: 26px;
            font-weight: 800;
            letter-spacing: 2px;
        }
        .logo-title p {
            margin: 4px 0 0 0;
            color: #8EB69B;
            font-size: 11px;
            text-transform: uppercase;
            font-weight: bold;
            letter-spacing: 1px;
        }
        .meta-info {
            float: right;
            text-align: right;
            font-size: 11px;
            color: #475569;
            margin-top: 5px;
        }
        .meta-info p {
            margin: 2px 0;
        }
        .clear {
            clear: both;
        }
        
        /* Dashboard Stats Row */
        .stats-row {
            margin-bottom: 25px;
            width: 100%;
        }
        .stat-card {
            float: left;
            width: 30%;
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 12px 15px;
            border-radius: 8px;
            margin-right: 3%;
        }
        .stat-card.last {
            margin-right: 0;
        }
        .stat-title {
            font-size: 9px;
            text-transform: uppercase;
            color: #64748b;
            font-weight: bold;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }
        .stat-value {
            font-size: 18px;
            font-weight: 800;
            color: #051F20;
        }
        .stat-desc {
            font-size: 8px;
            color: #94a3b8;
            margin-top: 2px;
        }

        /* Table Styling */
        .table-title {
            font-size: 14px;
            font-weight: bold;
            color: #051F20;
            margin-bottom: 12px;
            border-left: 3px solid #2A7844;
            padding-left: 8px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }
        th {
            background-color: #163832;
            color: #ffffff;
            font-weight: bold;
            text-align: left;
            padding: 10px 8px;
            text-transform: uppercase;
            font-size: 9px;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #2A7844;
        }
        td {
            padding: 9px 8px;
            border-bottom: 1px solid #f1f5f9;
            color: #334155;
            vertical-align: top;
        }
        tr:nth-child(even) td {
            background-color: #f8fafc;
        }
        .animal-name {
            font-weight: bold;
            color: #0f172a;
        }
        .animal-id {
            color: #64748b;
            font-size: 9px;
            margin-top: 1px;
        }
        .status-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
            text-align: center;
        }
        .status-sehat {
            background-color: #dcfce7;
            color: #166534;
            border: 1px solid #bbf7d0;
        }
        .status-sakit {
            background-color: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }
        .status-pemulihan {
            background-color: #fef3c7;
            color: #92400e;
            border: 1px solid #fde68a;
        }
        
        /* Signature Area */
        .signature-section {
            margin-top: 40px;
            text-align: right;
            font-size: 11px;
            color: #334155;
        }
        .signature-box {
            float: right;
            width: 200px;
            text-align: center;
        }
        .signature-line {
            margin-top: 50px;
            border-top: 1px solid #94a3b8;
            padding-top: 5px;
            font-weight: bold;
        }
        
        .footer {
            position: fixed;
            bottom: 1cm;
            left: 1.5cm;
            right: 1.5cm;
            text-align: center;
            font-size: 9px;
            color: #94a3b8;
            border-top: 1px solid #f1f5f9;
            padding-top: 8px;
        }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="overflow-x-hidden">
    @php
        $total = $rekamMedis->count();
        $sehat = $rekamMedis->filter(function($r) {
            $status = strtolower($r->status);
            return !str_contains($status, 'sakit') && !str_contains($status, 'flu') && !str_contains($status, 'pemulihan') && !str_contains($status, 'perawatan');
        })->count();
        $pemulihan = $rekamMedis->filter(function($r) {
            $status = strtolower($r->status);
            return str_contains($status, 'pemulihan') || str_contains($status, 'perawatan');
        })->count();
        $sakit = $total - $sehat - $pemulihan;
    @endphp

    <div class="header-container">
        <div class="logo-title" style="margin-top: 5px;">
            <img src="{{ public_path('images/logo-pdf.png') }}" alt="Goatin Logo" style="height: 38px; width: auto; display: block;">
        </div>
        <div class="meta-info">
            <p style="color: #051F20;"><strong>LAPORAN REKAM MEDIS</strong></p>
            <p>Tanggal: {{ \Carbon\Carbon::now('Asia/Jakarta')->format('d M Y, H:i') }} WIB</p>
            <p>Sistem: Goatin Stewardship Portal</p>
        </div>
        <div class="clear"></div>
    </div>

    <!-- Summary Statistics Dashboard Card -->
    <div class="stats-row">
        <div class="stat-card">
            <div class="stat-title">Total Rekam Medis</div>
            <div class="stat-value">{{ $total }}</div>
            <div class="stat-desc">Semua pemeriksaan medis tercatat</div>
        </div>
        <div class="stat-card">
            <div class="stat-title">Ternak Sehat</div>
            <div class="stat-value" style="color: #166534;">{{ $sehat }}</div>
            <div class="stat-desc">Kondisi sehat tanpa gejala</div>
        </div>
        <div class="stat-card last">
            <div class="stat-title">Perawatan &amp; Sakit</div>
            <div class="stat-value" style="color: #991b1b;">{{ $pemulihan + $sakit }}</div>
            <div class="stat-desc">Membutuhkan perhatian intensif</div>
        </div>
        <div class="clear"></div>
    </div>

    <div class="table-container">
        <div class="table-title">Riwayat Rekam Medis Detail</div>
        <table>
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 14%;">Tanggal</th>
                    <th style="width: 20%;">Hewan (ID)</th>
                    <th style="width: 16%;">Pemeriksa</th>
                    <th style="width: 18%;">Diagnosa</th>
                    <th style="width: 17%;">Tindakan</th>
                    <th style="width: 10%;">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rekamMedis as $index => $rekam)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($rekam->tanggal)->format('d M Y') }}</td>
                    <td>
                        <div class="animal-name">{{ $rekam->inventaris->jenis }}</div>
                        <div class="animal-id">ID: #{{ str_pad($rekam->inventaris->id, 4, '0', STR_PAD_LEFT) }}</div>
                    </td>
                    <td>{{ $rekam->dokter_hewan ?? 'Dokter Mandiri' }}</td>
                    <td>{{ $rekam->diagnosa }}</td>
                    <td>{{ $rekam->tindakan }}</td>
                    <td>
                        @php
                            $statusLower = strtolower($rekam->status);
                            $badgeClass = 'status-sehat';
                            if (str_contains($statusLower, 'sakit') || str_contains($statusLower, 'flu')) {
                                $badgeClass = 'status-sakit';
                            } elseif (str_contains($statusLower, 'pemulihan') || str_contains($statusLower, 'perawatan')) {
                                $badgeClass = 'status-pemulihan';
                            }
                        @endphp
                        <span class="status-badge {{ $badgeClass }}">
                            {{ $rekam->status }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 25px; color: #64748b;">
                        Belum ada data rekam medis kambing yang tercatat.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="footer">
        Dokumen ini dihasilkan secara otomatis oleh Goatin Stewardship Portal pada tanggal {{ \Carbon\Carbon::now('Asia/Jakarta')->format('d F Y') }} WIB. Halaman 1 dari 1.
    </div>
</body>
</html>
