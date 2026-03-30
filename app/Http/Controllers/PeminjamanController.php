<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PeminjamanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    /**
     * Display a listing of the resource (Active Loans).
     */
    /**
     * FUNGSI: Menampilkan Daftar Pinjaman Berjalan Milik Pembaca.
     * Mengambil tabel Peminjaman khusus status hidup (pending, dipinjam, menunggu_pengembalian)
     * untuk dirender di tabel halaman dasbor pembaca.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Peminjaman Aktif (Pending, Dipinjam, Menunggu Pengembalian)
        $activePeminjaman = Peminjaman::with('book')
            ->where('user_id', $user->id)
            ->whereIn('status', ['pending', 'dipinjam', 'menunggu_pengembalian'])
            ->latest()
            ->paginate(10);
            
        return view('user.peminjaman.index', compact('activePeminjaman'));
    }

    /**
     * Display a listing of the history.
     */
    public function history()
    {
        $user = Auth::user();

        // Riwayat (Ditolak, Dikembalikan)
        $historyPeminjaman = Peminjaman::with('book')
            ->where('user_id', $user->id)
            ->whereIn('status', ['ditolak', 'dikembalikan'])
            ->latest()
            ->paginate(10);

        return view('user.peminjaman.history', compact('historyPeminjaman'));
    }

    /**
     * Store a newly created resource in storage.
     */
    /**
     * FUNGSI: Aturan Logika Pengajuan Pinjaman Paling Krusial.
     * Memiliki 3 lapis pelindung bisnis logika (Business Rules):
     * 1. Cek Keterlambatan: Pembaca dengan buku telat dilarang meminjam lagi.
     * 2. Maksimal Pinjaman: Tidak boleh melebihi 2 buku yang aktif.
     * 3. Cegah Ganda: Tidak boleh mengajukan buku yang persis sama dua kali jika masih meminjamnya.
     */
    public function store(Request $request)
    {
        // Jika request dari tombol "Pinjam Buku Ini", book_id mungkin tidak ada di body, tapi di route atau hidden input
        // Kita validasi 'book_id'
        
        $request->validate([
            'book_id' => 'required|exists:books,id',
            'alamat' => 'required|string|max:255',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $user = Auth::user();
        
        // Update alamat user jika berubah/belum ada
        if ($user->alamat !== $request->alamat) {
            $user->update(['alamat' => $request->alamat]);
        }

        $book = Book::findOrFail($request->book_id);

        // Cek stok
        if ($book->stok < 1) {
            return back()->with('error', 'Maaf, stok buku ini sedang habis.');
        }

        // 1. Cek Keterlambatan (PRIORITAS UTAMA)
        // Jika ada buku yang telat, user tidak boleh minjam apa-apa, berapapun jumlahnya.
        $hasOverdue = Peminjaman::where('user_id', Auth::id())
            ->where('status', 'dipinjam')
            ->whereDate('tanggal_batas_kembali', '<', now()->toDateString())
            ->exists();

        if ($hasOverdue) {
            return back()->with('error_overdue', 'Akun Anda dibekukan sementara karena ada buku yang belum dikembalikan melewati batas waktu. Silakan kembalikan segera.');
        }

        // 2. Cek Limit Jumlah (PRIORITAS KEDUA)
        $activeCount = Peminjaman::where('user_id', Auth::id())
            ->whereIn('status', ['pending', 'dipinjam', 'menunggu_pengembalian'])
            ->count();

        if ($activeCount >= 2) {
            return back()->with('error_limit', 'Kuota peminjaman penuh! Anda sedang meminjam 2 buku (maksimal). Selesaikan peminjaman sebelumnya untuk meminjam buku baru.');
        }

        // Cek apakah user sedang meminjam buku yang sama dan statusnya masih aktif
        $existingLoan = Peminjaman::where('user_id', Auth::id())
            ->where('book_id', $book->id)
            ->whereIn('status', ['pending', 'dipinjam', 'menunggu_pengembalian'])
            ->exists();

        if ($existingLoan) {
            return back()->with('error', 'Anda sudah mengajukan atau sedang meminjam buku ini.');
        }

        // Tanggal Pinjam default hari ini jika tidak diisi
        $tanggalPinjam = $request->filled('tanggal_pinjam') 
            ? \Carbon\Carbon::parse($request->tanggal_pinjam) 
            : now();
            
        // Validasi Tanggal Kembali
        $request->validate([
            'tanggal_batas_kembali' => [
                'required',
                'date',
                'after_or_equal:tanggal_pinjam',
                'before_or_equal:' . $tanggalPinjam->copy()->addDays(14)->toDateString(), // Max 2 minggu
            ],
            'keterangan' => 'nullable|string|max:255',
        ], [
            'tanggal_batas_kembali.required' => 'Tanggal rencana pengembalian wajib diisi.',
            'tanggal_batas_kembali.after_or_equal' => 'Tanggal kembali tidak boleh sebelum tanggal pinjam.',
            'tanggal_batas_kembali.before_or_equal' => 'Maksimal peminjaman adalah 14 hari (2 minggu).',
        ]);

        $tanggalBatas = \Carbon\Carbon::parse($request->tanggal_batas_kembali);

        Peminjaman::create([
            'user_id' => Auth::id(),
            'book_id' => $book->id,
            'tanggal_pinjam' => $tanggalPinjam,
            'tanggal_batas_kembali' => $tanggalBatas,
            'status' => 'pending', 
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('peminjaman.index')
            ->with('success', 'Permintaan peminjaman berhasil diajukan! Menunggu persetujuan petugas.');
    }
    /**
     * Ajukan Pengembalian Buku
     */
    /**
     * FUNGSI: Pengajuan Pengembalian oleh Pembaca.
     * Saat pembaca ingin memulangkan buku, ia hanya mengubah izin dari 'dipinjam' 
     * menjadi 'menunggu_pengembalian'. Admin/Petugas nantinya yang harus klik ACC (persetujuan) akhir.
     */
    public function ajukanKembali(Peminjaman $peminjaman)
    {
        if ($peminjaman->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        if ($peminjaman->status !== 'dipinjam') {
            return back()->with('error', 'Hanya buku yang sedang dipinjam yang dapat diajukan pengembalian.');
        }

        $peminjaman->update([
            'status' => 'menunggu_pengembalian',
        ]);

        return back()->with('success', 'Pengajuan pengembalian berhasil dikirim Admin/Petugas. Menunggu konfirmasi.');
    }

    /**
     * Export PDF Receipt for a specific Peminjaman
     */
    public function exportReceipt(Peminjaman $peminjaman)
    {
        // Ensure the user owns this peminjaman
        if ($peminjaman->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        // Only allow export for active or completed loans
        if (!in_array($peminjaman->status, ['dipinjam', 'menunggu_pengembalian', 'dikembalikan'])) {
            abort(400, 'Bukti peminjaman tidak tersedia untuk status ini.');
        }

        $peminjaman->load(['book', 'user', 'petugas']);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('user.peminjaman.receipt-pdf', compact('peminjaman'))
                    ->setPaper('A5', 'portrait'); // A5 Portrait for thermal receipt style
        
        return $pdf->stream('Bukti_Peminjaman_' . $peminjaman->id . '.pdf');
    }
}
