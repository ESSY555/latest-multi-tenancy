<?php

require __DIR__ . '/bootstrap/app.php';

$app = require __DIR__ . '/bootstrap/app.php';

$user = \App\Models\User::where('email', 'admin@Bezaleel.test')->first();

if ($user) {
    echo "✓ User found!\n";
    echo "Email: " . $user->email . "\n";
    echo "Name: " . $user->name . "\n";
    echo "Super Admin: " . ($user->is_super_admin ? 'Yes' : 'No') . "\n";
} else {
    echo "✗ User not found!\n";
}
