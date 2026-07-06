<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderPaidInvoice extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Order $order) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Invoice for Your Order #' . $this->order->id,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.orders.order-paid',
        );
    }

    public function attachments(): array
    {
        return [
            /**
             * FIX: Generate the PDF internal to the closure.
             * This prevents Serialization errors when using Laravel Queues.
             */
            Attachment::fromData(function () {
                return Pdf::loadView('pdf.invoice', ['order' => $this->order])->output();
            }, 'Invoice-ASL-' . $this->order->id . '.pdf')
            ->withMime('application/pdf'),
        ];
    }
}