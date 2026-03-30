<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Koleksi;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'email',
        'avatar',
        'alamat',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * $casts (Casting / Mutator Bawaan Laravel).
     * Fitur ini mengubah format tipe data sebuah kolom secara otomatis saat dibaca/disimpan.
     * Contoh: 'password' otomatis dienkripsi ('hashed') saat kita menyimpan $user->password.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    /**
     * RELASI: Koleksi Buku (Has-Many).
     * Dari data User pengguna ini, kita bisa mengambil seluruh daftar
     * koleksi buku pribadi miliknya.
     */
    public function koleksi()
    {
        return $this->hasMany(Koleksi::class);
    }

    /**
     * RELASI: Riwayat Peminjaman (Has-Many).
     * Menghubungkan pengguna ini dengan seluruh transaksi peminjaman buku
     * yang pernah ia ajukan atau lakukan.
     */
    public function peminjamans()
    {
        return $this->hasMany(Peminjaman::class);
    }
}
