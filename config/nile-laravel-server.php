<?php

// config for JGamboa/NileLaravelServer

// config has no effect at the moment
return [
    'base_url' => env('NILE_API_BASE_URL', 'https://global.thenile.dev'),
    'token' => env('NILE_API_TOKEN'),
    'session_strategy' => env('NILE_SESSION_STRATEGY', 'database'), // 'jwt' o 'database'
    'session_max_age' => env('NILE_SESSION_MAX_AGE', 60 * 60 * 24 * 30), // 30 días en segundos
    'session_update_age' => env('NILE_SESSION_UPDATE_AGE', 60 * 60), // 1 hora en segundos

    'jwt_secret' => env('NILE_JWT_SECRET'),
    'jwt_algorithm' => env('NILE_JWT_ALGORITHM', 'HS256'),
    'jwt_salt' => env('NILE_JWT_SALT'),

    'proxy_routes' => [
        'csrf' => env('NILE_AUTH_CSRF_PROXY', 'https://auth.nile.dev/csrf'),
        'signin' => env('NILE_AUTH_SIGNIN_PROXY', 'https://auth.nile.dev/signin'),
        'signout' => env('NILE_AUTH_SIGNOUT_PROXY', 'https://auth.nile.dev/signout'),
        'session' => env('NILE_AUTH_SESSION_PROXY', 'https://auth.nile.dev/session'),
        'error' => env('NILE_AUTH_ERROR_PROXY', 'https://auth.nile.dev/error'),
        'verify_request' => env('NILE_AUTH_VERIFY_PROXY', 'https://auth.nile.dev/verify-request'),
        'password_reset' => env('NILE_AUTH_PASSWORD_RESET_PROXY', 'https://auth.nile.dev/password-reset'),
        'callback' => env('NILE_AUTH_CALLBACK_PROXY', 'https://auth.nile.dev/callback'),
        'providers' => env('NILE_AUTH_PROVIDERS_PROXY', 'https://auth.nile.dev/providers'),
        'signup' => env('NILE_AUTH_SIGNUP_PROXY', 'https://auth.nile.dev/signup'),
    ],
];
