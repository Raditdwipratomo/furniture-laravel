<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    protected $fillable = [
        'nama_kategori',
        'gambar',
    ];

    public function produks()
    {
        return $this->hasMany(Produk::class, 'kategori_id', 'id');
    }

    public function getProdukCountAttribute(): int
    {
        return $this->produks()->count();
    }
}
