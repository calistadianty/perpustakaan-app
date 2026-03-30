<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Anggota</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; color: #1a1a1a; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 3px solid #1e40af; padding-bottom: 15px; }
        .header h1 { font-size: 20px; color: #1e40af; letter-spacing: 2px; }
        .header h2 { font-size: 14px; color: #374151; margin-top: 4px; }
        .header p { font-size: 10px; color: #666; margin-top: 4px; }
        .meta { margin-bottom: 15px; font-size: 10px; }
        .meta table { width: 100%; }
        .meta td { padding: 2px 0; }
        .meta .label { color: #666; width: 130px; }
        .meta .value { font-weight: bold; }
        table.data { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table.data th { background: #1e40af; color: white; padding: 8px 6px; text-align: left; font-size: 10px; text-transform: uppercase; letter-spacing: 0.5px; }
        table.data td { padding: 7px 6px; border-bottom: 1px solid #e5e7eb; font-size: 10px; }
        table.data tr:nth-child(even) { background: #f8fafc; }
        .badge { padding: 2px 8px; border-radius: 10px; font-size: 9px; font-weight: bold; }
        .badge-aktif { background: #dbeafe; color: #1e40af; }
        .badge-none { background: #f3f4f6; color: #6b7280; }
        .summary { margin-top: 15px; background: #f8fafc; padding: 10px; border-radius: 4px; border: 1px solid #e5e7eb; }
        .summary p { font-size: 10px; margin: 3px 0; }
        .footer { margin-top: 20px; text-align: center; font-size: 9px; color: #999; border-top: 1px solid #e5e7eb; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>RUMAH BACA</h1>
        <h2>Laporan Data Anggota Perpustakaan</h2>
        <p>Dicetak pada: {{ $tanggalCetak }}</p>
    </div>

    <div class="meta">
        <table>
            <tr>
                <td class="label">Tanggal Cetak</td>
                <td class="value">: {{ $tanggalCetak }}</td>
            </tr>
            <tr>
                <td class="label">Total Anggota</td>
                <td class="value">: {{ $anggota->count() }} orang</td>
            </tr>
        </table>
    </div>

    <table class="data">
        <thead>
            <tr>
                <th style="width:30px">No</th>
                <th>Nama</th>
                <th>Username</th>
                <th>Email</th>
                <th>Alamat</th>
                <th>Terdaftar</th>
                <th style="text-align:center">Total Pinjam</th>
                <th style="text-align:center">Sedang Dipinjam</th>
            </tr>
        </thead>
        <tbody>
            @forelse($anggota as $i => $a)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td><strong>{{ $a->name }}</strong></td>
                <td>{{ $a->username }}</td>
                <td>{{ $a->email }}</td>
                <td>{{ $a->alamat ?? '-' }}</td>
                <td>{{ $a->created_at->format('d/m/Y') }}</td>
                <td style="text-align:center">{{ $a->peminjamans_count }}</td>
                <td style="text-align:center">
                    @if($a->sedang_dipinjam_count > 0)
                        <span class="badge badge-aktif">{{ $a->sedang_dipinjam_count }} buku</span>
                    @else
                        <span class="badge badge-none">-</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align:center; padding:20px; color:#999;">Tidak ada anggota terdaftar.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="summary">
        <p><strong>Ringkasan:</strong></p>
        <p>
            Total Anggota: {{ $anggota->count() }} |
            Total Pernah Meminjam: {{ $anggota->where('peminjamans_count', '>', 0)->count() }} |
            Sedang Meminjam: {{ $anggota->where('sedang_dipinjam_count', '>', 0)->count() }}
        </p>
    </div>

    <div class="footer">
        <p>Dokumen ini dicetak secara otomatis oleh sistem Rumah Baca pada {{ $tanggalCetak }}</p>
    </div>
</body>
</html>
