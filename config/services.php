<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
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

    /*
    |--------------------------------------------------------------------------
    | KHQR Payment Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for KHQR (Khmer Quick Response) payment integration
    | with Bakong. This is Cambodia's national payment system.
    |
    */

    'khqr' => [
        'enabled' => env('KHQR_ENABLED', false),
        'api_token' => env('KHQR_API_TOKEN'),
        'account_id' => env('KHQR_ACCOUNT_ID'),
        'merchant_name' => env('KHQR_MERCHANT_NAME'),
        'merchant_city' => env('KHQR_MERCHANT_CITY'),
        // Note: Currency is hardcoded to KHR in the controller using KHQRData::CURRENCY_KHR constant
        'transaction_type' => env('KHQR_TRANSACTION_TYPE', 'PP'),
    ],

];
