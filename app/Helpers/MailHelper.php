<?php

namespace App\Helpers;

use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Mail;

class MailHelper
{
    /**
     * Send mail based on configured mode (sync or queue)
     *
     * @param string $to
     * @param Mailable $mailable
     * @return void
     */
    public static function send(string $to, Mailable $mailable): void
    {
        if (config('mail.send_mode') === 'queue') {
            Mail::to($to)->queue($mailable);
        } else {
            Mail::to($to)->send($mailable);
        }
    }

    /**
     * Send mail to multiple recipients based on configured mode
     *
     * @param array $recipients
     * @param Mailable $mailable
     * @return void
     */
    public static function sendToMany(array $recipients, Mailable $mailable): void
    {
        foreach ($recipients as $recipient) {
            if (is_object($recipient) && isset($recipient->email)) {
                self::send($recipient->email, $mailable);
            } elseif (is_string($recipient)) {
                self::send($recipient, $mailable);
            }
        }
    }
}
