<?php

return [
    // One-time key gating the temporary /__migrate route in routes/web.php.
    // Set in production .env only — never commit the real value.
    'migrate_key' => env('MIGRATE_KEY'),
];
