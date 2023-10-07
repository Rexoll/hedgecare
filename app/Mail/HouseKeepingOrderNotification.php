<?php

namespace App\Mail;

use App\Models\HousekeepingOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class HousekeepingOrderNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(public HousekeepingOrder $order)
    {
        //
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'Order Notification',
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
            view: 'emails.order-notification',
            with: [
                "order_buyer_name" => Auth::user()->first_name . ' ' . Auth::user()->last_name,
                "service" => 'Housekeeping',
                "order_hours" => $this->order->to_hour - $this->order->from_hour,
                "order_date" => $this->order->created_at->format('F j, Y \a\t g A'),
                "email" => Auth::user()->email,
                "phone" => Auth::user()->phone_number
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
