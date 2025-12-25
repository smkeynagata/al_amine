<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "\n🔑 Configuration PayDunya\n";
echo "========================\n\n";

echo "Mode: " . config('paydunya.mode') . "\n";
echo "Master Key: " . config('paydunya.master_key') . "\n";
echo "Private Key: " . config('paydunya.private_key') . "\n";
echo "Public Key: " . config('paydunya.public_key') . "\n";
echo "Token: " . config('paydunya.token') . "\n";
echo "Currency: " . config('paydunya.currency') . "\n\n";

echo "📍 URLs de callback:\n";
echo "IPN URL: " . config('paydunya.ipn_url') . "\n";
echo "Return URL: " . config('paydunya.return_url') . "\n";
echo "Cancel URL: " . config('paydunya.cancel_url') . "\n\n";

echo "🏪 Informations du marchand:\n";
echo "Store Name: " . config('paydunya.store.name') . "\n";
echo "Store Tagline: " . config('paydunya.store.tagline') . "\n";
echo "Store Phone: " . config('paydunya.store.phone') . "\n";
echo "Store Address: " . config('paydunya.store.postal_address') . "\n";
echo "Store Website: " . config('paydunya.store.website_url') . "\n\n";

echo "✅ Configuration chargée avec succès!\n\n";
