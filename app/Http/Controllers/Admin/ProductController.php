<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Produk::with('kategori');

        if ($request->filled('q')) {
            $query->where('nama_produk', 'like', '%' . $request->q . '%')
                ->orWhere('sku', 'like', '%' . $request->q . '%');
        }

        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }

        if ($request->filled('stok')) {
            if ($request->stok === 'low') {
                $query->where('stok', '<', 5);
            } elseif ($request->stok === 'out') {
                $query->where('stok', 0);
            } elseif ($request->stok === 'available') {
                $query->where('stok', '>=', 5);
            }
        }

        $produks = $query->latest()->paginate(12)->withQueryString();
        $kategoris = Kategori::orderBy('nama_kategori')->get();

        return view('admin.products.index', compact('produks', 'kategoris'));
    }

    public function create()
    {
        $kategoris = Kategori::orderBy('nama_kategori')->get();
        return view('admin.products.create', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kategori_id' => 'required|exists:kategoris,id',
            'nama_produk' => 'required|string|max:255',
            'sku' => 'required|string|max:100|unique:produks,sku',
            'deskripsi' => 'nullable|string',
            'harga' => 'required|integer|min:0',
            'stok' => 'required|integer|min:0',
            'berat' => 'required|integer|min:0',
            'gambar.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $data = $request->except('gambar', 'is_featured', 'is_active');
        $data['is_featured'] = $request->has('is_featured');
        $data['is_active'] = $request->has('is_active');

        $images = [];
        if ($request->hasFile('gambar')) {
            foreach ($request->file('gambar') as $file) {
                $images[] = $file->store('products', 'public');
            }
        }
        $data['gambar'] = $images;

        Produk::create($data);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(Produk $product)
    {
        $kategoris = Kategori::orderBy('nama_kategori')->get();
        return view('admin.products.edit', compact('product', 'kategoris'));
    }

    public function update(Request $request, Produk $product)
    {
        $request->validate([
            'kategori_id' => 'required|exists:kategoris,id',
            'nama_produk' => 'required|string|max:255',
            'sku' => 'required|string|max:100|unique:produks,sku,' . $product->id,
            'deskripsi' => 'nullable|string',
            'harga' => 'required|integer|min:0',
            'stok' => 'required|integer|min:0',
            'berat' => 'required|integer|min:0',
            'gambar.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $data = $request->except('gambar', 'is_featured', 'is_active');
        $data['is_featured'] = $request->has('is_featured');
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('gambar')) {
            // Delete old images
            $oldImages = $product->gambar ?? [];
            foreach ($oldImages as $img) {
                Storage::disk('public')->delete($img);
            }

            $images = [];
            foreach ($request->file('gambar') as $file) {
                $images[] = $file->store('products', 'public');
            }
            $data['gambar'] = $images;
        }

        $product->update($data);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Produk $product)
    {
        $images = $product->gambar ?? [];
        foreach ($images as $img) {
            Storage::disk('public')->delete($img);
        }

        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil dihapus.');
    }
}
