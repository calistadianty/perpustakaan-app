<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
class UserController extends Controller
{
    /**
     * FUNGSI: Manajemen Akun Petugas.
     * Filter basis di sini hanya memunculkan akun yang perannya 'petugas' dari tabel yang sama (users).
     */
    public function index(Request $request)
{
    $role = 'petugas'; // Langsung petugas aja, gausah pilih-pilih
    $users = User::where('role', $role)->latest()->paginate(10);
    return view('admin.petugas.index', compact('users', 'role'));
}
    public function create(Request $request)
    {
        $role = $request->get('role', 'user');
        return view('admin.petugas.create', compact('role'));
    }
    /**
     * FUNGSI: Pembuatan Akun Akses Baru.
     * Validasi paling krusial ada di pengecekan duplikat email dan username ('unique:users').
     * Selain itu, kata sandi (password) HUKUMNYA WAJIB disandikan (enkripsi) dengan Hash::make().
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'alamat' => 'nullable|string|max:500',
            'role' => 'required|in:user,petugas',
        ]);
        $validated['password'] = Hash::make($validated['password']);
        User::create($validated);
        return redirect()->route('admin.petugas.index', ['role' => $validated['role']])
            ->with('success', 'Akun berhasil dibuat!');
    }
    public function edit(User $user)
    {
        return view('admin.petugas.edit', compact('user'));
    }
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'alamat' => 'nullable|string|max:500',
        ]);
        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }
        $user->update($validated);
        return redirect()->route('admin.petugas.index', ['role' => $user->role])
            ->with('success', 'Akun berhasil diperbarui!');
    }
    public function destroy(User $user)
    {
        if ($user->role === 'admin') {
            return redirect()->back()->with('error', 'Tidak dapat menghapus akun admin!');
        }
        $role = $user->role;
        $user->delete();
        return redirect()->route('admin.petugas.index', ['role' => $role])
            ->with('success', 'Akun berhasil dihapus!');
    }
}