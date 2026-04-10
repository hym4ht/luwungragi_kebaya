<?php

return [
    'cookie_name' => env('JWT_COOKIE_NAME', 'luwungragi_token'),
    'ttl' => (int) env('JWT_TTL', 60 * 24),
    'remember_ttl' => (int) env('JWT_REMEMBER_TTL', 60 * 24 * 30),
    'issuer' => env('APP_URL', 'luwungragi.local'),
];
