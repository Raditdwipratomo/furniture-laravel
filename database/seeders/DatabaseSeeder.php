<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Kategori;
use App\Models\Produk;
use App\Models\Banner;
use App\Models\Kupon;
use App\Models\Setting;
use App\Models\Alamat;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use App\Models\Pembayaran;
use App\Models\Pengiriman;
use App\Models\Review;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user
        $admin = User::firstOrCreate(
            ['email' => 'admin@furnico.id'],
            [
                'nama' => 'Admin Furnico',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'no_hp' => '081234567890',
            ]
        );

        // Customer users
        $customer1 = User::firstOrCreate(
            ['email' => 'budi@example.com'],
            [
                'nama' => 'Budi Santoso',
                'password' => Hash::make('password'),
                'role' => 'customer',
                'no_hp' => '081298765432',
            ]
        );

        $customer2 = User::firstOrCreate(
            ['email' => 'siti@example.com'],
            [
                'nama' => 'Siti Rahma',
                'password' => Hash::make('password'),
                'role' => 'customer',
                'no_hp' => '081311223344',
            ]
        );

        // Categories
        $kategoris = [
            'Sofa & Kursi' => Kategori::create(['nama_kategori' => 'Sofa & Kursi']),
            'Meja' => Kategori::create(['nama_kategori' => 'Meja']),
            'Lemari & Rak' => Kategori::create(['nama_kategori' => 'Lemari & Rak']),
            'Tempat Tidur' => Kategori::create(['nama_kategori' => 'Tempat Tidur']),
            'Dekorasi' => Kategori::create(['nama_kategori' => 'Dekorasi']),
            'Kantor' => Kategori::create(['nama_kategori' => 'Kantor']),
        ];

        // Products
        $products = [
            Produk::create([
                'kategori_id' => $kategoris['Sofa & Kursi']->id,
                'nama_produk' => 'Sofa Minimalis 3-Seater',
                'sku' => 'SFA-001',
                'deskripsi' => 'Sofa minimalis modern dengan bahan kain premium, busa high-density, dan rangka kayu jati. Cocok untuk ruang tamu keluarga.',
                'harga' => 5500000,
                'stok' => 15,
                'berat' => 45000,
                'gambar' => json_encode(['products/product1.png']),
                'is_featured' => true,
                'is_active' => true,
            ]),
            Produk::create([
                'kategori_id' => $kategoris['Sofa & Kursi']->id,
                'nama_produk' => 'Kursi Santai Rotan',
                'sku' => 'KRS-002',
                'deskripsi' => 'Kursi santai dari rotan sintetis berkualitas tinggi dengan bantal duduk empuk. Ideal untuk teras atau ruang baca.',
                'harga' => 1850000,
                'stok' => 25,
                'berat' => 8000,
                'gambar' => json_encode(['products/product2.png']),
                'is_featured' => true,
                'is_active' => true,
            ]),
            Produk::create([
                'kategori_id' => $kategoris['Meja']->id,
                'nama_produk' => 'Meja Makan Kayu Jati 6 Orang',
                'sku' => 'MJM-003',
                'deskripsi' => 'Meja makan kayu jati solid dengan finishing natural. Kapasitas 6 orang, desain klasik modern.',
                'harga' => 8750000,
                'stok' => 8,
                'berat' => 55000,
                'gambar' => json_encode(['products/product3.png']),
                'is_featured' => true,
                'is_active' => true,
            ]),
            Produk::create([
                'kategori_id' => $kategoris['Meja']->id,
                'nama_produk' => 'Meja Kopi Industrial',
                'sku' => 'MJK-004',
                'deskripsi' => 'Meja kopi dengan desain industrial, kombinasi besi hitam dan kayu solid. Cocok untuk ruang tamu modern.',
                'harga' => 2200000,
                'stok' => 20,
                'berat' => 15000,
                'gambar' => json_encode(['products/product4.png']),
                'is_featured' => true,
                'is_active' => true,
            ]),
            Produk::create([
                'kategori_id' => $kategoris['Lemari & Rak']->id,
                'nama_produk' => 'Lemari Pakaian 3 Pintu',
                'sku' => 'LMP-005',
                'deskripsi' => 'Lemari pakaian 3 pintu dengan cermin, rak dalam yang luas, dan laci penyimpanan. Bahan multiplek premium.',
                'harga' => 4500000,
                'stok' => 12,
                'berat' => 65000,
                'gambar' => json_encode(['products/product5.png']),
                'is_featured' => true,
                'is_active' => true,
            ]),
            Produk::create([
                'kategori_id' => $kategoris['Lemari & Rak']->id,
                'nama_produk' => 'Rak Buku Minimalis 5 Tingkat',
                'sku' => 'RKB-006',
                'deskripsi' => 'Rak buku 5 tingkat dengan desain minimalis modern. Bahan kayu pinus dengan finishing walnut.',
                'harga' => 1650000,
                'stok' => 30,
                'berat' => 12000,
                'gambar' => json_encode(['products/product6.png']),
                'is_featured' => false,
                'is_active' => true,
            ]),
            Produk::create([
                'kategori_id' => $kategoris['Tempat Tidur']->id,
                'nama_produk' => 'Ranjang Queen Size + Headboard',
                'sku' => 'RNG-007',
                'deskripsi' => 'Ranjang queen size dengan headboard berlapis kain. Termasuk rangka besi kuat dan slat kayu.',
                'harga' => 6800000,
                'stok' => 10,
                'berat' => 70000,
                'gambar' => json_encode(['products/product7.png']),
                'is_featured' => true,
                'is_active' => true,
            ]),
            Produk::create([
                'kategori_id' => $kategoris['Tempat Tidur']->id,
                'nama_produk' => 'Nakas Samping Tempat Tidur',
                'sku' => 'NKS-008',
                'deskripsi' => 'Nakas samping tempat tidur dengan 2 laci. Desain minimalis cocok dengan berbagai gaya interior.',
                'harga' => 850000,
                'stok' => 35,
                'berat' => 8000,
                'gambar' => json_encode(['products/product8.png']),
                'is_featured' => false,
                'is_active' => true,
            ]),
            Produk::create([
                'kategori_id' => $kategoris['Dekorasi']->id,
                'nama_produk' => 'Cermin Dinding Besar Frame Emas',
                'sku' => 'CRM-009',
                'deskripsi' => 'Cermin dinding dekoratif dengan frame emas. Ukuran 80x120cm, cocok untuk ruang tamu atau kamar.',
                'harga' => 1200000,
                'stok' => 18,
                'berat' => 5000,
                'gambar' => json_encode(['products/product9.png']),
                'is_featured' => true,
                'is_active' => true,
            ]),
            Produk::create([
                'kategori_id' => $kategoris['Dekorasi']->id,
                'nama_produk' => 'Lampu Meja Keramik',
                'sku' => 'LMP-010',
                'deskripsi' => 'Lampu meja dengan base keramik handmade dan kap kain linen. Cahaya hangat untuk suasana cozy.',
                'harga' => 750000,
                'stok' => 40,
                'berat' => 3000,
                'gambar' => json_encode(['products/product10.png']),
                'is_featured' => false,
                'is_active' => true,
            ]),
            Produk::create([
                'kategori_id' => $kategoris['Kantor']->id,
                'nama_produk' => 'Meja Kerja L-Shape',
                'sku' => 'MJA-011',
                'deskripsi' => 'Meja kerja bentuk L dengan permukaan luas. Dilengkapi cable management dan rak bawah.',
                'harga' => 3200000,
                'stok' => 3,
                'berat' => 35000,
                'gambar' => json_encode(['products/product11.png']),
                'is_featured' => true,
                'is_active' => true,
            ]),
            Produk::create([
                'kategori_id' => $kategoris['Kantor']->id,
                'nama_produk' => 'Kursi Kantor Ergonomis',
                'sku' => 'KRK-012',
                'deskripsi' => 'Kursi kantor ergonomis dengan adjustable lumbar support, armrest, dan headrest. Bahan mesh breathable.',
                'harga' => 4200000,
                'stok' => 0,
                'berat' => 18000,
                'gambar' => json_encode(['products/product12.png']),
                'is_featured' => false,
                'is_active' => true,
            ]),
        ];

        // Banners
        Banner::create([
            'judul' => 'Koleksi Baru 2026',
            'gambar' => 'banners/banner1.png',
            'url' => '/products',
            'is_active' => true,
            'sort_order' => 1,
        ]);
        Banner::create([
            'judul' => 'Diskon Akhir Tahun hingga 40%',
            'gambar' => 'banners/banner2.png',
            'url' => '/products',
            'is_active' => true,
            'sort_order' => 2,
        ]);
        Banner::create([
            'judul' => 'Gratis Ongkir Seluruh Indonesia',
            'gambar' => 'banners/banner3.png',
            'url' => '/products',
            'is_active' => true,
            'sort_order' => 3,
        ]);

        // Coupons
        Kupon::firstOrCreate(['kode' => 'WELCOME10'], [
            'tipe' => 'percent',
            'nilai' => 10,
            'min_order' => 500000,
            'max_uses' => 100,
            'used_count' => 5,
            'valid_from' => now()->subDays(30),
            'valid_until' => now()->addDays(365),
            'is_active' => true,
        ]);
        Kupon::firstOrCreate(['kode' => 'HEMAT50K'], [
            'tipe' => 'fixed',
            'nilai' => 50000,
            'min_order' => 1000000,
            'max_uses' => 50,
            'used_count' => 12,
            'valid_from' => now()->subDays(7),
            'valid_until' => now()->addDays(90),
            'is_active' => true,
        ]);

        // Customer Address
        Alamat::create([
            'user_id' => $customer1->id,
            'label' => 'Rumah',
            'nama_penerima' => 'Budi Santoso',
            'no_hp' => '081298765432',
            'provinsi' => 'DKI Jakarta',
            'kota' => 'Jakarta Selatan',
            'kecamatan' => 'Kebayoran Baru',
            'kode_pos' => '12110',
            'alamat_lengkap' => 'Jl. Senayan No. 45, RT 03/RW 07',
            'is_default' => true,
        ]);

        // Sample Orders
        $pesanan1 = Pesanan::firstOrCreate(
            ['no_pesanan' => 'FRN-' . now()->format('Ymd') . '-0001'],
            [
                'user_id' => $customer1->id,
                'tanggal_pesanan' => now()->format('Y-m-d H:i:s'),
                'total_harga' => 7700000,
                'subtotal' => 7700000,
                'ongkir' => 0,
                'diskon' => 0,
                'status' => 'selesai',
                'alamat_pengiriman' => 'Jl. Senayan No. 45, Kebayoran Baru, Jakarta Selatan, DKI Jakarta, 12110',
            ]
        );

        DetailPesanan::firstOrCreate(['pesanan_id' => $pesanan1->id, 'produk_id' => $products[0]->id], [
            'quantity' => 1,
            'harga' => 5500000,
            'subtotal' => 5500000,
        ]);
        DetailPesanan::firstOrCreate(['pesanan_id' => $pesanan1->id, 'produk_id' => $products[1]->id], [
            'quantity' => 1,
            'harga' => 1850000,
            'subtotal' => 1850000,
        ]);

        Pembayaran::firstOrCreate(['pesanan_id' => $pesanan1->id], [
            'metode_pembayaran' => 'bank_transfer',
            'jumlah' => 7700000,
            'status_pembayaran' => 'berhasil',
            'paid_at' => now()->format('Y-m-d H:i:s'),
        ]);

        Pengiriman::firstOrCreate(['pesanan_id' => $pesanan1->id], [
            'kurir' => 'jne',
            'no_resi' => 'JNE123456789',
            'ongkir' => 0,
            'status_pengiriman' => 'diterima',
            'tanggal_kirim' => now()->subDays(3)->format('Y-m-d'),
        ]);

        // Pending order
        $pesanan2 = Pesanan::firstOrCreate(
            ['no_pesanan' => 'FRN-' . now()->format('Ymd') . '-0002'],
            [
                'user_id' => $customer2->id,
                'tanggal_pesanan' => now()->format('Y-m-d H:i:s'),
                'total_harga' => 8750000,
                'subtotal' => 8750000,
                'ongkir' => 0,
                'diskon' => 0,
                'status' => 'pending',
                'alamat_pengiriman' => 'Jl. Sudirman No. 100, Jakarta Pusat',
            ]
        );

        DetailPesanan::firstOrCreate(['pesanan_id' => $pesanan2->id, 'produk_id' => $products[2]->id], [
            'quantity' => 1,
            'harga' => 8750000,
            'subtotal' => 8750000,
        ]);

        Pembayaran::firstOrCreate(['pesanan_id' => $pesanan2->id], [
            'metode_pembayaran' => 'midtrans',
            'jumlah' => 8750000,
            'status_pembayaran' => 'pending',
        ]);

        // Reviews
        Review::create([
            'user_id' => $customer1->id,
            'produk_id' => $products[0]->id,
            'pesanan_id' => $pesanan1->id,
            'rating' => 5,
            'komentar' => 'Sofa sangat nyaman dan berkualitas. Pengiriman cepat dan packaging aman.',
            'is_approved' => true,
            'tanggal_review' => now()->subDays(2)->format('Y-m-d'),
        ]);
        Review::create([
            'user_id' => $customer1->id,
            'produk_id' => $products[1]->id,
            'pesanan_id' => $pesanan1->id,
            'rating' => 4,
            'komentar' => 'Kursi rotan bagus, desainnya cantik. Sedikit goyang di kaki tapi overall puas.',
            'is_approved' => true,
            'tanggal_review' => now()->subDays(2)->format('Y-m-d'),
        ]);
        Review::create([
            'user_id' => $customer2->id,
            'produk_id' => $products[3]->id,
            'rating' => 5,
            'komentar' => 'Meja kopi industrial keren banget! Pas di ruang tamu saya.',
            'is_approved' => false,
            'tanggal_review' => now()->format('Y-m-d'),
        ]);

        // Settings
        $settings = [
            'store_name' => 'Furnico',
            'store_tagline' => 'Furniture Berkualitas untuk Rumah Impian Anda',
            'contact_email' => 'info@furnico.id',
            'contact_phone' => '+62 812-3456-7890',
            'midtrans_server_key' => env('MIDTRANS_SERVER_KEY', ''),
            'midtrans_client_key' => env('MIDTRANS_CLIENT_KEY', ''),
            'midtrans_is_production' => 'false',
            'rajaongkir_api_key' => env('RAJAONGKIR_API_KEY', ''),
            'store_city_id' => '501',
        ];
        foreach ($settings as $key => $value) {
            Setting::create(['key' => $key, 'value' => $value]);
        }
    }
}
