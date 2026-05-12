<?php

namespace App\Mail;

use App\Models\CollectionSlot;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class OrderConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public User $user;

    public Collection $orders;

    public float $combinedTotal;

    public ?CollectionSlot $slot;

    public ?string $paypalTxnId;

    public string $currency;

    public string $currencySymbol;

    public function __construct(
        User $user,
        Collection $orders,
        float $combinedTotal,
        ?CollectionSlot $slot,
        ?string $paypalTxnId,
        string $currency
    ) {
        $this->user = $user;
        $this->orders = $orders;
        $this->combinedTotal = $combinedTotal;
        $this->slot = $slot;
        $this->paypalTxnId = $paypalTxnId;
        $this->currency = $currency;
        $this->currencySymbol = $this->resolveCurrencySymbol($currency);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Order Confirmation - Click&Collect',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.order-confirmation',
        );
    }

    private function resolveCurrencySymbol(string $currency): string
    {
        $symbols = [
            'USD' => '$',
        ];

        return $symbols[$currency] ?? $currency.' ';
    }
}
