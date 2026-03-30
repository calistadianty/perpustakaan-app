<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Peminjaman;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    /**
     * FUNGSI: Menampilkan Halaman Dasbor/Laporan Utama.
     * Logika ini menghitung total data menggunakan fungsi bawaan database Laravel seperti 'count()'.
     * 'whereDate' dan 'whereNotNull' digunakan untuk menyaring pangkalan database berdasarkan kriteria tanggal.
     */
    public function index(Request $request)
    {
        $totalBuku       = Book::count();
        $totalAnggota    = User::where('role', 'user')->count();
        $totalPeminjaman = Peminjaman::count();
        $sedangDipinjam  = Peminjaman::where('status', 'dipinjam')->count();
        $dikembalikan    = Peminjaman::where('status', 'dikembalikan')->count();
        $terlambat       = Peminjaman::where('status', 'dipinjam')
            ->whereNotNull('tanggal_batas_kembali')
            ->where('tanggal_batas_kembali', '<', now())
            ->count();

        // Jika ada request generate, ambil data peminjaman
        $peminjaman = null;
        $filtered   = false;

        if ($request->has('generate')) {
            $filtered = true;
            $query    = Peminjaman::with(['user', 'book']);

            if ($request->filled('dari')) {
                $query->whereDate('tanggal_pinjam', '>=', $request->dari);
            }
            if ($request->filled('sampai')) {
                $query->whereDate('tanggal_pinjam', '<=', $request->sampai);
            }
            if ($request->filled('status') && $request->status !== 'semua') {
                $query->where('status', $request->status);
            }

            $peminjaman = $query->latest('tanggal_pinjam')->get();
        }

        // Tentukan view berdasarkan prefix route (admin atau petugas)
        $prefix = request()->routeIs('petugas.*') ? 'petugas' : 'admin';

        return view("{$prefix}.laporan.index", compact(
            'totalBuku', 'totalAnggota', 'totalPeminjaman',
            'sedangDipinjam', 'terlambat', 'dikembalikan',
            'peminjaman', 'filtered'
        ));
    }

    /**
     * FUNGSI: Meng-ekspor (Download) Laporan Peminjaman ke PDF.
     * Mengambil data dari tabel, lalu menjejal HTML ke dalam generator 'Barryvdh\DomPDF'.
     * Fungsi stream() akan membuka PDF langsung di tab browser pengguna.
     */
    public function exportPeminjaman(Request $request)
    {
        $query = Peminjaman::with(['user', 'book']);

        if ($request->filled('dari')) {
            $query->whereDate('tanggal_pinjam', '>=', $request->dari);
        }
        if ($request->filled('sampai')) {
            $query->whereDate('tanggal_pinjam', '<=', $request->sampai);
        }
        if ($request->filled('status') && $request->status !== 'semua') {
            $query->where('status', $request->status);
        }

        $peminjaman    = $query->latest('tanggal_pinjam')->get();
        $tanggalCetak  = Carbon::now()->format('d M Y, H:i');
        $filterDari    = $request->dari    ? Carbon::parse($request->dari)->format('d M Y')    : '-';
        $filterSampai  = $request->sampai  ? Carbon::parse($request->sampai)->format('d M Y')  : '-';
        $filterStatus  = $request->status ?? 'semua';

        $pdf = Pdf::loadView('admin.laporan.peminjaman-pdf', compact(
            'peminjaman', 'tanggalCetak', 'filterDari', 'filterSampai', 'filterStatus'
        ))->setPaper('a4', 'landscape');

        return $pdf->stream('Laporan_Peminjaman_' . now()->format('Ymd_His') . '.pdf');
    }

    /**
     * FUNGSI: Laporan Anggota Teraktif ke PDF.
     * Menggunakan 'withCount' ganda: satu untuk menghitung semua riwayat, 
     * dan satu lagi (dengan sub-query) khusus menghitung buku yang saat ini statusnya sedang 'dipinjam'.
     */
    public function exportAnggota(Request $request)
    {
        $anggota = User::where('role', 'user')
            ->withCount('peminjamans')
            ->withCount(['peminjamans as sedang_dipinjam_count' => function ($q) {
                $q->where('status', 'dipinjam');
            }])
            ->oldest()
            ->get();

        $tanggalCetak = Carbon::now()->format('d M Y, H:i');

        $pdf = Pdf::loadView('admin.laporan.anggota-pdf', compact('anggota', 'tanggalCetak'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream('Laporan_Anggota_' . now()->format('Ymd_His') . '.pdf');
    }

    /**
     * FUNGSI: Laporan Buku Terpopuler.
     * Mirip seperti anggota, tetapi ini mengurutkan Buku berdasarkan jumlah 
     * transaksi peminjamannya ('peminjamans_count') terbanyak (orderByDesc), diambil 50 terbesar (take 50).
     */
    public function exportBukuPopuler(Request $request)
    {
        $buku = Book::withCount('peminjamans')
            ->orderByDesc('peminjamans_count')
            ->take(50)
            ->get();

        $tanggalCetak = Carbon::now()->format('d M Y, H:i');

        $pdf = Pdf::loadView('admin.laporan.buku-populer-pdf', compact('buku', 'tanggalCetak'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream('Laporan_Buku_Populer_' . now()->format('Ymd_His') . '.pdf');
    }

    public function exportKeterlambatan(Request $request)
    {
        $keterlambatan = Peminjaman::with(['user', 'book'])
            ->where('status', 'dipinjam')
            ->whereNotNull('tanggal_batas_kembali')
            ->where('tanggal_batas_kembali', '<', now())
            ->oldest('tanggal_batas_kembali')
            ->get();

        $tanggalCetak = Carbon::now()->format('d M Y, H:i');

        $pdf = Pdf::loadView('admin.laporan.keterlambatan-pdf', compact('keterlambatan', 'tanggalCetak'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream('Laporan_Keterlambatan_' . now()->format('Ymd_His') . '.pdf');
    }
}
