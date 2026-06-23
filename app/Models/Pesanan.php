<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    protected $fillable = [
        'user_id',
        'no_pesanan',
        'tanggal_pesanan',
        'total_harga',
        'subtotal',
        'ongkir',
        'diskon',
        'status',
        'alamat_pengiriman',
        'catatan',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function detail()
    {
        return $this->hasMany(DetailPesanan::class, 'pesanan_id', 'id');
    }

    public function pengiriman()
    {
        return $this->hasOne(Pengiriman::class, 'pesanan_id', 'id');
    }

    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class, 'pesanan_id', 'id');
    }

    public static function generateOrderNumber(): string
    {
        $date = now()->format('Ymd');
        $lastOrder = self::where('no_pesanan', 'like', 'FRN-' . $date . '-%')
            ->orderBy('id', 'desc')
            ->first();
        $sequence = 1;
        if ($lastOrder) {
            $parts = explode('-', $lastOrder->no_pesanan);
            $sequence = intval(end($parts)) + 1;
        }
        return 'FRN-' . $date . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'bg-yellow-100 text-yellow-800',
            'dibayar' => 'bg-blue-100 text-blue-800',
            'diproses' => 'bg-indigo-100 text-indigo-800',
            'dikirim' => 'bg-purple-100 text-purple-800',
            'selesai' => 'bg-green-100 text-green-800',
            'dibatalkan' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }
}
