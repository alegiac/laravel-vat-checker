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
];