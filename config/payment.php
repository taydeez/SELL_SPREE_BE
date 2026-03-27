<?php

return [

    'default' => env('PAYMENT_PROVIDER', 'paystack'),

    'currency' => env('PAYMENT_CURRENCY', 'NGN'),

    'platform_fee_percent' => (int) env('PLATFORM_FEE_PERCENT', 10),

    'paystack' => [
        'public_key' => env('PAYSTACK_PUBLIC_KEY'),
        'secret_key' => env('PAYSTACK_SECRET_KEY'),
        'base_url' => 'https://api.paystack.co',
        'webhook_secret' => env('PAYSTACK_WEBHOOK_SECRET'),
    ],

    'flutterwave' => [
        'public_key' => env('FLUTTERWAVE_PUBLIC_KEY'),
        'secret_key' => env('FLUTTERWAVE_SECRET_KEY'),
        'base_url' => 'https://api.flutterwave.com/v3',
        'webhook_secret' => env('FLUTTERWAVE_WEBHOOK_SECRET'),
    ],

];
