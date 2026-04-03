<?php

namespace App\Mail;

use App\Models\Shipment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ShipmentCreated extends Mailable
{
    use Queueable, SerializesModels;

    public Shipment $shipment;
    public string $trackingUrl;

    /**
     * Create a new message instance.
     */
    public function __construct(Shipment $shipment)
    {
        $this->shipment = $shipment;
        $this->trackingUrl = route('tracking.index');
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Shipment has been Created - ' . $this->shipment->tracking_id,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.shipment-created',
            with: [
                'shipment' => $this->shipment,
                'trackingUrl' => $this->trackingUrl,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
