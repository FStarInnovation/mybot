<?php

return [
    App\Providers\AppServiceProvider::class,
    // Horizon disabled for local development to avoid Redis requirement
    // App\Providers\HorizonServiceProvider::class,
    App\Providers\TelescopeServiceProvider::class,
];
