<?php

return [
    // Settings cache duration in seconds (default: 24 hours)
    'cache_expiration' => env('SETTINGS_CACHE_EXPIRATION', 86400),
    
    // Settings table name
    'table' => env('SETTINGS_TABLE', 'settings'),
    
    // Cache key for settings
    'cache_key' => env('SETTINGS_CACHE_KEY', 'app_settings'),
];