<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pengirimen', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pesanan_id');
            $table->string('kurir');
            $table->string('no_resi');
            $table->string('ongkir');
            $table->enum('status_pengiriman', [
                'menunggu_pengiriman',
                'diproses',
                'dikirim',
                'dalam_perjalanan',
                'diterima',
                'gagal_kirim',
                'dikembalikan'
            ])->default('menunggu_pengiriman');
            $table->string('tanggal_kirim');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengirimen');
    }
};
