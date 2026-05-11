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
        $response = Http::timeout(30)
            ->withBasicAuth($this->clientId, $this->secret)
            ->asForm()
            ->post($this->baseUrl.'/v1/oauth2/token', [
                'grant_type' => 'client_credentials',
            ]);

        if (! $response->successful()) {
            $errorBody = $response->json();
            Log::error('PayPal auth failed', [
                'status' => $response->status(),
                'response' => $errorBody,
                'client_id' => $this->clientId,
            ]);
            
            $errorMessage = $errorBody['error_description'] ?? $errorBody['message'] ?? 'Unknown error';
            throw new \Exception('PayPal authentication failed: '.$errorMessage);
        }

        $token = $response->json('access_token');
        if (! $token) {
            Log::error('PayPal token missing', ['response' => $response->json()]);
            throw new \Exception('PayPal failed to return access token.');
        }

        return $token;
    }

    public function createOrder(float $total, string $currency = 'GBP', array $items = []): array
    {
        $token = $this->getAccessToken();

        $payload = [
            'intent' => 'CAPTURE',
            'purchase_units' => [
                [
                    'reference_id' => 'default',
                    'description' => 'Click and Collect Order',
                    'amount' => [
                        'currency_code' => $currency,
                        'value' => number_format($total, 2, '.', ''),
                        'breakdown' => [
                            'item_total' => [
                                'currency_code' => $currency,
                                'value' => number_format($total, 2, '.', ''),
                            ],
                        ],
                    ],
                ],
            ],
            'application_context' => [
                'return_url' => route('paypal.capture'),
                'cancel_url' => route('orders.checkout'),
                'brand_name' => 'Click and Collect',
                'locale' => 'en-GB',
                'user_action' => 'PAY_NOW',
                'shipping_preference' => 'NO_SHIPPING',
            ],
        ];

        // Add items if provided
        if (!empty($items)) {
            $payload['purchase_units'][0]['items'] = $items;
        }

        $response = Http::timeout(30)
            ->withToken($token)
            ->withHeader('Content-Type', 'application/json')
            ->post($this->baseUrl.'/v2/checkout/orders', $payload);

        if (! $response->successful()) {
            $errorBody = $response->json();
            Log::error('PayPal create order failed', [
                'status' => $response->status(),
                'body' => $errorBody,
                'amount' => $total,
                'currency' => $currency,
                'payload' => $payload,
            ]);
            
            $errorMessage = $errorBody['message'] ?? $errorBody['error_description'] ?? 'Unknown error';
            throw new \Exception('Failed to create PayPal order: '.$errorMessage);
        }

        $data = $response->json();
        if (! isset($data['id'])) {
            Log::error('PayPal order missing ID', ['response' => $data]);
            throw new \Exception('PayPal order response missing order ID.');
        }

        return $data;
    }

    public function getOrder(string $paypalOrderId): array
    {
        $token = $this->getAccessToken();

        $response = Http::timeout(30)
            ->withToken($token)
            ->withHeader('Content-Type', 'application/json')
            ->get($this->baseUrl."/v2/checkout/orders/{$paypalOrderId}");

        if (! $response->successful()) {
            $errorBody = $response->json();
            Log::error('PayPal get order failed', [
                'order_id' => $paypalOrderId,
                'status' => $response->status(),
                'body' => $errorBody,
            ]);
            
            $errorMessage = $errorBody['message'] ?? $errorBody['error_description'] ?? 'Unknown error';
            throw new \Exception('Failed to get PayPal order: '.$errorMessage);
        }

        return $response->json();
    }

    public function captureOrder(string $paypalOrderId): array
    {
        $token = $this->getAccessToken();

        Log::info('PayPal capture request', [
            'order_id' => $paypalOrderId,
        ]);

        $response = Http::timeout(30)
            ->withToken($token)
            ->withHeader('Content-Type', 'application/json')
            ->post($this->baseUrl."/v2/checkout/orders/{$paypalOrderId}/capture", (object) []);

        Log::info('PayPal capture response', [
            'order_id' => $paypalOrderId,
            'status' => $response->status(),
            'body' => $response->json(),
        ]);

        if (! $response->successful()) {
            $errorBody = $response->json();
            Log::error('PayPal capture failed', [
                'order_id' => $paypalOrderId,
                'status' => $response->status(),
                'body' => $errorBody,
            ]);
            
            $errorMessage = $errorBody['message'] ?? $errorBody['error_description'] ?? 'Unknown error';
            $details = isset($errorBody['details'][0]['description']) ? ' - ' . $errorBody['details'][0]['description'] : '';
            throw new \Exception('Failed to capture PayPal payment: ' . $errorMessage . $details);
        }

        return $response->json();
    }

    /**
     * Test the PayPal connection and credentials
     */
    public function testConnection(): array
    {
        try {
            $token = $this->getAccessToken();
            
            return [
                'status' => 'success',
                'message' => 'PayPal connection successful',
                'base_url' => $this->baseUrl,
                'mode' => config('paypal.mode'),
            ];
        } catch (\Exception $e) {
            Log::error('PayPal connection test failed', ['error' => $e->getMessage()]);
            
            return [
                'status' => 'error',
                'message' => $e->getMessage(),
                'base_url' => $this->baseUrl,
                'mode' => config('paypal.mode'),
            ];
        }
    }
}
