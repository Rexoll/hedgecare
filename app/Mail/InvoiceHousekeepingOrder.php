<?php

namespace App\Mail;

use App\Models\HousekeepingOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InvoiceHousekeepingOrder extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(public HousekeepingOrder $order)
    {
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'Invoice Housekeeping Order',
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            view: 'emails.invoice',
            with: [
                "order_id" => $this->order->id,
                "order_buyer_name" => $this->order->first_name,
                "order_category" => $this->order->category->name,
                "order_hours" => $this->order->expected_hour,
                "order_sub_total" => $this->order->sub_total,
                "order_tax" => $this->order->tax,
                "order_buyer_address" => $this->order->street_address,
                "order_seller_address" => "South San Francisco, 354 Oyster Point Blvd",
                "order_date" => $this->order->created_at->format('F j, Y \a\t g A'),
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}
