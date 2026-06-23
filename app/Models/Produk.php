<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    protected $fillable = [
        'kategori_id',
        'nama_produk',
        'sku',
        'deskripsi',
        'harga',
        'stok',
        'berat',
        'gambar',
        'is_featured',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'gambar' => 'array',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id', 'id');
    }

    public function detailKeranjang()
    {
        return $this->hasMany(DetailKeranjang::class, 'produk_id', 'id');
    }

    public function detailPesanan()
    {
        return $this->hasMany(DetailPesanan::class, 'produk_id', 'id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'produk_id', 'id');
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class, 'produk_id', 'id');
    }

    public function getGambarUtamaAttribute(): string
    {
        $images = $this->gambar;
        if (is_array($images) && count($images) > 0) {
            return $images[0];
        }
        return 'images/placeholder.jpg';
    }

    public function getAvgRatingAttribute(): float
    {
        return round($this->reviews()->where('is_approved', true)->avg('rating') ?? 0, 1);
    }
}
