<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengirimen', function (Blueprint $table) {
            $table->string('no_resi')->nullable()->change();
            $table->string('tanggal_kirim')->nullable()->change();
            $table->string('ongkir')->default(0)->change();
        });
    }

    public function down(): void
    {
        Schema::table('pengirimen', function (Blueprint $table) {
            $table->string('no_resi')->nullable(false)->change();
            $table->string('tanggal_kirim')->nullable(false)->change();
        });
    }
};
