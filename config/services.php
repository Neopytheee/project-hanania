<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Resend, Postmark, AWS, and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'midtrans' => [
        'server_key' => env('MIDTRANS_SERVER_KEY'),
        'client_key' => env('MIDTRANS_CLIENT_KEY'),
        'is_production' => env('MIDTRANS_IS_PRODUCTION', false),
        'notification_url' => env('MIDTRANS_NOTIFICATION_URL'),
        'snap_url' => env(
            'MIDTRANS_SNAP_URL',
            env('MIDTRANS_IS_PRODUCTION', false)
                ? 'https://app.midtrans.com/snap/snap.js'
                : 'https://app.sandbox.midtrans.com/snap/snap.js'
        ),
        'expiry_duration' => env('MIDTRANS_EXPIRY_DURATION', 5),
        'expiry_unit' => env('MIDTRANS_EXPIRY_UNIT', 'minute'),
        'default_fee_rate' => env('MIDTRANS_DEFAULT_FEE_RATE', 0.008),
        'ppn_rate' => env('MIDTRANS_PPN_RATE', 0.0),
        'fee_rates' => [
            'qris' => env('MIDTRANS_FEE_QRIS', 0.008),
            'bank_transfer' => env('MIDTRANS_FEE_BANK_TRANSFER', 0.008),
            'bca_va' => env('MIDTRANS_FEE_BCA_VA', 0.008),
            'bni_va' => env('MIDTRANS_FEE_BNI_VA', 0.008),
            'bri_va' => env('MIDTRANS_FEE_BRI_VA', 0.008),
            'permata_va' => env('MIDTRANS_FEE_PERMATA_VA', 0.008),
            'gopay' => env('MIDTRANS_FEE_GOPAY', 0.008),
            'shopeepay' => env('MIDTRANS_FEE_SHOPEEPAY', 0.008),
            'echannel' => env('MIDTRANS_FEE_ECHANNEL', 0.008),
            'alfamart' => env('MIDTRANS_FEE_ALFAMART', 0.008),
        ],
    ],

    'fonnte' => [
        'token' => env('FONNTE_TOKEN'),
        'endpoint' => env('FONNTE_ENDPOINT', 'https://api.fonnte.com/send'),
    ],

    'whatsapp' => [
        'web_url' => env('WHATSAPP_WEB_URL', 'https://wa.me'),
    ],

    'google_maps' => [
        'default_url' => env('GOOGLE_MAPS_DEFAULT_URL', 'https://maps.google.com/?q=Gedung+Senayan'),
    ],

    'gemini' => [
        'api_key' => env('GEMINI_API_KEY'),
        'endpoint' => env('GEMINI_ENDPOINT', 'https://generativelanguage.googleapis.com/v1beta/models/gemini-3.5-flash-lite:generateContent'),
    ],

    'seed' => [
        'default_password' => env('SEED_DEFAULT_PASSWORD') ?: 'password',
    ],

];
