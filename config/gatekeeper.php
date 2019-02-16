<?php

return [
    /**
     * Login credentials
     */
    'username' => env('GATE_USER', 'username'),
    'password' => env('GATE_PASS', 'password'),

    /**
     * View blade with the login form
     */
    'authview' => env('GATE_VIEW', 'login'),
    'authtime' => env('GATE_TIME', 86400)
];
