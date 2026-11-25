<?php

namespace App\Mail;

use App\Models\Sale;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SaleReceipt extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Sale $sale)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your POS sale #' . $this->sale->id
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'sales.email'
        );
    }
}