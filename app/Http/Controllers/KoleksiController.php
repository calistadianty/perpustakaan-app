<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Koleksi;
use App\Models\Book;
use Illuminate\Support\Facades\Auth;

class KoleksiController extends Controller
{
    /**
     * FUNGSI: Menampilkan Halaman Daftar Simpanan (Koleksi / Wishlist).
     * Filter ketat: `where('user_id', auth()->id())` memastikan pembaca 
     * HANYA BISA melihat daftar koleksinya sendiri, bukan milik orang lain.
     */
    public function index()
    {
        $koleksi = Koleksi::where('user_id', auth()->id())->latest()->get();
        return view('user.koleksi.index', compact('koleksi'));
    }

    /**
     * FUNGSI: Fitur 'Simpan' / 'Hapus Simpanan' (Toggle Bookmark).
     * Pengecekan cerdas: Jika buku sudah dikoleksi, maka hapus koleksi tersebut (Un-save).
     * Jika belum, bangun rekam jejak koleksi baru.
     * Mengembalikan 'json' (AJAX) jika diminta oleh JavaScript untuk efek tombol yang mulus.
     */
    public function store(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
        ]);

        $existing = Koleksi::where('user_id', auth()->id())
            ->where('book_id', $request->book_id)
            ->first();

        if ($existing) {
            $existing->delete();
            if ($request->wantsJson()) {
                return response()->json(['status' => 'removed', 'message' => 'Buku dihapus dari koleksi.']);
            }
            return back()->with('success', 'Buku dihapus dari koleksi.');
        } else {
            Koleksi::create([
                'user_id' => auth()->id(),
                'book_id' => $request->book_id,
            ]);
            if ($request->wantsJson()) {
                return response()->json(['status' => 'added', 'message' => 'Buku ditambahkan ke koleksi.']);
            }
            return back()->with('success', 'Buku ditambahkan ke koleksi.');
        }
    }

    public function destroy($id)
    {
        $koleksi = Koleksi::where('user_id', auth()->id())->findOrFail($id);
        $koleksi->delete();
        return back()->with('success', 'Buku dihapus dari koleksi.');
    }
}
