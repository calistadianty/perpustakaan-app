<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\User;
use App\Models\Book;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PeminjamanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    /**
     * FUNGSI: Menampilkan Halaman Peminjaman Aktif.
     * Halaman ini memuat dua tabel (menggunakan dua paginasi berbeda):
     * 1. Peminjaman Aktif (Sedang dipinjam atau sedang diajukan/pending).
     * 2. Permintaan Pengembalian (Pembaca menekan tombol "Kembalikan Buku").
     * Terdapat fitur pencarian (*search*) berdasarkan nama user dan judul buku.
     */
    public function index(Request $request)
    {
        $baseQuery = Peminjaman::with(['user', 'book', 'petugas']);

        // Filter Pembaca
        if ($request->has('user_id') && $request->user_id != '') {
            $baseQuery->where('user_id', $request->user_id);
        }

        // Active: Pending, Dipinjam
        $activeQuery = (clone $baseQuery)->whereIn('status', ['pending', 'dipinjam']);
        if ($request->has('search_active') && $request->search_active != '') {
            $search = $request->search_active;
            $activeQuery->where(function($q) use ($search) {
                $q->whereHas('user', function($u) use ($search) {
                    $u->where('name', 'like', "%{$search}%");
                })->orWhereHas('book', function($b) use ($search) {
                    $b->where('judul', 'like', "%{$search}%");
                });
            });
        }
        $activePeminjaman = $activeQuery->orderBy('created_at', 'desc')
            ->paginate(10, ['*'], 'active_page');

        // Return Requests: Menunggu Pengembalian
        $returnQuery = (clone $baseQuery)->where('status', 'menunggu_pengembalian');
        if ($request->has('search_return') && $request->search_return != '') {
            $search = $request->search_return;
            $returnQuery->where(function($q) use ($search) {
                $q->whereHas('user', function($u) use ($search) {
                    $u->where('name', 'like', "%{$search}%");
                })->orWhereHas('book', function($b) use ($search) {
                    $b->where('judul', 'like', "%{$search}%");
                });
            });
        }
        $returnRequests = $returnQuery->orderBy('updated_at', 'desc')
            ->paginate(10, ['*'], 'return_page');

        $users = User::where('role', 'user')->get();

        $user = auth()->user();
        $view = ($user->role === 'petugas') ? 'petugas.peminjaman.index' : 'admin.peminjaman.index';
        return view($view, compact('activePeminjaman', 'users', 'returnRequests'));
    }

    /**
     * FUNGSI: Menampilkan Riwayat Peminjaman (History).
     * Memuat daftar transaksi yang sudah berstatus 'dikembalikan' atau 'ditolak'.
     * Sama seperti index(), fungsi ini juga menangani fitur pencarian.
     */
    public function history(Request $request)
    {
        $query = Peminjaman::with(['user', 'book', 'petugas']);

        // Search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($u) use ($search) {
                    $u->where('name', 'like', "%{$search}%");
                })->orWhereHas('book', function($b) use ($search) {
                    $b->where('judul', 'like', "%{$search}%");
                });
            });
        }

        // Filter Pembaca
        if ($request->has('user_id') && $request->user_id != '') {
            $query->where('user_id', $request->user_id);
        }

        // History: Dikembalikan, Ditolak
        $historyPeminjaman = $query->whereIn('status', ['dikembalikan', 'ditolak'])
            ->orderBy('updated_at', 'desc')
            ->paginate(10);

        $users = User::where('role', 'user')->get();

        $user = auth()->user();
        $view = ($user->role === 'petugas') ? 'petugas.peminjaman.history' : 'admin.peminjaman.history';
        return view($view, compact('historyPeminjaman', 'users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = auth()->user();
        $users = User::where('role', 'user')->get();
        $books = Book::where('stok', '>', 0)->approved()->get();

        $view = ($user->role === 'petugas') ? 'petugas.peminjaman.create' : 'admin.peminjaman.create';
        return view($view, compact('users', 'books'));
    }

    /**
     * FUNGSI: Menyimpan Input Peminjaman Manual oleh Petugas/Admin.
     * Memvalidasi input, lalu mengecek ketersediaan stok buku.
     * Karena yang menginput adalah petugas, maka statusnya langsung "dipinjam" 
     * (tanpa status *pending* persetujuan), dan stok buku di akhir akan dikurangi.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'book_id' => 'required|exists:books,id',
            'tanggal_pinjam' => 'required|date',
            'keterangan' => 'nullable|string',
        ]);

        // Cek stok buku
        $book = Book::findOrFail($request->book_id);
        if ($book->stok < 1) {
            return back()->with('error', 'Stok buku tidak tersedia!');
        }

        // Hitung deadline
        $tanggalPinjam = Carbon::parse($request->tanggal_pinjam);
        $deadline = $tanggalPinjam->copy()->addDays(7);

        // Buat peminjaman (Langsung Disetujui/Dipinjam kalau Admin/Petugas yang buat)
        Peminjaman::create([
            'user_id' => $request->user_id,
            'book_id' => $request->book_id,
            'petugas_id' => auth()->id(),
            'tanggal_pinjam' => $request->tanggal_pinjam,
            'tanggal_batas_kembali' => $deadline,
            'status' => 'dipinjam', // Admin directly creates active loan
            'keterangan' => $request->keterangan,
        ]);

        // Kurangi stok buku
        $book->decrement('stok');

        $user = auth()->user();
        $route = ($user->role === 'petugas') ? 'petugas.peminjaman.index' : 'admin.peminjaman.index';
        
        return redirect()->route($route)
            ->with('success', 'Data peminjaman berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Peminjaman $peminjaman)
    {
        $peminjaman->load(['user', 'book', 'petugas']);
        $user = auth()->user();
        
        $view = ($user->role === 'petugas') ? 'petugas.peminjaman.show' : 'admin.peminjaman.show';
        return view($view, compact('peminjaman'));
    }

    /**
     * Approve Peminjaman
     */
    public function approve(Peminjaman $peminjaman)
    {
        if ($peminjaman->status !== 'pending') {
            return back()->with('error', 'Peminjaman tidak dalam status pending.');
        }

        // Cek stok lagi just in case
        $book = $peminjaman->book;
        if ($book->stok < 1) {
            return back()->with('error', 'Stok buku habis, tidak bisa menyetujui.');
        }

        $peminjaman->update([
            'status' => 'dipinjam',
            'petugas_id' => auth()->id(),
        ]);

        // Kurangi stok buku
        $book->decrement('stok');

        return back()->with('success', 'Peminjaman disetujui dan buku langsung dipinjamkan! Stok dikurangi.');
    }

    /**
     * Reject Peminjaman
     */
    public function reject(Peminjaman $peminjaman)
    {
        if ($peminjaman->status !== 'pending') {
            return back()->with('error', 'Peminjaman tidak dalam status pending.');
        }

        $peminjaman->update([
            'status' => 'ditolak',
            'petugas_id' => auth()->id(),
        ]);

        return back()->with('success', 'Permintaan peminjaman ditolak.');
    }

    /**
     * Update Status (Pengembalian)
     */
    public function update(Request $request, Peminjaman $peminjaman)
    {
        $request->validate([
            'status' => 'required|in:dikembalikan'
        ]);

        // Dari Dipinjam -> Dikembalikan
        if ($peminjaman->status !== 'dipinjam') {
            return back()->with('error', 'Hanya buku yang sedang dipinjam yang bisa dikembalikan.');
        }

        $peminjaman->update([
            'status' => 'dikembalikan',
            'tanggal_kembali' => now(),
        ]);

        // Kembalikan stok
        $peminjaman->book->increment('stok');

        return back()->with('success', 'Buku telah dikembalikan! Stok bertambah.');
    }

    /**
     * FUNGSI: Menyetujui Permintaan Pengembalian Buku.
     * Dipanggil ketika Petugas menekan tombol setuju (Approve) saat pembaca mengajukan pengembalian.
     * Mengubah status menjadi 'dikembalikan' dan MENGEMBALIKAN/MENAMBAH angka di stok buku (+1).
     */
    public function approveKembali(Peminjaman $peminjaman)
    {
        if ($peminjaman->status !== 'menunggu_pengembalian') {
            return back()->with('error', 'Status peminjaman bukan pengajuan pengembalian.');
        }

        $peminjaman->update([
            'status' => 'dikembalikan',
            'tanggal_kembali' => now(),
        ]);

        // Kembalikan stok buku
        $peminjaman->book->increment('stok');

        return back()->with('success', 'Pengembalian buku disetujui! Status menjadi Sudah Dikembalikan.');
    }

    /**
     * Tolak Pengembalian Buku
     */
    public function rejectKembali(Peminjaman $peminjaman)
    {
        if ($peminjaman->status !== 'menunggu_pengembalian') {
            return back()->with('error', 'Status peminjaman bukan pengajuan pengembalian.');
        }

        $peminjaman->update([
            'status' => 'dipinjam',
        ]);

        return back()->with('success', 'Pengembalian buku ditolak, user harus mengajukan kembali.');
    }

    public function destroy(Peminjaman $peminjaman)
    {
         // Optional: if needed to delete records
         // If status was active/approved, we might need to restore stock logic here too if deleted recklessly.
         // For now, assume delete is soft or restricted to completed items.
         $peminjaman->delete();
         return back()->with('success', 'Data peminjaman dihapus.');
    }
}