<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * FUNGSI: Menampilkan Halaman Daftar Kategori.
     * Menggunakan 'withCount' milik Eloquent untuk menghitung secara dinamis 
     * berapa banyak buku yang ada di dalam masing-masing kategori tanpa harus me-looping datanya.
     */
    public function index()
    {
        $categories = Category::withCount('books')->latest()->paginate(10);
        $view = auth()->user()->role === 'petugas' ? 'petugas.categories.index' : 'admin.categories.index';
        return view($view, compact('categories'));
    }

    public function create()
    {
        $view = auth()->user()->role === 'petugas' ? 'petugas.categories.create' : 'admin.categories.create';
        return view($view);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100|unique:categories',
        ]);

        Category::create($validated);

        $route = auth()->user()->role === 'petugas' ? 'petugas.categories.index' : 'admin.categories.index';

        return redirect()->route($route)
            ->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function edit(Category $category)
    {
        $view = auth()->user()->role === 'petugas' ? 'petugas.categories.edit' : 'admin.categories.edit';
        return view($view, compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100|unique:categories,nama,' . $category->id,
        ]);

        $category->update($validated);

        $route = auth()->user()->role === 'petugas' ? 'petugas.categories.index' : 'admin.categories.index';

        return redirect()->route($route)
            ->with('success', 'Kategori berhasil diperbarui!');
    }

    public function destroy(Category $category)
    {
        $category->delete();

        $route = auth()->user()->role === 'petugas' ? 'petugas.categories.index' : 'admin.categories.index';

        return redirect()->route($route)
            ->with('success', 'Kategori berhasil dihapus!');
    }
}