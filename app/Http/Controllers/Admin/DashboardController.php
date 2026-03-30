<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\User;
use Illuminate\Http\Request;
class DashboardController extends Controller
{
    /**
     * FUNGSI: Menampilkan Statistik Cepat Halaman Depan Admin.
     * Berbeda dengan LaporanController yang lebih rumit, fungsi ini ditujukan
     * untuk menampilkan perhitungan matematika dasar dengan cepat menggunakan 'sum()' dan 'count()'.
     */
    public function index()
    {
        $totalBooks = Book::count();
        $totalUsers = User::where('role', 'user')->count();
        $totalStaff = User::where('role', 'petugas')->count();
        $totalStock = Book::sum('stok');
        return view('admin.dashboard', compact('totalBooks', 'totalUsers', 'totalStaff', 'totalStock'));
    }
}