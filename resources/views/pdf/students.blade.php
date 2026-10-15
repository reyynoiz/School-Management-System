<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Data Siswa</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h2 { margin: 0; font-size: 16px; text-transform: uppercase; }
        .header p { margin: 3px 0 0; font-size: 11px; color: #666; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table, th, td { border: 1px solid #ccc; }
        th { background-color: #f3f4f6; padding: 6px; text-align: left; font-size: 10px; text-transform: uppercase; }
        td { padding: 6px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Laporan Data Siswa</h2>
        <p>Sistem Manajemen Sekolah</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 20%;">NIS</th>
                <th style="width: 35%;">Nama</th>
                <th style="width: 15%;">L/P</th>
                <th style="width: 25%;">Kelas</th>
            </tr>
        </thead>
        <tbody>
            @forelse($students as $index => $student)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td>{{ $student->nis }}</td>
                <td>{{ $student->name }}</td>
                <td>{{ $student->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                <td>{{ $student->schoolClass->name ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align: center;">Tidak ada data siswa.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>