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

    'google' => [
        'client_id'     => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect'      => env('GOOGLE_REDIRECT_URI'),
    ],

    'vehicle_api' => [
        'key' => env('VEHICLE_API_KEY'),
        'base_url' => env('VEHICLE_API_BASE_URL', 'https://api.vehicledatabases.com'),
    ],

    'flutterwave' => [
    'public_key' => env('FLW_PUBLIC_KEY'),
    'secret_key' => env('FLW_SECRET_KEY'),
    'encryption_key' => env('FLW_ENCRYPTION_KEY'),
    ],

    'tinymce' => [
    'key' => env('TINYMCE_API_KEY'),
    ],

    'intouch' => [
    'base_url'         => env('INTOUCH_BASE_URL', 'https://www.intouchpay.co.rw/api'),
    'username'         => env('INTOUCH_USERNAME'),
    'account_no'       => env('INTOUCH_ACCOUNT_NO'),
    'partner_password' => env('INTOUCH_PARTNER_PASSWORD'),
    ],

];
