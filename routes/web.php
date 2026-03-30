<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Models\Book;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\BookController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PeminjamanController as AdminPeminjamanController; // Renamed to avoid conflict
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\KoleksiController;
use App\Http\Controllers\Petugas\UserController as PetugasUserController;

/*
|--------------------------------------------------------------------------
| PUBLIC
|--------------------------------------------------------------------------
*/
/*
 * ROUTE: GET '/' (Halaman Beranda / Landing Page)
 * Mengambil 4 buku terbaru yang sudah disetujui (approved) untuk ditampilkan ke pengunjung.
 */
Route::get('/', function () {
    $books = Book::with('categories')
                 ->approved()
                 ->latest()
                 ->take(4)
                 ->get();

    return view('welcome', compact('books'));
})->name('home');

/*
 * MIDDLEWARE 'auth': Memastikan bahwa baris-baris kode rute di dalam blok grup ini
 * HANYA BISA DIAKSES oleh pengguna yang sudah berhasil Login.
 * Jika pengguna belum login mencoba mengakses '/katalog', sistem otomatis menendangnya ke halaman Login.
 */
// CATALOG ROUTES
Route::middleware('auth')->group(function() {
    // CATALOG (PROTECTED)
    Route::get('/katalog', [CatalogController::class, 'index'])->name('catalog.index');
    Route::get('/katalog/{book}', [CatalogController::class, 'show'])->name('catalog.show');
    
    // REVIEWS
    Route::post('/katalog/{book}/rate', [CatalogController::class, 'storeReview'])->name('catalog.rate');
    Route::patch('/reviews/{review}', [CatalogController::class, 'updateReview'])->name('reviews.update');
    Route::delete('/reviews/{review}', [CatalogController::class, 'destroyReview'])->name('reviews.destroy');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/koleksi', [KoleksiController::class, 'index'])->name('koleksi.index');
    Route::post('/koleksi', [KoleksiController::class, 'store'])->name('koleksi.store');
    Route::delete('/koleksi/{id}', [KoleksiController::class, 'destroy'])->name('koleksi.destroy');
});

/*
|--------------------------------------------------------------------------
| REDIRECT DASHBOARD (GERBANG UTAMA SETELAH LOGIN)
|--------------------------------------------------------------------------
*/
/*
 * MIDDLEWARE ROUTING BERDASARKAN ROLE (Pintu Gerbang Utama):
 * Rute '/dashboard' ini dipanggil otomatis setelah sistem Laravel sukses melakukan login.
 * Blok kode ini mengecek peran (role) pengguna, lalu melemparnya (redirect) ke jalur yang benar:
 * Admin ke dashboard admin, Petugas ke petugas, User biasa ke user.
 */
Route::get('/dashboard', function () {
    $user = Auth::user();

    if ($user->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }
    
    if ($user->role === 'petugas') {
        return redirect()->route('petugas.dashboard');
    }

    return redirect()->route('user.dashboard');
})->middleware(['auth'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| USER ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    Route::get('/user/dashboard', function () {
        $books = Book::with('categories')
                    ->approved()
                    ->latest()
                    ->take(4)
                    ->get();

        return view('welcome', compact('books'));
    })->name('user.dashboard');

    Route::get('/peminjaman/riwayat', [PeminjamanController::class, 'history'])->name('peminjaman.history');
    Route::get('/peminjaman/{peminjaman}/receipt', [PeminjamanController::class, 'exportReceipt'])->name('peminjaman.export-receipt');
    Route::post('/peminjaman/{peminjaman}/kembali', [PeminjamanController::class, 'ajukanKembali'])->name('peminjaman.ajukan-kembali');
    Route::resource('peminjaman', PeminjamanController::class)->only(['index', 'store']);


});

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/
/*
 * PREFIX & NAME: URL dalam grup ini otomatis diawali '/admin/...' dan penamaan rute diawali 'admin....'.
 * MIDDLEWARE 'role:admin': Ini lapis keamanan ganda, HANYA pengguna berstatus Admin yang bisa masuk ke sini.
 */
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    /*
     * ROUTE RESOURCE ('Route::resource'):
     * Fitur sakti Laravel yang dengan menamakan 1 baris kode saja, otomatis mendaftarkan 7 rute CRUD lengkap
     * (index, create, store, show, edit, update, destroy) untuk entitas Books.
     */
    Route::resource('books', BookController::class);
    Route::delete('/books/{book}/reviews/{review}', [BookController::class, 'destroyReview'])->name('books.reviews.destroy');

    Route::resource('petugas', UserController::class)
        ->except(['show'])
        ->parameters(['petugas' => 'user']);

    Route::resource('categories', CategoryController::class)
        ->except(['show']);

    Route::get('/pembaca', function () {
        $users = \App\Models\User::where('role', 'user')->latest()->paginate(10);
        return view('admin.pembaca.index', compact('users'));
    })->name('pembaca.index');

    Route::delete('/pembaca/{user}', function (\App\Models\User $user) {
        if ($user->role !== 'user') {
            return redirect()->back()->with('error', 'Hanya bisa hapus akun pembaca!');
        }

        $user->delete();
        return redirect()->route('admin.pembaca.index')
            ->with('success', 'Akun pembaca berhasil dihapus!');
    })->name('pembaca.destroy');

    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/peminjaman/pdf', [LaporanController::class, 'exportPeminjaman'])->name('laporan.peminjaman');
    Route::get('/laporan/anggota/pdf', [LaporanController::class, 'exportAnggota'])->name('laporan.anggota');
    Route::get('/laporan/buku-populer/pdf', [LaporanController::class, 'exportBukuPopuler'])->name('laporan.buku-populer');
    Route::get('/laporan/keterlambatan/pdf', [LaporanController::class, 'exportKeterlambatan'])->name('laporan.keterlambatan');

    Route::patch('/peminjaman/{peminjaman}/approve', [AdminPeminjamanController::class, 'approve'])->name('peminjaman.approve');
    Route::patch('/peminjaman/{peminjaman}/reject', [AdminPeminjamanController::class, 'reject'])->name('peminjaman.reject');
    Route::patch('/peminjaman/{peminjaman}/update', [AdminPeminjamanController::class, 'update'])->name('peminjaman.update');
    Route::patch('/peminjaman/{peminjaman}/approve-kembali', [AdminPeminjamanController::class, 'approveKembali'])->name('peminjaman.approve-kembali');
    Route::patch('/peminjaman/{peminjaman}/reject-kembali', [AdminPeminjamanController::class, 'rejectKembali'])->name('peminjaman.reject-kembali');
    Route::get('/peminjaman/history', [AdminPeminjamanController::class, 'history'])->name('peminjaman.history'); // Added history route
    Route::resource('peminjaman', AdminPeminjamanController::class);

});

/*
|--------------------------------------------------------------------------
| PETUGAS ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('petugas')->name('petugas.')->group(function () {

    Route::get('/dashboard', function () {
        $totalBooks = \App\Models\Book::count();
        $totalStock = \App\Models\Book::sum('stok');
        $totalPeminjaman = \App\Models\Peminjaman::count();
        return view('petugas.dashboard', compact('totalBooks', 'totalStock', 'totalPeminjaman'));
    })->name('dashboard');

    // Petugas bisa manage buku
    Route::resource('books', BookController::class);
    Route::delete('/books/{book}/reviews/{review}', [BookController::class, 'destroyReview'])->name('books.reviews.destroy');
    Route::resource('categories', CategoryController::class)->except(['show']);
    
    // Petugas bisa manage peminjaman
    Route::patch('/peminjaman/{peminjaman}/approve', [AdminPeminjamanController::class, 'approve'])->name('peminjaman.approve');
    Route::patch('/peminjaman/{peminjaman}/reject', [AdminPeminjamanController::class, 'reject'])->name('peminjaman.reject');
    Route::patch('/peminjaman/{peminjaman}/update', [AdminPeminjamanController::class, 'update'])->name('peminjaman.update');
    Route::patch('/peminjaman/{peminjaman}/approve-kembali', [AdminPeminjamanController::class, 'approveKembali'])->name('peminjaman.approve-kembali');
    Route::patch('/peminjaman/{peminjaman}/reject-kembali', [AdminPeminjamanController::class, 'rejectKembali'])->name('peminjaman.reject-kembali');
    Route::get('/peminjaman/history', [AdminPeminjamanController::class, 'history'])->name('peminjaman.history'); // Added history route
    Route::resource('peminjaman', AdminPeminjamanController::class);

    // Laporan
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/peminjaman/pdf', [LaporanController::class, 'exportPeminjaman'])->name('laporan.peminjaman');
    Route::get('/laporan/anggota/pdf', [LaporanController::class, 'exportAnggota'])->name('laporan.anggota');
    Route::get('/laporan/buku-populer/pdf', [LaporanController::class, 'exportBukuPopuler'])->name('laporan.buku-populer');
    Route::get('/laporan/keterlambatan/pdf', [LaporanController::class, 'exportKeterlambatan'])->name('laporan.keterlambatan');

    // Manajemen User
    Route::get('/users', [PetugasUserController::class, 'index'])->name('users.index');
    Route::delete('/users/{user}', [PetugasUserController::class, 'destroy'])->name('users.destroy');
});

/*
|--------------------------------------------------------------------------
| PROFILE
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';
