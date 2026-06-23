<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alamat extends Model
{
    protected $fillable = [
        'user_id',
        'label',
        'nama_penerima',
        'no_hp',
        'provinsi',
        'kota',
        'city_id',
        'kecamatan',
        'kode_pos',
        'alamat_lengkap',
        'is_default',
    ];

    protected function casts(): array
    {
        return [
            'is_default' => 'boolean',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getFullAddressAttribute(): string
    {
        return implode(', ', array_filter([
            $this->alamat_lengkap,
            $this->kecamatan,
            $this->kota,
            $this->provinsi,
            $this->kode_pos,
        ]));
    }
}
