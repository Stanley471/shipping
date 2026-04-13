<?php

namespace App\Mail;

use App\Models\CoinPurchase;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminDepositNotification extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public CoinPurchase $purchase;
    public User $user;

    /**
     * Create a new message instance.
     */
    public function __construct(CoinPurchase $purchase)
    {
        $this->purchase = $purchase;
        $this->user = $purchase->user;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Deposit Request - ₦' . number_format($this->purchase->amount_naira) . ' from ' . $this->user->name,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.admin.new-deposit',
            with: [
                'purchase' => $this->purchase,
                'user' => $this->user,
                'adminUrl' => route('admin.coins.purchases'),
            ],
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
