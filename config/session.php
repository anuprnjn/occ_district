<?php

use Illuminate\Support\Str;

return [

    'driver' => env('SESSION_DRIVER', 'database'), // Database session driver ensures persistence

    'lifetime' => env('SESSION_LIFETIME', 180), // Increase session lifetime to 180 minutes

    'expire_on_close' => false, // Ensures session stays active even after browser is closed

    'encrypt' => true, // Encrypt session data for better security

    'files' => storage_path('framework/sessions'),

    'connection' => env('SESSION_CONNECTION', null),

    'table' => env('SESSION_TABLE', 'sessions'),

    'store' => env('SESSION_STORE', null),

    'lottery' => [2, 100],

    'cookie' => env(
        'SESSION_COOKIE',
        Str::slug(env('APP_NAME', 'laravel'), '_') . '_session'
    ),

    'path' => '/',

    'domain' => env('SESSION_DOMAIN', null), // Ensure session works across subdomains

    'secure' => env('SESSION_SECURE_COOKIE', true), // Ensures session is only sent over HTTPS

    'http_only' => true, // Prevent JavaScript from accessing the session cookie

    'same_site' => 'lax', // Strongest security for cross-site requests

    'partitioned' => env('SESSION_PARTITIONED_COOKIE', false),

];