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

    'sync' => [
        'enabled'     => env('SYNC_ENABLED', false),
        'cloud_url'   => env('SYNC_CLOUD_URL', 'http://146.235.224.99/RestPOS/api/sync/ingest'),
        'verify_url'  => env('SYNC_VERIFY_URL', 'http://146.235.224.99/RestPOS/api/sync/verify-license'),
        'token'       => env('SYNC_TOKEN', env('LICENSE_KEY', 'restpos_sync_default_token')),
        'tenant_slug' => env('SYNC_TENANT_SLUG', null),
    ],

];
