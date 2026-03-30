<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    use HasFactory;

    protected $table = 'peminjaman';

    /**
     * $fillable menentukan properti/kolom database apa saja yang 
     * diizinkan untuk diisi secara langsung oleh kode aplikasi.
     * Mencegah "Mass Assignment Vulnerability" (kerentanan salah isi kolom dari luar).
     */
    protected $fillable = [
        'user_id',
        'book_id',
        'petugas_id',
        'tanggal_pinjam',
        'tanggal_batas_kembali',
        'tanggal_kembali',
        'status',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_pinjam' => 'date',
        'tanggal_batas_kembali' => 'date',
        'tanggal_kembali' => 'date',
    ];

    /**
     * RELASI: Pembaca (Belongs-To).
     * Transaksi peminjaman ini dimiliki/dilakukan oleh satu User tertentu. 
     * Foreign key-nya adalah kolom 'user_id'.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * RELASI: Buku yang Dipinjam (Belongs-To).
     * Transaksi ini merujuk ke data satu Buku spesifik.
     * Foreign key-nya adalah 'book_id'.
     */
    public function book()
    {
        return $this->belongsTo(Book::class, 'book_id');
    }

    /**
     * Relasi ke Petugas
     */
    public function petugas()
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }

    /**
     * Scope untuk filter status
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope untuk peminjaman aktif (dipinjam)
     */
    public function scopeDipinjam($query)
    {
        return $query->where('status', 'dipinjam');
    }

    /**
     * Scope untuk peminjaman yang sudah dikembalikan
     */
    public function scopeDikembalikan($query)
    {
        return $query->where('status', 'dikembalikan');
    }
}