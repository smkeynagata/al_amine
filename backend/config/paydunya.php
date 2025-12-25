<?php

return [
    'mode' => env('PAYDUNYA_MODE', 'sandbox'), // sandbox | live
    'master_key' => env('PAYDUNYA_MASTER_KEY'),
    'private_key' => env('PAYDUNYA_PRIVATE_KEY'),
    'public_key' => env('PAYDUNYA_PUBLIC_KEY'),
    'token' => env('PAYDUNYA_TOKEN'),
    'account_alias' => env('PAYDUNYA_ACCOUNT_ALIAS'),

    'currency' => env('PAYDUNYA_CURRENCY', 'XOF'),

    'ipn_url' => env('PAYDUNYA_IPN_URL'),
    'return_url' => env('PAYDUNYA_RETURN_URL'),
    'cancel_url' => env('PAYDUNYA_CANCEL_URL'),

    'timeout' => env('PAYDUNYA_TIMEOUT', 15),

    // Nom et coordonnées de la structure
    'store' => [
        'name' => env('PAYDUNYA_STORE_NAME', env('APP_NAME', 'Al-Amine')),
        'tagline' => env('PAYDUNYA_STORE_TAGLINE', 'Plateforme de santé'),
        'phone' => env('PAYDUNYA_STORE_PHONE'),
        'postal_address' => env('PAYDUNYA_STORE_ADDRESS'),
        'website_url' => env('PAYDUNYA_STORE_WEBSITE', env('APP_URL')),
        'logo_url' => env('PAYDUNYA_STORE_LOGO'),
    ],
];
