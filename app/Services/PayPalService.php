<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PayPalService
{
    protected string $baseUrl;

    protected string $clientId;

    protected string $secret;

    public function __construct()
    {
        $this->clientId = config('paypal.client_id');
        $this->secret = config('paypal.secret');
        $this->baseUrl = config('paypal.base_url');
    }

    public function getAccessToken(): string
    {
        $response = Http::withBasicAuth($this->clientId, $this->secret)
            ->asForm()
            ->post($this->baseUrl.'/v1/oauth2/token', [
                'grant_type' => 'client_credentials',
            ]);

        if (! $response->successful()) {
            Log::error('PayPal auth failed', ['response' => $response->body()]);
            throw new \Exception('Failed to authenticate with PayPal.');
        }

        return $response->json('access_token');
    }

    public function createOrder(float $total, string $currency = 'GBP'): array
    {
        $token = $this->getAccessToken();

        $response = Http::withToken($token)
            ->withHeader('Content-Type', 'application/json')
            ->post($this->baseUrl.'/v2/checkout/orders', [
                'intent' => 'CAPTURE',
                'purchase_units' => [
                    [
                        'amount' => [
                            'currency_code' => $currency,
                            'value' => number_format($total, 2, '.', ''),
                        ],
                    ],
                ],
            ]);

        if (! $response->successful()) {
            Log::error('PayPal create order failed', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            throw new \Exception('Failed to create PayPal order.');
        }

        return $response->json();
    }

    public function captureOrder(string $paypalOrderId): array
    {
        $token = $this->getAccessToken();

        $response = Http::withToken($token)
            ->withHeader('Content-Type', 'application/json')
            ->post($this->baseUrl."/v2/checkout/orders/{$paypalOrderId}/capture");

        if (! $response->successful()) {
            Log::error('PayPal capture failed', [
                'order_id' => $paypalOrderId,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            throw new \Exception('Failed to capture PayPal payment.');
        }

        return $response->json();
    }
}
