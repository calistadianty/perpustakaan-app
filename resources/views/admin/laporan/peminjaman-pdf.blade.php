<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Peminjaman</title>
    <style>
        /* PENJELASAN DOMPDF CSS:
           1. Jangan gunakan Tailwind (ex: class="mt-4 text-red-500") di fle ini.
           2. DomPDF adalah library PHP tua; ia hanya bisa membaca CSS murni versi dasar.
           3. Selalu gunakan margin, padding dengan satuan tetap seperti 'px' agar posisi PDF tidak meleset saat dicetak.
        */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 10px; color: #1a1a1a; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 3px solid #1e40af; padding-bottom: 15px; }
        .header h1 { font-size: 20px; color: #1e40af; letter-spacing: 2px; }
        .header p { font-size: 10px; color: #666; margin-top: 4px; }
        .meta { margin-bottom: 15px; font-size: 10px; }
        .meta table { width: 100%; }
        .meta td { padding: 2px 0; }
        .meta .label { color: #666; width: 120px; }
        .meta .value { font-weight: bold; }
        table.data { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table.data th { background: #1e40af; color: white; padding: 7px 5px; text-align: left; font-size: 9px; text-transform: uppercase; letter-spacing: 0.5px; }
        table.data td { padding: 6px 5px; border-bottom: 1px solid #e5e7eb; font-size: 9px; }
        table.data tr:nth-child(even) { background: #f8fafc; }
        table.data tr.overdue { background: #fff7f7; }
        .status { padding: 2px 6px; border-radius: 10px; font-size: 8px; font-weight: bold; text-transform: uppercase; }
        .status-pending { background: #fef3c7; color: #92400e; }
        .status-menunggu_pengembalian { background: #ffedd5; color: #9a3412; }
        .status-dipinjam { background: #e0e7ff; color: #3730a3; }
        .status-dikembalikan { background: #d1fae5; color: #065f46; }
        .status-ditolak { background: #fee2e2; color: #991b1b; }
        .status-terlambat { background: #fee2e2; color: #991b1b; }
        .late-days { color: #dc2626; font-weight: bold; }
        .footer { margin-top: 20px; text-align: center; font-size: 9px; color: #999; border-top: 1px solid #e5e7eb; padding-top: 10px; }
        .summary { margin-top: 15px; background: #f8fafc; padding: 10px; border-radius: 4px; border: 1px solid #e5e7eb; }
        .summary p { font-size: 10px; margin: 3px 0; }
    </style>
</head>
<body>
    <div class="header">
        <h1>RUMAH BACA</h1>
        <p>Laporan Data Peminjaman Buku</p>
    </div>

    <div class="meta">
        <table>
            <tr>
                <td class="label">Tanggal Cetak</td>
                <td class="value">: {{ $tanggalCetak }}</td>
            </tr>
            <tr>
                <td class="label">Periode</td>
                <td class="value">: {{ $filterDari }} s/d {{ $filterSampai }}</td>
            </tr>
            <tr>
                <td class="label">Filter Status</td>
                <td class="value">: {{ ucfirst($filterStatus) }}</td>
            </tr>
            <tr>
                <td class="label">Total Data</td>
                <td class="value">: {{ $peminjaman->count() }} transaksi</td>
            </tr>
        </table>
    </div>

    <table class="data">
        <thead>
            <tr>
                <th style="width:25px">No</th>
                <th>Peminjam</th>
                <th>Judul Buku</th>
                <th>Tgl Pinjam</th>
                <th>Batas Kembali</th>
                <th>Tgl Kembali</th>
                <th>Status</th>
                <th style="text-align:center">Terlambat</th>
            </tr>
        </thead>
        <tbody>
            {{-- PENJELASAN LOGIKA BLADE (@php):
                 Di file Blade, kita diizinkan menyisipkan PHP murni di antara @php dan @endphp.
                 Logika di bawah ini secara otomatis menghitung selisih hari jika pembaca telat mengembalikan buku.
            --}}
            @forelse($peminjaman as $i => $item)
            @php
                $isOverdue = $item->status == 'dipinjam' && $item->tanggal_batas_kembali && $item->tanggal_batas_kembali->isPast();
                $hariTerlambat = $isOverdue ? now()->diffInDays($item->tanggal_batas_kembali) : 0;
            @endphp
            <tr class="{{ $isOverdue ? 'overdue' : '' }}">
                <td>{{ $i + 1 }}</td>
                <td>{{ $item->user->name ?? '-' }}</td>
                <td>{{ $item->book->judul ?? '-' }}</td>
                <td>{{ $item->tanggal_pinjam->format('d/m/Y') }}</td>
                <td class="{{ $isOverdue ? 'late-days' : '' }}">{{ $item->tanggal_batas_kembali ? $item->tanggal_batas_kembali->format('d/m/Y') : '-' }}</td>
                <td>{{ $item->tanggal_kembali ? $item->tanggal_kembali->format('d/m/Y') : '-' }}</td>
                <td>
                    @if($isOverdue)
                        <span class="status status-terlambat">Terlambat</span>
                    @else
                        <span class="status status-{{ $item->status }}">{{ ucfirst($item->status) }}</span>
                    @endif
                </td>
                <td style="text-align:center" class="{{ $hariTerlambat > 0 ? 'late-days' : '' }}">
                    {{ $hariTerlambat > 0 ? $hariTerlambat . ' hr' : '-' }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align: center; padding: 20px; color: #999;">Tidak ada data peminjaman.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="summary">
        <p><strong>Ringkasan:</strong></p>
        <p>Pending: {{ $peminjaman->where('status', 'pending')->count() }} | Konfirmasi: {{ $peminjaman->where('status', 'menunggu_pengembalian')->count() }} | Dipinjam: {{ $peminjaman->where('status', 'dipinjam')->count() }} | Dikembalikan: {{ $peminjaman->where('status', 'dikembalikan')->count() }} | Ditolak: {{ $peminjaman->where('status', 'ditolak')->count() }}</p>
    </div>

    <div class="footer">
        <p>Dokumen ini dicetak secara otomatis oleh sistem Rumah Baca pada {{ $tanggalCetak }}</p>
    </div>
</body>
</html>
