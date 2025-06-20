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

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'webhook' => [
        'site_url' => env('WEBHOOK_SITE_URL'),
    ],
    // sync service
    'sync_service' => [
        'host'        => env('SYNC_SERVICE_HOST', 'sync-app'),
        'port'        => env('SYNC_SERVICE_PORT', '9000'),
        'base_url'    => env('SYNC_SERVICE_BASE_URL', 'http://sync-app:9000'),
        'health_url'  => env('SYNC_SERVICE_HEALTH_URL', 'http://sync-app:9000/api/health'),
        'sync_url'    => env('SYNC_SERVICE_SYNC_URL', 'http://sync-app:9000/api/sync-user'),
    ],
];
