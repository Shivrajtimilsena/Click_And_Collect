<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ForgotPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public User $user;

    public string $token;

    public string $resetUrl;

    public function __construct(User $user, string $token)
    {
        $this->user = $user;
        $this->token = $token;
        $this->resetUrl = route('password.reset', ['token' => $token, 'email' => $user->email]);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Reset Your Password - Click&Collect',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.forgot-password',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
