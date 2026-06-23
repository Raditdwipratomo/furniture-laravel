<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Models\Pengiriman;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Pesanan::with('user');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('q')) {
            $query->where(function ($q) use ($request) {
                $q->where('no_pesanan', 'like', '%' . $request->q . '%')
                  ->orWhereHas('user', function ($q2) use ($request) {
                      $q2->where('nama', 'like', '%' . $request->q . '%');
                  });
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('tanggal_pesanan', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('tanggal_pesanan', '<=', $request->date_to);
        }

        $pesanans = $query->latest('tanggal_pesanan')->paginate(15)->withQueryString();

        return view('admin.orders.index', compact('pesanans'));
    }

    public function show(Pesanan $order)
    {
        $order->load(['user', 'detail.produk', 'pengiriman', 'pembayaran']);

        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Pesanan $order)
    {
        $request->validate([
            'status' => 'required|in:pending,dibayar,diproses,dikirim,selesai,dibatalkan',
            'no_resi' => 'required_if:status,dikirim|nullable|string|max:255',
            'kurir' => 'nullable|string|max:255',
        ]);

        $order->update(['status' => $request->status]);

        if ($request->status === 'dikirim') {
            Pengiriman::updateOrCreate(
                ['pesanan_id' => $order->id],
                [
                    'no_resi' => $request->no_resi,
                    'kurir' => $request->kurir ?? $order->pengiriman->kurir ?? '-',
                    'ongkir' => $order->ongkir,
                    'status_pengiriman' => 'dalam_pengiriman',
                    'tanggal_kirim' => now(),
                ]
            );
        } elseif ($request->status === 'selesai') {
            // Mark pengiriman as delivered
            if ($order->pengiriman) {
                $order->pengiriman->update(['status_pengiriman' => 'terkirim']);
            }
        }

        return redirect()->route('admin.orders.show', $order)
            ->with('success', 'Status pesanan berhasil diperbarui.');
    }

    public function destroy(Request $request, Pesanan $order)
    {
        $order->update([
            'status' => 'dibatalkan',
            'catatan' => ($order->catatan ? $order->catatan . "\n" : '') . 'Alasan pembatalan: ' . ($request->reason ?? 'Dibatalkan oleh admin'),
        ]);

        return redirect()->route('admin.orders.index')
            ->with('success', 'Pesanan berhasil dibatalkan.');
    }
}
