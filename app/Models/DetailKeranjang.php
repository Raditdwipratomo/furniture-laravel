<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Keranjang;
use App\Models\Produk;

class DetailKeranjang extends Model
{
    //
    protected $fillable = [
        'keranjang_id',
        'produk_id',
        'quantity'
    ];

    public function keranjang()
    {
        return $this->belongsTo(Keranjang::class, 'keranjang_id', 'id');
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id', 'id');
    }
}
