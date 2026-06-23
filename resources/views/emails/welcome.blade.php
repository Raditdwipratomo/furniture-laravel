<!DOCTYPE html>
<html>
<body style="font-family: Arial, sans-serif; color: #333; max-width: 600px; margin: 0 auto;">
    <div style="background: #d97706; padding: 20px; text-align: center;">
        <h1 style="color: white; margin: 0;">Selamat Datang di Furnico!</h1>
    </div>
    <div style="padding: 30px; background: #fff;">
        <h2>Halo {{ $user->nama }},</h2>
        <p>Terima kasih telah bergabung dengan Furnico - toko furnitur online berkualitas.</p>
        <p>Dengan akun ini, Anda dapat:</p>
        <ul>
            <li>Belanja berbagai koleksi furnitur pilihan</li>
            <li>Melacak pesanan dengan mudah</li>
            <li>Menyimpan produk favorit</li>
            <li>Mendapatkan promo eksklusif</li>
        </ul>
        <p style="text-align: center; margin: 30px 0;">
            <a href="{{ url('/products') }}" style="background: #d97706; color: white; padding: 12px 30px; text-decoration: none; border-radius: 8px; font-weight: bold;">Mulai Belanja</a>
        </p>
        <p style="color: #78716c; font-size: 14px;">Selamat berbelanja!</p>
    </div>
</body>
</html>
