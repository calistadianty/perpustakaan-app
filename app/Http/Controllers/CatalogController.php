<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Review;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CatalogController extends Controller
{
    /**
     * FUNGSI: Menampilkan Halaman Utama Katalog Buku.
     * Mengambil fitur 'when()' milik Laravel Eloquent: Jika *search* atau *category* diisi oleh pengguna,
     * barulah perintah 'where' dijalankan ke database. Jika kosong, tampilkan semua secara bawaan.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $category = $request->input('category');

        $books = Book::with('categories')
                    ->approved()
                    ->when($search, function($query, $search) {
                        return $query->where('judul', 'like', "%{$search}%")
                                     ->orWhere('penulis', 'like', "%{$search}%");
                    })
                    ->when($category, function($query, $category) {
                        return $query->whereHas('categories', function($q) use ($category) {
                            $q->where('nama', $category);
                        });
                    })
                    ->latest()
                    ->paginate(12);

        return view('user.catalog.index', compact('books', 'search', 'category'));
    }

    /**
     * FUNGSI: Menampilkan Detail Spesifik Satu Buku.
     * Menggunakan Route Model Binding (otomatis mencari buku berdasarkan parameter URL).
     * Juga mengecek status peminjaman dari 'user_id' untuk menentukan apakah ia berhak memberikan 'Review' atau tidak.
     */
    public function show(Book $book)
    {
        $book->load(['categories', 'reviews.user']);
        
        // Count rating average
        $averageRating = $book->reviews()->avg('rating') ?? 0;
        $totalReviews = $book->reviews()->count();

        // Check if user has already reviewed
        $userHasReviewed = false;
        $userCanReview = false;
        if(auth()->check()){
            $userHasReviewed = $book->reviews()->where('user_id', auth()->id())->exists();
            $userCanReview = \App\Models\Peminjaman::where('user_id', auth()->id())
                ->where('book_id', $book->id)
                ->where('status', 'dikembalikan')
                ->exists();
        }

        // Related books (share at least one category)
        $relatedBooks = Book::whereHas('categories', function($q) use ($book) {
                                $q->whereIn('categories.id', $book->categories->pluck('id'));
                            })
                            ->where('id', '!=', $book->id)
                            ->approved()
                            ->take(4)
                            ->get();

        return view('user.catalog.show', compact('book', 'averageRating', 'totalReviews', 'userHasReviewed', 'userCanReview', 'relatedBooks'));
    }
    /**
     * FUNGSI: Menyimpan Ulasan (Review) Bintang dari Pembaca.
     * Lapisan keamanan ganda ditegakkan di sini:
     * 1. Pembaca tidak boleh *spam* 2 ulasan di buku yang sama.
     * 2. Pembaca tidak boleh mengulas buku yang belum ia pinjam & kembalikan (validasi status 'dikembalikan').
     */
    public function storeReview(Request $request, Book $book)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string'
        ]);

        // Prevent duplicate reviews
        if($book->reviews()->where('user_id', auth()->id())->exists()) {
            return back()->with('error', 'Anda sudah mereview buku ini.');
        }

        // Prevent review if hasn't borrowed and returned
        $hasValidLoan = \App\Models\Peminjaman::where('user_id', auth()->id())
            ->where('book_id', $book->id)
            ->where('status', 'dikembalikan')
            ->exists();
            
        if (!$hasValidLoan) {
            return back()->with('error', 'Anda harus meminjam dan mengembalikan buku ini terlebih dahulu sebelum memberikan ulasan.');
        }

        $book->reviews()->create([
            'user_id' => auth()->id(),
            'rating' => $request->rating,
            'comment' => $request->comment
        ]);

        return back()->with('success', 'Terima kasih atas ulasan Anda!');
    }

    public function updateReview(Request $request, Review $review)
    {
        // Ensure user owns the review
        if ($review->user_id !== Auth::id()) {
            return back()->with('error', 'Unauthorized action.');
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string'
        ]);

        $review->update([
            'rating' => $request->rating,
            'comment' => $request->comment
        ]);

        return back()->with('success', 'Ulasan berhasil diperbarui!');
    }

    public function destroyReview(Review $review)
    {
        // Ensure user owns the review
        if ($review->user_id !== Auth::id()) {
            return back()->with('error', 'Unauthorized action.');
        }

        $review->delete();

        return back()->with('success', 'Ulasan berhasil dihapus!');
    }
}
