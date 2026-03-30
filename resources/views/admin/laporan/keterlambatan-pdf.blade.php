<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Keterlambatan</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; color: #1a1a1a; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 3px solid #dc2626; padding-bottom: 15px; }
        .header h1 { font-size: 20px; color: #dc2626; letter-spacing: 2px; }
        .header h2 { font-size: 14px; color: #374151; margin-top: 4px; }
        .header p { font-size: 10px; color: #666; margin-top: 4px; }
        .meta { margin-bottom: 15px; font-size: 10px; }
        .meta table { width: 100%; }
        .meta td { padding: 2px 0; }
        .meta .label { color: #666; width: 130px; }
        .meta .value { font-weight: bold; }
        table.data { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table.data th { background: #dc2626; color: white; padding: 8px 6px; text-align: left; font-size: 10px; text-transform: uppercase; letter-spacing: 0.5px; }
        table.data td { padding: 7px 6px; border-bottom: 1px solid #e5e7eb; font-size: 10px; }
        table.data tr:nth-child(even) { background: #fff7f7; }
        .late-days { font-weight: bold; color: #dc2626; }
        .late-badge { padding: 2px 8px; border-radius: 10px; font-size: 9px; font-weight: bold; background: #fee2e2; color: #991b1b; }
        .summary { margin-top: 15px; background: #fff7f7; padding: 10px; border-radius: 4px; border: 1px solid #fecaca; }
        .summary p { font-size: 10px; margin: 3px 0; color: #7f1d1d; }
        .footer { margin-top: 20px; text-align: center; font-size: 9px; color: #999; border-top: 1px solid #e5e7eb; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>RUMAH BACA</h1>
        <h2>Laporan Peminjaman Terlambat Dikembalikan</h2>
        <p>Dicetak pada: {{ $tanggalCetak }}</p>
    </div>

    <div class="meta">
        <table>
            <tr>
                <td class="label">Tanggal Cetak</td>
                <td class="value">: {{ $tanggalCetak }}</td>
            </tr>
            <tr>
                <td class="label">Total Terlambat</td>
                <td class="value">: {{ $keterlambatan->count() }} transaksi</td>
            </tr>
        </table>
    </div>

    <table class="data">
        <thead>
            <tr>
                <th style="width:30px">No</th>
                <th>Nama Peminjam</th>
                <th>Judul Buku</th>
                <th>Tgl Pinjam</th>
                <th>Batas Kembali</th>
                <th style="text-align:center">Hari Terlambat</th>
            </tr>
        </thead>
        <tbody>
            @forelse($keterlambatan as $i => $item)
            @php
                $hariTerlambat = now()->diffInDays($item->tanggal_batas_kembali);
            @endphp
            <tr>
                <td>{{ $i + 1 }}</td>
                <td><strong>{{ $item->user->name ?? '-' }}</strong><br><span style="color:#666; font-size:9px">{{ $item->user->email ?? '' }}</span></td>
                <td>{{ $item->book->judul ?? '-' }}</td>
                <td>{{ $item->tanggal_pinjam->format('d/m/Y') }}</td>
                <td style="color:#dc2626; font-weight:bold">{{ $item->tanggal_batas_kembali->format('d/m/Y') }}</td>
                <td style="text-align:center">
                    <span class="late-badge">{{ $hariTerlambat }} hari</span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align:center; padding:20px; color:#999;">Tidak ada data keterlambatan.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="summary">
        <p><strong>⚠ Ringkasan Keterlambatan:</strong></p>
        <p>Total peminjaman terlambat: <strong>{{ $keterlambatan->count() }}</strong> transaksi</p>
        @if($keterlambatan->count() > 0)
        @php
            $maxLate = $keterlambatan->map(fn($i) => now()->diffInDays($i->tanggal_batas_kembali))->max();
        @endphp
        <p>Keterlambatan terlama: <strong>{{ $maxLate }} hari</strong></p>
        @endif
    </div>

    <div class="footer">
        <p>Dokumen ini dicetak secara otomatis oleh sistem Rumah Baca pada {{ $tanggalCetak }}</p>
    </div>
</body>
</html>
