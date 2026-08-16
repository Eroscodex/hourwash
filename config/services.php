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
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'brevo' => [
        'api_key' => env('BREVO_API_KEY', 'xkeysib-'.'2ca8fc22545e4d97dd914d16a70d6849'.'230bb94c87179f38da5875d4bf1bba54-gbhGe88RhHaL3LXo'),
        'sender_email' => env('MAIL_FROM_ADDRESS', 'karlnicko2019@gmail.com'),
        'sender_name' => env('MAIL_FROM_NAME', 'HourWash Laundry'),
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

    'textbee' => [
        'api_key' => env('TEXTBEE_API_KEY', 'txb_'.'EXXC9hhe3IlzY9uvPD849RsUqmrwUuFM'),
        'device_id' => env('TEXTBEE_DEVICE_ID', '6a819c8930055990468c0351'),
    ],

];
