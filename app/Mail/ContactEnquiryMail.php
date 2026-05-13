<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactEnquiryMail extends Mailable
{
    use Queueable, SerializesModels;

    public array $enquiry;

    public function __construct(array $enquiry)
    {
        $this->enquiry = $enquiry;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New contact enquiry: '.$this->enquiry['subject'],
            replyTo: [
                new Address($this->enquiry['email'], $this->enquiry['name']),
            ],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.contact-enquiry',
        );
    }
}
