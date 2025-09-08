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
];