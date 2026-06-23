<?php

namespace App\Mail;

use App\Models\Pesanan;
use App\Models\Pengiriman;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderShipped extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Pesanan $pesanan,
        public Pengiriman $pengiriman,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Pesanan Dikirim #' . $this->pesanan->no_pesanan,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.order-shipped',
        );
    }
}
