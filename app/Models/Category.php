<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['nama'];

    /**
     * RELASI: Buku (Many-to-Many).
     * Model ini menghubungkan tabel kategori dengan tabel buku melalui
     * tabel perantara/pivot bernama 'categories_relasi'. 
     * Saat kita memanggil $kategori->books, sistem akan mencari semua buku untuk kategori ini.
     */
    public function books()
    {
        return $this->belongsToMany(Book::class, 'categories_relasi')->withTimestamps();
    }
}