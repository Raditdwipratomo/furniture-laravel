<!DOCTYPE html>
<html>
<body style="font-family: Arial, sans-serif; color: #333; max-width: 600px; margin: 0 auto;">
    <div style="background: #7c3aed; padding: 20px; text-align: center;">
        <h1 style="color: white; margin: 0;">Pesanan Dikirim</h1>
    </div>
    <div style="padding: 30px; background: #fff;">
        <p>Halo <strong>{{ $pesanan->user->nama }}</strong>,</p>
        <p>Pesanan <strong>#{{ $pesanan->no_pesanan }}</strong> telah dikirim!</p>
        <table style="width: 100%; border-collapse: collapse; margin: 20px 0;">
            <tr style="background: #f5f5f4;">
                <td style="padding: 10px; border: 1px solid #e7e5e4;">Kurir</td>
                <td style="padding: 10px; border: 1px solid #e7e5e4;">{{ strtoupper($pengiriman->kurir) }}</td>
            </tr>
            @if($pengiriman->no_resi)
            <tr>
                <td style="padding: 10px; border: 1px solid #e7e5e4;">No. Resi</td>
                <td style="padding: 10px; border: 1px solid #e7e5e4;"><strong>{{ $pengiriman->no_resi }}</strong></td>
            </tr>
            @endif
        </table>
        <p>Anda dapat melacak pesanan melalui halaman akun Anda.</p>
        <p style="color: #78716c; font-size: 14px;">Terima kasih telah berbelanja di Furnico!</p>
    </div>
</body>
</html>
