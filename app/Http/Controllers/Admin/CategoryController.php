<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function index()
    {
        $kategoris = Kategori::withCount('produks')->paginate(10);
        return view('admin.categories.index', compact('kategoris'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:kategoris,nama_kategori',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $data = $request->only('nama_kategori');

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('categories', 'public');
        }

        Kategori::create($data);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit(Kategori $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Kategori $category)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:kategoris,nama_kategori,' . $category->id,
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $data = $request->only('nama_kategori');

        if ($request->hasFile('gambar')) {
            if ($category->gambar) {
                Storage::disk('public')->delete($category->gambar);
            }
            $data['gambar'] = $request->file('gambar')->store('categories', 'public');
        }

        $category->update($data);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Kategori $category)
    {
        if ($category->produks()->count() > 0) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'Tidak dapat menghapus kategori yang masih memiliki produk.');
        }

        if ($category->gambar) {
            Storage::disk('public')->delete($category->gambar);
        }

        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil dihapus.');
    }
}
