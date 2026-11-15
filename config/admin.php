<?php

// config/admin.php  (new file)
return [
    // The single password required to open the admin register page.
    // Set ADMIN_REGISTER_PASSWORD in .env. If empty, registration stays locked.
    'register_password' => env('ADMIN_REGISTER_PASSWORD'),
];
