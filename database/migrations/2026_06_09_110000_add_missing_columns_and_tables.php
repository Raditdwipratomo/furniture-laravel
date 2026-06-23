<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add missing columns to users
        Schema::table('users', function (Blueprint $table) {
            $table->string('avatar')->nullable()->after('no_hp');
        });

        // Add missing columns to produks
        Schema::table('produks', function (Blueprint $table) {
            $table->string('sku')->nullable()->after('nama_produk');
            $table->boolean('is_featured')->default(false)->after('gambar');
            $table->boolean('is_active')->default(true)->after('is_featured');
        });

        // Add missing columns to pesanans
        Schema::table('pesanans', function (Blueprint $table) {
            $table->string('no_pesanan')->unique()->nullable()->after('user_id');
            $table->integer('subtotal')->default(0)->after('total_harga');
            $table->integer('ongkir')->default(0)->after('subtotal');
            $table->integer('diskon')->default(0)->after('ongkir');
            $table->text('catatan')->nullable()->after('alamat_pengiriman');
        });

        // Add missing columns to reviews
        Schema::table('reviews', function (Blueprint $table) {
            $table->foreignId('pesanan_id')->nullable()->after('produk_id')->constrained('pesanans')->nullOnDelete();
            $table->boolean('is_approved')->default(false)->after('komentar');
        });

        // Add missing columns to pembayarans
        Schema::table('pembayarans', function (Blueprint $table) {
            $table->string('snap_token')->nullable()->after('pesanan_id');
            $table->string('transaction_id')->nullable()->after('snap_token');
            $table->json('payload')->nullable()->after('status_pembayaran');
        });

        // Create alamats (addresses) table
        Schema::create('alamats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('label')->default('Rumah');
            $table->string('nama_penerima');
            $table->string('no_hp');
            $table->string('provinsi');
            $table->string('kota');
            $table->string('kecamatan')->nullable();
            $table->string('kode_pos')->nullable();
            $table->text('alamat_lengkap');
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });

        // Create banners table
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('gambar');
            $table->string('url')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // Create kupons (coupons) table
        Schema::create('kupons', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique();
            $table->enum('tipe', ['fixed', 'percent']);
            $table->integer('nilai');
            $table->integer('min_order')->default(0);
            $table->integer('max_uses')->default(0);
            $table->integer('used_count')->default(0);
            $table->date('valid_from');
            $table->date('valid_until');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Create wishlists table
        Schema::create('wishlists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('produk_id')->constrained('produks')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['user_id', 'produk_id']);
        });

        // Create settings table
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
        Schema::dropIfExists('wishlists');
        Schema::dropIfExists('kupons');
        Schema::dropIfExists('banners');
        Schema::dropIfExists('alamats');

        Schema::table('pembayarans', function (Blueprint $table) {
            $table->dropColumn(['snap_token', 'transaction_id', 'payload']);
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->dropColumn(['pesanan_id', 'is_approved']);
        });

        Schema::table('pesanans', function (Blueprint $table) {
            $table->dropColumn(['no_pesanan', 'subtotal', 'ongkir', 'diskon', 'catatan']);
        });

        Schema::table('produks', function (Blueprint $table) {
            $table->dropColumn(['sku', 'is_featured', 'is_active']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('avatar');
        });
    }
};
