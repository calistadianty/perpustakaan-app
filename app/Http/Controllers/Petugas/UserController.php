<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('role', 'user')->latest()->paginate(10);
        return view('petugas.users.index', compact('users'));
    }

    public function destroy(User $user)
    {
        if ($user->role !== 'user') {
            return redirect()->back()->with('error', 'Hanya bisa menghapus akun pembaca!');
        }

        $user->delete();
        return redirect()->route('petugas.users.index')
            ->with('success', 'Akun pembaca berhasil dihapus!');
    }
}
