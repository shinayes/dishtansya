<?php

/**
 * ===================================
 * Config file for Dishtansya
 * ===================================
 */
return [
    'user_status' => [
        'active' => 'ACTIVE',
        'inactive' => 'INACTIVE',
        'suspended' => 'SUSPENDED'
    ],

    'max_login_attempt' => env('MAX_LOGIN_ATTEMPT', 5),

];
