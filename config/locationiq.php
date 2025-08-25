<?php

return [
    /*
    |--------------------------------------------------------------------------
    | LocationIQ API Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for LocationIQ Forward Geocoding API
    | https://docs.locationiq.com/docs/search-forward-geocoding
    |
    */

    'base_url' => env('LOCATIONIQ_BASE_URL', 'https://eu1.locationiq.com/v1'),

    'api_key' => env('LOCATIONIQ_API_KEY'),

    'timeout' => env('LOCATIONIQ_TIMEOUT', 30),

    'default_params' => [
        'format' => 'json',
        'limit' => env('LOCATIONIQ_DEFAULT_LIMIT', 10),
        'addressdetails' => 1,
        'extratags' => 1,
        'namedetails' => 1,
    ],

    'log_requests' => env('LOCATIONIQ_LOG_REQUESTS', false),
    'log_responses' => env('LOCATIONIQ_LOG_RESPONSES', false),
];
