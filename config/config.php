<?php

/*
 * You can place your custom package configuration in here.
 */
return [
    'cache' => [
        // Enable/disable caching fallback and writes
        'enabled' => env('VAT_CHECKER_CACHE', true),

        // Time To Live for cached entries (in seconds)
        // Set to 0 for no expiration (cache forever)
        'ttl' => env('VAT_CHECKER_CACHE_TTL', 86400), // 24 hours, 0 = forever
    ],

    'notifications' => [
        // Enable/disable notifications on VIES connection errors
        'enabled' => env('VAT_CHECKER_NOTIFICATIONS', false),

        'mail' => [
            // Enable mail channel
            'enabled' => env('VAT_CHECKER_NOTIFICATIONS_MAIL', false),

            // Recipient email address (comma-separated accepted by Mail::to)
            'to' => env('VAT_CHECKER_NOTIFICATIONS_MAIL_TO', ''),

            // Optional from address and name
            'from_address' => env('VAT_CHECKER_NOTIFICATIONS_MAIL_FROM', ''),
            'from_name' => env('VAT_CHECKER_NOTIFICATIONS_MAIL_FROM_NAME', 'Laravel VAT Checker'),

            // Subject
            'subject' => env('VAT_CHECKER_NOTIFICATIONS_MAIL_SUBJECT', 'VAT Checker: VIES connection error'),
        ],
    ],

    'ch' => [
        // Enable external validation for Switzerland (CH) via HTTP API
        'external' => [
            'enabled' => env('VAT_CHECKER_CH_EXTERNAL', false),
            // Base URL of the external service (must return JSON)
            'url' => env('VAT_CHECKER_CH_EXTERNAL_URL', ''),
            // Optional API key/header name
            'api_key' => env('VAT_CHECKER_CH_EXTERNAL_API_KEY', ''),
            'api_key_header' => env('VAT_CHECKER_CH_EXTERNAL_API_KEY_HEADER', 'Authorization'),
            // Timeout seconds
            'timeout' => env('VAT_CHECKER_CH_EXTERNAL_TIMEOUT', 10),
        ],
    ],

    'rates_api' => [
        // Enable package routes to expose VAT rates API
        'enabled' => env('VAT_CHECKER_RATES_API', false),
        // Base route prefix
        'prefix' => env('VAT_CHECKER_RATES_PREFIX', 'vat-checker/v1'),
        // Source of rates data: 'config' (embedded) for now
        'source' => 'config',
    ],

    // Embedded VAT rates data (subset, extend as needed)
    'rates' => [
        'IT' => [
            'standard' => ['rate' => 22.0, 'note' => 'Standard'],
            'reduced' => [
                ['rate' => 10.0, 'note' => 'Food, restaurants'],
                ['rate' => 5.0, 'note' => 'Specific goods/services'],
            ],
            'super_reduced' => [ ['rate' => 4.0, 'note' => 'Books, medical devices'] ],
            'zero' => [ ['rate' => 0.0, 'note' => 'Exports, intra-EU supply'] ],
        ],
        'DE' => [
            'standard' => ['rate' => 19.0, 'note' => 'Standard'],
            'reduced' => [ ['rate' => 7.0, 'note' => 'Food, books, transport'] ],
            'zero' => [ ['rate' => 0.0, 'note' => 'Exports'] ],
        ],
        'FR' => [
            'standard' => ['rate' => 20.0],
            'reduced' => [
                ['rate' => 10.0, 'note' => 'Food services'],
                ['rate' => 5.5, 'note' => 'Basic necessities'],
            ],
            'super_reduced' => [ ['rate' => 2.1, 'note' => 'Press, medicines'] ],
            'zero' => [ ['rate' => 0.0] ],
        ],
        'GB' => [
            'standard' => ['rate' => 20.0],
            'reduced' => [ ['rate' => 5.0, 'note' => 'Home energy, child car seats'] ],
            'zero' => [ ['rate' => 0.0, 'note' => 'Food, children clothing'] ],
        ],
        'NO' => [
            'standard' => ['rate' => 25.0],
            'reduced' => [
                ['rate' => 15.0, 'note' => 'Food'],
                ['rate' => 12.0, 'note' => 'Passenger transport, cinema'],
            ],
            'zero' => [ ['rate' => 0.0] ],
        ],
        'CH' => [
            'standard' => ['rate' => 8.1],
            'reduced' => [ ['rate' => 2.6, 'note' => 'Food, books'] ],
            'special' => [ ['rate' => 3.8, 'note' => 'Accommodation (special)'] ],
            'zero' => [ ['rate' => 0.0] ],
        ],
    ],
];