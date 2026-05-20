<?php

namespace App\Mail;

use App\Models\Delivery;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DeliveryStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var Delivery
     */
    public $delivery;

    /**
     * @var string
     */
    public $oldStatus;

    /**
     * @var string
     */
    public $newStatus;

    /**
     * Create a new message instance.
     *
     * @param Delivery $delivery
     * @param string $oldStatus
     * @param string $newStatus
     */
    public function __construct(Delivery $delivery, string $oldStatus, string $newStatus)
    {
        $this->delivery = $delivery;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = match($this->newStatus) {
            'in_progress' => 'Your Order is Now Being Processed - HomeNest Furniture',
            'delivered' => 'Your Order Has Been Delivered - HomeNest Furniture',
            default => 'Delivery Status Update - HomeNest Furniture',
        };

        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.delivery-status',
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}