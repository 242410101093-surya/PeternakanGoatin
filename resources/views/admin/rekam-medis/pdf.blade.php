<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Rekam Medis Ternak</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333;
            line-height: 1.5;
            margin: 0;
            padding: 0;
        }
        .header {
            border-bottom: 3px solid #1e4e2f;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .logo-title {
            float: left;
        }
        .logo-title h1 {
            color: #1e4e2f;
            margin: 0;
            font-size: 28px;
            font-weight: bold;
            letter-spacing: 1px;
        }
        .logo-title p {
            margin: 5px 0 0 0;
            color: #525f52;
            font-size: 14px;
        }
        .meta-info {
            float: right;
            text-align: right;
            font-size: 12px;
            color: #525f52;
        }
        .meta-info p {
            margin: 3px 0;
        }
        .clear {
            clear: both;
        }
        .table-container {
            margin-top: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }
        th {
            background-color: #1e4e2f;
            color: #ffffff;
            font-weight: bold;
            text-align: left;
            padding: 12px 10px;
            border-bottom: 2px solid #16431b;
        }
        td {
            padding: 10px;
            border-bottom: 1px solid #e8efe6;
            color: #2c3e50;
        }
        tr:nth-child(even) {
            background-color: #f8f9f4;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-sehat {
            background-color: #dff2dd;
            color: #16431b;
        }
        .status-sakit {
            background-color: #fde8c1;
            color: #5f3f15;
        }
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 10px;
            color: #9fae9a;
            border-top: 1px solid #e8efe6;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo-title">
            <h1>GOATIN</h1>
            <p>Sistem Informasi &amp; Stewardship Peternakan Kambing</p>
        </div>
        <div class="meta-info">
            <p><strong>Laporan Rekam Medis Ternak</strong></p>
            <p>Tanggal Cetak: {{ \Carbon\Carbon::now()->format('d M Y, H:i') }}</p>
            <p>Status: Semua Ternak</p>
        </div>
        <div class="clear"></div>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 15%;">Tanggal</th>
                    <th style="width: 20%;">Hewan (ID)</th>
                    <th style="width: 15%;">Dokter Hewan</th>
                    <th style="width: 15%;">Diagnosa</th>
                    <th style="width: 20%;">Tindakan</th>
                    <th style="width: 10%;">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rekamMedis as $index => $rekam)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($rekam->tanggal)->format('d M Y') }}</td>
                    <td>
                        <strong>{{ $rekam->inventaris->jenis }}</strong><br>
                        <span style="color:#777; font-size:10px;">ID: {{ $rekam->inventaris->id }}</span>
                    </td>
                    <td>{{ $rekam->dokter_hewan ?? '-' }}</td>
                    <td>{{ $rekam->diagnosa }}</td>
                    <td>{{ $rekam->tindakan }}</td>
                    <td>
                        @php
                            $isSakit = str_contains(strtolower($rekam->status), 'sakit') || str_contains(strtolower($rekam->status), 'perawatan');
                        @endphp
                        <span class="status-badge {{ $isSakit ? 'status-sakit' : 'status-sehat' }}">
                            {{ $rekam->status }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 20px; color: #777;">
                        Belum ada data rekam medis kambing yang tercatat.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="footer">
        Laporan Rekam Medis Otomatis Goatin Stewardship Portal - Halaman 1
    </div>
</body>
</html>
