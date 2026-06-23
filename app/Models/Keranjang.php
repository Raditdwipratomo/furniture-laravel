<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Keranjang extends Model
{
    //
    protected $fillable = [
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function detailKeranjang()
    {
        return $this->hasMany(DetailKeranjang::class, 'keranjang_id', 'id');
    }
}
