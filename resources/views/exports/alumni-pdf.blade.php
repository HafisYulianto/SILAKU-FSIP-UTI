<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Data Alumni</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            color: #333;
        }
        h1 {
            text-align: center;
            color: #1e5a4a;
            border-bottom: 2px solid #1e5a4a;
            padding-bottom: 10px;
        }
        .info {
            margin-bottom: 20px;
            font-size: 12px;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        thead {
            background-color: #1e5a4a;
            color: white;
        }
        th {
            padding: 12px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #ddd;
        }
        td {
            padding: 10px;
            border: 1px solid #ddd;
            font-size: 11px;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #999;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        a {
            color: #1e5a4a;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div>
        <h1>Data Alumni</h1>
        <div class="info">
            <p><strong>Kategori:</strong> Alumni</p>
            <p><strong>Total Records:</strong> {{ $alumnis->count() }}</p>
            <p><strong>Tanggal Export:</strong> {{ now()->timezone('Asia/Jakarta')->format('d M Y H:i:s') }}</p>
        </div>

        @if ($alumnis->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th>Nama</th>
                        <th>Nama Perusahaan</th>
                        <th>Posisi</th>
                        <th>Lokasi</th>
                        <th>Program Studi</th>
                        <th>Diinput Oleh</th>
                        <th>Tanggal Dibuat</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($alumnis as $index => $alumni)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $alumni->nama }}</td>
                            <td>{{ $alumni->nama_perusahaan }}</td>
                            <td>{{ $alumni->posisi }}</td>
                            <td>📍 {{ $alumni->lokasi }}</td>
                            <td>{{ $alumni->programStudi?->name ?? 'Umum' }}</td>
                            <td>{{ $alumni->creator?->name ?? '-' }}</td>
                            <td>{{ $alumni->created_at->timezone('Asia/Jakarta')->format('d/m/Y H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p style="text-align: center; color: #999;">Tidak ada data untuk ditampilkan</p>
        @endif

        <div class="footer">
            <p>Dokumen ini dihasilkan oleh SILAKU FSIP - Sistem Pelaporan IKU</p>
        </div>
    </div>
</body>
</html>
