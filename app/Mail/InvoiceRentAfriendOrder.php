<?php

namespace App\Mail;

use App\Models\rentAfriendOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InvoiceRentAfriendOrder extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(public rentAfriendOrder $order, public $suffix_card_number)
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
            subject: 'Invoice Rent A friend Order',
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
                "order_category" => 'Rent a friend',
                "order_hours" => $this->order->to_hour - $this->order->from_hour,
                "order_sub_total" => $this->order->sub_total,
                "order_tax" => $this->order->tax,
                "order_buyer_address" => $this->order->street_address,
                "order_seller_address" => "South San Francisco, 354 Oyster Point Blvd",
                "order_date" => $this->order->created_at->format('F j, Y \a\t g A'),
                "suffix_card_number" => $this->suffix_card_number,
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
