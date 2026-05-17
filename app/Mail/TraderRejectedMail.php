<?php

namespace App\Mail;

use App\Models\TraderApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TraderRejectedMail extends Mailable
{
    use Queueable, SerializesModels;

    public TraderApplication $application;

    public function __construct(TraderApplication $application)
    {
        $this->application = $application;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Trader Application Status',
            from: new Address('clickandcollect.vendor@gmail.com', 'Click&Collect'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.trader-rejected',
        );
    }
}
