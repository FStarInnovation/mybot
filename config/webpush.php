<?php

return [
    /*
    |--------------------------------------------------------------------------
    | VAPID Configuration
    |--------------------------------------------------------------------------
    |
    | VAPID (Voluntary Application Server Identification) is a protocol
    | for secure Web Push subscriptions. These keys are used to identify
    | your application server to push services.
    */
    'vapid' => [
        // The subject should be a URL or a 'mailto:' email address for your application
        'subject' => env('APP_URL', 'https://farmabot.local'),
        
        // Public key for VAPID (from .env)
        'public_key' => env('VAPID_PUBLIC_KEY'),
        
        // Private key for VAPID (from .env) - keep this secure
        'private_key' => env('VAPID_PRIVATE_KEY'),
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Default Notification Options
    |--------------------------------------------------------------------------
    |
    | Default options for push notifications.
    */
    'default' => [
        // Time-to-live in seconds (how long the push service should retain the message)
        'TTL' => 2419200, // 28 days
        
        // Urgency: 'very-low', 'low', 'normal', or 'high'
        'urgency' => 'normal',
        
        // Topic for collapsible notifications
        'topic' => 'farmabot',
    ],
];
