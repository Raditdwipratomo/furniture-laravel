<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\Pembayaran;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    private function midtransServerKey(): string
    {
        return Setting::get('midtrans_server_key', config('services.midtrans.server_key', ''));
    }

    private function midtransClientKey(): string
    {
        return Setting::get('midtrans_client_key', config('services.midtrans.client_key', ''));
    }

    private function midtransIsProduction(): bool
    {
        return filter_var(
            Setting::get('midtrans_is_production', config('services.midtrans.is_production', false)),
            FILTER_VALIDATE_BOOLEAN
        );
    }

    private function midtransIsSanitized(): bool
    {
        return filter_var(
            Setting::get('midtrans_is_sanitized', config('services.midtrans.is_sanitized', true)),
            FILTER_VALIDATE_BOOLEAN
        );
    }

    private function midtransIs3ds(): bool
    {
        return filter_var(
            Setting::get('midtrans_is_3ds', config('services.midtrans.is_3ds', true)),
            FILTER_VALIDATE_BOOLEAN
        );
    }

    private function configureMidtrans(): void
    {
        \Midtrans\Config::$serverKey = $this->midtransServerKey();
        \Midtrans\Config::$isProduction = $this->midtransIsProduction();
        \Midtrans\Config::$isSanitized = $this->midtransIsSanitized();
        \Midtrans\Config::$is3ds = $this->midtransIs3ds();
    }

    /**
     * Map Midtrans transaction_status to internal payment status.
     */
    private function mapPaymentStatus(string $transactionStatus): string
    {
        return match ($transactionStatus) {
            'capture', 'settlement' => 'berhasil',
            'pending' => 'pending',
            'deny', 'cancel', 'expire' => 'gagal',
            default => 'pending',
        };
    }

    /**
     * Update order and payment records based on payment status.
     */
    private function updateOrderStatus(Pesanan $pesanan, Pembayaran $pembayaran, string $statusPembayaran, string $transactionStatus, ?string $transactionId, ?string $paymentType, array $payload): void
    {
        DB::transaction(function () use ($pesanan, $pembayaran, $statusPembayaran, $transactionId, $paymentType, $payload) {
            $pembayaran->update([
                'transaction_id' => $transactionId,
                'metode_pembayaran' => $paymentType ?? $pembayaran->metode_pembayaran,
                'status_pembayaran' => $statusPembayaran,
                'payload' => $payload,
                'paid_at' => $statusPembayaran === 'berhasil' ? now() : $pembayaran->paid_at,
            ]);

            if ($statusPembayaran === 'berhasil') {
                $pesanan->update(['status' => 'dibayar']);
            } elseif ($statusPembayaran === 'gagal') {
                $pesanan->update(['status' => 'dibatalkan']);
            }
            // 'pending' - no order status change
        });
    }

    public function createSnapToken(Request $request)
    {
        $request->validate([
            'no_pesanan' => 'required|string',
        ]);

        $pesanan = Pesanan::with('detail.produk')
            ->where('user_id', auth()->id())
            ->where('no_pesanan', $request->no_pesanan)
            ->firstOrFail();

        $pembayaran = Pembayaran::where('pesanan_id', $pesanan->id)->firstOrFail();

        if ($pembayaran->snap_token) {
            return response()->json(['snap_token' => $pembayaran->snap_token]);
        }

        $this->configureMidtrans();

        $params = [
            'transaction_details' => [
                'order_id' => $pesanan->no_pesanan,
                'gross_amount' => $pesanan->total_harga,
            ],
            'customer_details' => [
                'first_name' => auth()->user()->nama,
                'email' => auth()->user()->email,
                'phone' => auth()->user()->no_hp,
            ],
            'item_details' => $pesanan->detail->map(function ($item) {
                return [
                    'id' => $item->produk_id,
                    'price' => $item->harga,
                    'quantity' => $item->quantity,
                    'name' => substr($item->produk->nama_produk, 0, 50),
                ];
            })->toArray(),
        ];

        try {
            $snapToken = \Midtrans\Snap::getSnapToken($params);
            $pembayaran->update(['snap_token' => $snapToken]);

            return response()->json(['snap_token' => $snapToken]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal membuat snap token: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Handle payment result from frontend Snap.js callbacks.
     */
    public function handle(Request $request)
    {
        $request->validate([
            'order_id' => 'required|string',
            'transaction_status' => 'required|string',
            'transaction_id' => 'nullable|string',
            'payment_type' => 'nullable|string',
        ]);

        Log::info('Payment handle() called from frontend', [
            'order_id' => $request->order_id,
            'transaction_status' => $request->transaction_status,
        ]);

        $pesanan = Pesanan::where('no_pesanan', $request->order_id)->firstOrFail();
        $pembayaran = Pembayaran::where('pesanan_id', $pesanan->id)->firstOrFail();

        $statusPembayaran = $this->mapPaymentStatus($request->transaction_status);

        $this->updateOrderStatus(
            $pesanan,
            $pembayaran,
            $statusPembayaran,
            $request->transaction_status,
            $request->transaction_id,
            $request->payment_type,
            $request->all()
        );

        return response()->json([
            'message' => 'Payment processed successfully',
            'status' => $statusPembayaran,
            'order_status' => $pesanan->fresh()->status,
        ]);
    }

    /**
     * Handle server-to-server notification from Midtrans (webhook).
     * This is the PRIMARY mechanism for updating payment status.
     */
    public function handleNotification(Request $request)
    {
        Log::info('Midtrans notification received', [
            'order_id' => $request->order_id,
            'transaction_status' => $request->transaction_status,
            'status_code' => $request->status_code,
            'all' => $request->all(),
        ]);

        $this->configureMidtrans();

        $orderId = $request->order_id;
        $statusCode = $request->status_code;
        $grossAmount = $request->gross_amount;
        $signatureKey = $request->signature_key;

        // Validate signature
        $expectedSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $this->midtransServerKey());

        if ($signatureKey !== $expectedSignature) {
            Log::warning('Midtrans notification invalid signature', [
                'order_id' => $orderId,
                'expected' => substr($expectedSignature, 0, 20) . '...',
                'received' => substr($signatureKey ?? '', 0, 20) . '...',
            ]);
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $pesanan = Pesanan::where('no_pesanan', $orderId)->first();

        if (!$pesanan) {
            Log::warning('Midtrans notification: order not found', ['order_id' => $orderId]);
            return response()->json(['message' => 'Order not found'], 404);
        }

        $pembayaran = Pembayaran::where('pesanan_id', $pesanan->id)->first();

        if (!$pembayaran) {
            Log::warning('Midtrans notification: payment record not found', ['order_id' => $orderId]);
            return response()->json(['message' => 'Payment record not found'], 404);
        }

        $transactionStatus = $request->transaction_status;
        $statusPembayaran = $this->mapPaymentStatus($transactionStatus);

        Log::info('Midtrans notification processing', [
            'order_id' => $orderId,
            'transaction_status' => $transactionStatus,
            'mapped_status' => $statusPembayaran,
        ]);

        $this->updateOrderStatus(
            $pesanan,
            $pembayaran,
            $statusPembayaran,
            $transactionStatus,
            $request->transaction_id,
            $request->payment_type,
            $request->all()
        );

        return response()->json(['message' => 'Notification processed']);
    }
}
