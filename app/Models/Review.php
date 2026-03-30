<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = ['user_id', 'book_id', 'rating', 'comment'];

    /**
     * RELASI: Penulis Ulasan (Belongs-To).
     * Mengikat ulasan ini pada profil pengguna yang mempostingnya.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * RELASI: Buku yang Diulas (Belongs-To).
     * Mengikat ulasan ini pada spesifik satu buku.
     */
    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
