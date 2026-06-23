<!DOCTYPE html>
<html>
<body style="font-family: Arial, sans-serif; color: #333; max-width: 600px; margin: 0 auto;">
    <div style="background: #d97706; padding: 20px; text-align: center;">
        <h1 style="color: white; margin: 0;">Furnico</h1>
    </div>
    <div style="padding: 30px; background: #fff;">
        <h2>Terima kasih atas pesanan Anda!</h2>
        <p>Halo <strong>{{ $pesanan->user->nama }}</strong>,</p>
        <p>Pesanan Anda telah berhasil dibuat dengan detail:</p>
        <table style="width: 100%; border-collapse: collapse; margin: 20px 0;">
            <tr style="background: #f5f5f4;">
                <td style="padding: 10px; border: 1px solid #e7e5e4;">No. Pesanan</td>
                <td style="padding: 10px; border: 1px solid #e7e5e4;"><strong>{{ $pesanan->no_pesanan }}</strong></td>
            </tr>
            <tr>
                <td style="padding: 10px; border: 1px solid #e7e5e4;">Tanggal</td>
                <td style="padding: 10px; border: 1px solid #e7e5e4;">{{ $pesanan->tanggal_pesanan }}</td>
            </tr>
            <tr style="background: #f5f5f4;">
                <td style="padding: 10px; border: 1px solid #e7e5e4;">Total</td>
                <td style="padding: 10px; border: 1px solid #e7e5e4;"><strong>Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</strong></td>
            </tr>
        </table>
        <p>Silakan selesaikan pembayaran Anda untuk memproses pesanan.</p>
        <p style="color: #78716c; font-size: 14px;">Jika Anda memiliki pertanyaan, hubungi kami di info@furnico.id</p>
    </div>
</body>
</html>
