<?php

namespace App\Mail;

use App\Models\Shipment;
use App\Models\TrackingUpdate as TrackingUpdateModel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TrackingUpdate extends Mailable
{
    use Queueable, SerializesModels;

    public Shipment $shipment;
    public TrackingUpdateModel $update;
    public string $trackingUrl;

    /**
     * Create a new message instance.
     */
    public function __construct(Shipment $shipment, TrackingUpdateModel $update)
    {
        $this->shipment = $shipment;
        $this->update = $update;
        $this->trackingUrl = route('tracking.index');
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Shipment Update - ' . $this->shipment->tracking_id . ' is ' . ucfirst(str_replace('_', ' ', $this->update->status)),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.tracking-update',
            with: [
                'shipment' => $this->shipment,
                'update' => $this->update,
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
