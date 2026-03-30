<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Buku Terpopuler</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; color: #1a1a1a; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 3px solid #7c3aed; padding-bottom: 15px; }
        .header h1 { font-size: 20px; color: #7c3aed; letter-spacing: 2px; }
        .header h2 { font-size: 14px; color: #374151; margin-top: 4px; }
        .header p { font-size: 10px; color: #666; margin-top: 4px; }
        .meta { margin-bottom: 15px; font-size: 10px; }
        .meta table { width: 100%; }
        .meta td { padding: 2px 0; }
        .meta .label { color: #666; width: 130px; }
        .meta .value { font-weight: bold; }
        table.data { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table.data th { background: #7c3aed; color: white; padding: 8px 6px; text-align: left; font-size: 10px; text-transform: uppercase; letter-spacing: 0.5px; }
        table.data td { padding: 7px 6px; border-bottom: 1px solid #e5e7eb; font-size: 10px; }
        table.data tr:nth-child(even) { background: #f8fafc; }
        .rank { font-weight: bold; font-size: 12px; text-align: center; }
        .rank-1 { color: #d97706; }
        .rank-2 { color: #6b7280; }
        .rank-3 { color: #b45309; }
        .rank-other { color: #374151; }
        .footer { margin-top: 20px; text-align: center; font-size: 9px; color: #999; border-top: 1px solid #e5e7eb; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>RUMAH BACA</h1>
        <h2>Laporan Buku Paling Sering Dipinjam</h2>
        <p>Dicetak pada: {{ $tanggalCetak }}</p>
    </div>

    <div class="meta">
        <table>
            <tr>
                <td class="label">Tanggal Cetak</td>
                <td class="value">: {{ $tanggalCetak }}</td>
            </tr>
            <tr>
                <td class="label">Total Buku Ditampilkan</td>
                <td class="value">: {{ $buku->count() }} judul</td>
            </tr>
        </table>
    </div>

    <table class="data">
        <thead>
            <tr>
                <th style="width:40px; text-align:center">Rank</th>
                <th>Judul Buku</th>
                <th>Penulis</th>
                <th>Penerbit</th>
                <th style="text-align:center">Stok</th>
                <th style="text-align:center">Total Dipinjam</th>
            </tr>
        </thead>
        <tbody>
            @forelse($buku as $i => $b)
            <tr>
                <td class="rank {{ $i === 0 ? 'rank-1' : ($i === 1 ? 'rank-2' : ($i === 2 ? 'rank-3' : 'rank-other')) }}">
                    #{{ $i + 1 }}
                </td>
                <td><strong>{{ $b->judul }}</strong></td>
                <td>{{ $b->penulis }}</td>
                <td>{{ $b->penerbit ?? '-' }}</td>
                <td style="text-align:center">{{ $b->stok }}</td>
                <td style="text-align:center"><strong>{{ $b->peminjamans_count }}x</strong></td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align:center; padding:20px; color:#999;">Tidak ada data buku.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Dokumen ini dicetak secara otomatis oleh sistem Rumah Baca pada {{ $tanggalCetak }}</p>
    </div>
</body>
</html>
