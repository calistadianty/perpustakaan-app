<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class BookController extends Controller
{
    /**
     * FUNGSI: Menampilkan Halaman Daftar Buku (Read).
     * Jika yang login adalah Petugas, ia melihat semua buku 'approved' + buku yang ia input sendiri.
     * Jika Admin, ia melihat semua buku yang statusnya 'approved'.
     * Data di-paginate (dibagi) menjadi 10 item per halaman.
     */
    public function index()
    {
        $user = auth()->user();
        
        if ($user->role === 'petugas') {
            // Petugas lihat semua buku approved + buku mereka sendiri
            $books = Book::with('categories')
                ->where('status', 'approved')
                ->orWhere('submitted_by', $user->id)
                ->latest()
                ->distinct()
                ->paginate(10);
            return view('petugas.books.index', compact('books'));
        }
        
        // Admin lihat semua buku yang approved
        $books = Book::with('categories')
            ->approved()
            ->latest()
            ->paginate(10);
        return view('admin.books.index', compact('books'));
    }

    public function create()
    {
        $categories = \App\Models\Category::all();
        $user = auth()->user();
        
        $view = ($user->role === 'petugas') ? 'petugas.books.create' : 'admin.books.create';
        return view($view, compact('categories'));
    }

    /**
     * FUNGSI: Menyimpan Buku Baru ke Database (Create).
     * 1. Validasi input dari form (judul wajib diisi, cover harus gambar, dsb).
     * 2. Proses *upload* file cover jika ada, simpannya di folder 'public/covers'.
     * 3. Ambil data *user* yang login, jadikan dia sebagai 'submitted_by' dan otomatis 'approved_by'.
     * 4. Pisahkan 'category_id' karena penyimpanannya berbeda (masuk ke tabel relasi/pivot).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'penulis' => 'required|string|max:255',
            'penerbit' => 'nullable|string|max:255',
            'tahun_terbit' => 'nullable|integer|min:1900|max:' . date('Y'),
            'stok' => 'required|integer|min:0',
            'category_id' => 'nullable|array',
            'category_id.*' => 'exists:categories,id',
            'cover' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'deskripsi' => 'nullable|string',
        ]);

        if ($request->hasFile('cover')) {
            $validated['cover'] = $request->file('cover')->store('covers', 'public');
        }

        $user = auth()->user();
        
        // Semua role (Admin & Petugas) langsung approved
        $validated['status'] = 'approved';
        $validated['submitted_by'] = $user->id;
        $validated['approved_by'] = $user->id; // Auto approve by self
        $validated['approved_at'] = now();
        
        // Remove category_id from validated data as it's not in the books table anymore
        $categoryIds = $validated['category_id'] ?? [];
        unset($validated['category_id']);
        
        $book = Book::create($validated);
        
        // Attach categories
        if (!empty($categoryIds)) {
            $book->categories()->attach($categoryIds);
        }
        
        $redirectRoute = ($user->role === 'petugas') ? 'petugas.books.index' : 'admin.books.index';

        return redirect()->route($redirectRoute)
            ->with('success', 'Buku berhasil ditambahkan dan langsung aktif!');
    }

    public function show(Book $book)
    {
        $book->load(['categories', 'reviews.user']);
        $averageRating = $book->reviews()->avg('rating') ?? 0;
        $totalReviews = $book->reviews()->count();
        $user = auth()->user();
        
        $view = ($user->role === 'petugas') ? 'petugas.books.show' : 'admin.books.show';
        return view($view, compact('book', 'averageRating', 'totalReviews'));
    }


    public function edit(Book $book)
    {
        $categories = \App\Models\Category::all();
        $user = auth()->user();
        
        $view = ($user->role === 'petugas') ? 'petugas.books.edit' : 'admin.books.edit';
        return view($view, compact('book', 'categories'));
    }

    /**
     * FUNGSI: Menyimpan Perubahan Data Buku (Update).
     * Mirip seperti store(), namun ini memperbarui (update) data buku yang sudah ada.
     * Terdapat logika menghapus file gambar cover lama jika *user* mengunggah gambar cover baru.
     * Di akhir, akan dilakukan sinkronisasi ('sync') kategori buku di tabel pivot.
     */
    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'penulis' => 'required|string|max:255',
            'penerbit' => 'nullable|string|max:255',
            'tahun_terbit' => 'nullable|integer|min:1900|max:' . date('Y'),
            'stok' => 'required|integer|min:0',
            'category_id' => 'nullable|array',
            'category_id.*' => 'exists:categories,id',
            'cover' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'deskripsi' => 'nullable|string',
        ]);

        if ($request->hasFile('cover')) {
            if ($book->cover) {
                Storage::disk('public')->delete($book->cover);
            }
            $validated['cover'] = $request->file('cover')->store('covers', 'public');
        }

        // Remove category_id from validated data
        // Use input directly to ensuring we get the array even if validation weirdness occurs
        $categoryIds = $request->input('category_id', []);
        
        // Ensure strictly array
        if (!is_array($categoryIds)) {
            $categoryIds = [];
        }

        // Clean up validated data for Book model update
        unset($validated['category_id']);

        $book->update($validated);
        
        // Sync categories
        $book->categories()->sync($categoryIds);

        $user = auth()->user();
        $route = ($user->role === 'petugas') ? 'petugas.books.index' : 'admin.books.index';
        
        return redirect()->route($route)
            ->with('success', 'Buku berhasil diperbarui!');
    }

    /**
     * FUNGSI: Menghapus Data Buku (Delete).
     * Menghapus fisik file gambar sampul (cover) dari memori *storage*.
     * Kemudian menghapus *record* data buku tersebut dari database.
     */
    public function destroy(Book $book)
    {
        if ($book->cover) {
            Storage::disk('public')->delete($book->cover);
        }

        $book->delete();

        $user = auth()->user();
        $route = ($user->role === 'petugas') ? 'petugas.books.index' : 'admin.books.index';
        
        return redirect()->route($route)
            ->with('success', 'Buku berhasil dihapus!');
    }

    public function destroyReview(Book $book, Review $review)
    {
        $review->delete();

        $user = auth()->user();
        $route = ($user->role === 'petugas') ? 'petugas.books.show' : 'admin.books.show';

        return redirect()->route($route, $book)
            ->with('success', 'Ulasan berhasil dihapus!');
    }
}