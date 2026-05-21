<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ $entity->name }}</title>
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
        <h1>{{ $entity->name }}</h1>
        <div class="info">
            <p><strong>Deskripsi:</strong> {{ $entity->description ?? '-' }}</p>
            <p><strong>Kategori:</strong> {{ ucfirst($entity->root_category) }}</p>
            <p><strong>Total Records:</strong> {{ $records->count() }}</p>
            <p><strong>Tanggal Export:</strong> {{ now()->format('d M Y H:i:s') }}</p>
        </div>

        @if ($records->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        @foreach ($tableFields as $field)
                            <th>{{ $field->name }}</th>
                        @endforeach
                        <th>Dibuat Oleh</th>
                        <th>Program Studi</th>
                        <th>Tanggal Dibuat</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($records as $index => $record)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            @foreach ($tableFields as $field)
                                <td>
                                    @php
                                        $value = $record->getFieldValue($field->slug, '');
                                    @endphp
                                    
                                    @if ($field->type === 'file')
                                        @if ($value)
                                            <a href="{{ url('storage/' . $value) }}" target="_blank">View File</a>
                                        @else
                                            -
                                        @endif
                                    @elseif ($field->type === 'url' && $value)
                                        <a href="{{ $value }}" target="_blank">Link</a>
                                    @else
                                        {{ $value ?? '-' }}
                                    @endif
                                </td>
                            @endforeach
                            <td>{{ $record->creator?->name ?? '-' }}</td>
                            <td>{{ $record->programStudi?->name ?? 'Umum' }}</td>
                            <td>{{ $record->created_at->format('d/m/Y H:i') }}</td>
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
