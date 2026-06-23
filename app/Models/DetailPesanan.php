<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPesanan extends Model
{
    //

    protected $fillable = [
        'pesanan_id',
        'produk_id',
        'quantity',
        'harga',
        'subtotal'
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class);
    }
}
