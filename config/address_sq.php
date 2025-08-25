<?php

return [
  /*
    |--------------------------------------------------------------------------
    | Address SQ API Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for the Address Service Qualification API integration
    |
    */

  'base_url' => env('ADDRESS_SQ_BASE_URL', 'https://extranet.asmorphic.com'),

  'credentials' => [
    'username' => env('ADDRESS_SQ_USERNAME', 'project-test@projecttest.com.au'),
    'password' => env('ADDRESS_SQ_PASSWORD', 'oxhyV9NzkZ^02MEB'),
  ],

  'default_company_id' => env('ADDRESS_SQ_COMPANY_ID', 17),
  'default_service_type_id' => env('ADDRESS_SQ_SERVICE_TYPE_ID', 3),

  /*
    |--------------------------------------------------------------------------
    | API Endpoints
    |--------------------------------------------------------------------------
    */

  'endpoints' => [
    'login' => '/api/login',
    'find_address' => '/api/orders/findaddress',
    'qualify' => '/api/orders/qualify',
  ],

  /*
    |--------------------------------------------------------------------------
    | Request Timeout
    |--------------------------------------------------------------------------
    */

  'timeout' => env('ADDRESS_SQ_TIMEOUT', 30),

  /*
    |--------------------------------------------------------------------------
    | Logging
    |--------------------------------------------------------------------------
    */

  'log_requests' => env('ADDRESS_SQ_LOG_REQUESTS', true),
  'log_responses' => env('ADDRESS_SQ_LOG_RESPONSES', true),
];
