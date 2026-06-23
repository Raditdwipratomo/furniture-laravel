<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kupon extends Model
{
    protected $fillable = [
        'kode',
        'tipe',
        'nilai',
        'min_order',
        'max_uses',
        'used_count',
        'valid_from',
        'valid_until',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'valid_from' => 'date',
            'valid_until' => 'date',
        ];
    }

    public function isValid(): bool
    {
        if (!$this->is_active) return false;
        if ($this->max_uses > 0 && $this->used_count >= $this->max_uses) return false;
        $now = now();
        if ($now->lt($this->valid_from) || $now->gt($this->valid_until)) return false;
        return true;
    }

    public function calculateDiscount(int $subtotal): int
    {
        if ($this->tipe === 'percent') {
            return (int) ($subtotal * $this->nilai / 100);
        }
        return min($this->nilai, $subtotal);
    }
}
