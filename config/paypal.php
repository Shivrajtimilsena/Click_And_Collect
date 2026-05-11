<?php

return [
    'client_id' => env('PAYPAL_CLIENT_ID'),
    'secret' => env('PAYPAL_SECRET'),
    'mode' => env('PAYPAL_MODE', 'sandbox'),
    'currency' => env('PAYPAL_CURRENCY', 'GBP'),

    'base_url' => env('PAYPAL_MODE', 'sandbox') === 'live'
        ? 'https://api-m.paypal.com'
        : 'https://api-m.sandbox.paypal.com',
];
