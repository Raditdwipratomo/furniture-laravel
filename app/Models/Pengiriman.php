<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Pesanan;

class Pengiriman extends Model
{
    //
    protected $fillable = [
        'pesanan_id',
        'kurir',
        'no_resi',
        'ongkir',
        'status_pengiriman',
        'tanggal_kirim'
    ];

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'pesanan_id', 'id');
    }
}
