<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Book;

class Koleksi extends Model
{
    protected $table = 'koleksi_buku';

    protected $fillable = [
        'user_id',
        'book_id',
    ];

    /**
     * RELASI: Pemilik Koleksi (Belongs-To).
     * Berfungsi untuk mengetahui koleksi ini milik User yang mana.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * RELASI: Buku yang Dikoleksi (Belongs-To).
     * Berfungsi untuk menampilkan data lengkap buku yang disimpan 
     * dalam daftar koleksi ("wishlist"/koleksi) ini.
     */
    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
