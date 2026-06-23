<!DOCTYPE html>
<html>
<body style="font-family: Arial, sans-serif; color: #333; max-width: 600px; margin: 0 auto;">
    <div style="background: #16a34a; padding: 20px; text-align: center;">
        <h1 style="color: white; margin: 0;">Pembayaran Dikonfirmasi</h1>
    </div>
    <div style="padding: 30px; background: #fff;">
        <p>Halo <strong>{{ $pesanan->user->nama }}</strong>,</p>
        <p>Pembayaran untuk pesanan <strong>#{{ $pesanan->no_pesanan }}</strong> telah kami terima.</p>
        <p>Pesanan Anda sedang diproses dan akan segera dikirim.</p>
        <p style="color: #78716c; font-size: 14px;">Terima kasih telah berbelanja di Furnico!</p>
    </div>
</body>
</html>
