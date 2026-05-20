<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var Order
     */
    public $order;

    /**
     * @var array
     */
    public $cart;

    /**
     * @var float
     */
    public $totalAmount;

    /**
     * Create a new message instance.
     *
     * @param Order $order
     * @param array $cart
     * @param float $totalAmount
     */
    public function __construct(Order $order, array $cart, float $totalAmount)
    {
        $this->order = $order;
        $this->cart = $cart;
        $this->totalAmount = $totalAmount;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Order Confirmation - HomeNest Furniture #' . str_pad($this->order->id, 6, '0', STR_PAD_LEFT),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.order-confirmation',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}