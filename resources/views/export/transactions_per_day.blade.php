<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $title }} - {{ $date }}</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; }
        h3 { margin-bottom: 16px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #999; padding: 5px; text-align: left; }
        th { background-color: #eee; }
        .text-end { text-align: right; }
    </style>
</head>
<body>
    <h3>{{ $title }} - {{ $date }}</h3>
    <table>
        <thead>
            <tr>
                <th width="5%">#</th>
                <th>Nomor</th>
                <th>Tanggal</th>
                <th class="text-end">Total</th>
                <th>User</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rows as $row)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $row->number }}</td>
                <td>{{ $row->date }}</td>
                <td class="text-end">{{ number_format($row->total_amount, 0, ',', '.') }}</td>
                <td>{{ $row->user_name }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5">Tidak ada data pada tanggal {{ $date }}.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
