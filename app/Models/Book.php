<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Review;
use App\Models\Koleksi;

class Book extends Model
{
    use HasFactory;
    
    /**
     * $fillable adalah daftar nama kolom di tabel 'books' yang diizinkan 
     * untuk diisi secara massal (Mass Assignment) melalui Book::create() atau update().
     * Ini mencegah celah keamanan di mana *user* nakal bisa memanipulasi kolom lain.
     */
    protected $fillable = [
        'judul',
        'penulis',
        'penerbit',
        'tahun_terbit',
        'stok',
        // 'category_id' removed, use categories() relationship
        'cover',
        'deskripsi',
        'status',
        'submitted_by',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
    ];
    
    /**
     * RELASI: Kategori (Many-to-Many / Banyak ke Banyak).
     * Satu buku bisa memiliki banyak kategori, dan satu kategori bisa dimiliki banyak buku.
     * Karena itu, kita menggunakan tabel penghubung pivot bernama 'categories_relasi'.
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'categories_relasi')->withTimestamps();
    }

    /**
     * RELASI: Pengaju Buku (Belongs-To).
     * Fungsi ini mencari tahu data User (pengguna) mana yang pertama kali 
     * menginput/mengajukan buku ini ke dalam sistem.
     */
    public function submitter()
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    /**
     * RELASI: Penyetuju Buku (Belongs-To).
     * Jika buku harus disetujui, relasi ini mengecek Admin/Petugas (User) mana 
     * yang mengeklik tombol *approve* pada buku tersebut.
     */
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * RELASI: Riwayat Peminjaman (Has-Many).
     * Mengambil daftar semua transaksi di mana buku ini pernah dipinjam.
     */
    public function peminjamans()
    {
        return $this->hasMany(Peminjaman::class, 'book_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * LOCAL SCOPE: Hanya ambil buku yang sudah Disetujui (approved).
     * Mempermudah pemanggilan di Controller. 
     * Contoh penggunaannya: Book::approved()->get();
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    // Scope untuk buku pending
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
    public function koleksi()
    {
        return $this->hasMany(Koleksi::class);
    }
}