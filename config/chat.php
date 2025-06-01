
<?php

return [
    // TTL for Redis chat history (seconds). 1 day by default.
    'history_ttl' => env('CHAT_HISTORY_TTL', 86400),
];
