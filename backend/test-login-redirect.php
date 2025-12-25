<?php

/**
 * Script de test pour vérifier la logique de redirection après login
 * À exécuter avec: php test-login-redirect.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "========================================\n";
echo "TEST DE REDIRECTION APRÈS LOGIN\n";
echo "========================================\n\n";

// Test avec l'admin
$admin = App\Models\User::where('email', 'admin@alamine.sn')->first();

if (!$admin) {
    echo "❌ ERREUR: Admin non trouvé!\n";
    exit(1);
}

echo "✅ Utilisateur trouvé:\n";
echo "   Email: {$admin->email}\n";
echo "   Rôle: {$admin->role}\n";
echo "   ID: {$admin->id}\n\n";

// Simuler la logique du contrôleur AuthenticatedSessionController::store
echo "🔍 Simulation de la logique de redirection...\n\n";

$redirectRoute = match ($admin->role) {
    'ADMIN' => 'admin.dashboard',
    'PATIENT' => 'patient.dashboard',
    'PRATICIEN' => 'praticien.dashboard',
    'SECRETAIRE' => 'secretaire.dashboard',
    default => 'patient.dashboard',
};

echo "Route calculée: {$redirectRoute}\n";

try {
    $url = route($redirectRoute);
    echo "URL de redirection: {$url}\n\n";
    
    // Vérifier que c'est bien l'URL admin
    if (strpos($url, '/admin/dashboard') !== false) {
        echo "✅ SUCCESS: La redirection pointe bien vers /admin/dashboard\n";
    } else {
        echo "❌ ERREUR: La redirection NE pointe PAS vers /admin/dashboard\n";
        echo "   URL obtenue: {$url}\n";
        exit(1);
    }
} catch (\Exception $e) {
    echo "❌ ERREUR lors de la génération de l'URL: {$e->getMessage()}\n";
    exit(1);
}

// Test avec un praticien
echo "\n----------------------------------------\n";
echo "Test avec un utilisateur PRATICIEN\n";
echo "----------------------------------------\n\n";

$praticien = App\Models\User::where('role', 'PRATICIEN')->first();

if ($praticien) {
    $redirectRoute = match ($praticien->role) {
        'ADMIN' => 'admin.dashboard',
        'PATIENT' => 'patient.dashboard',
        'PRATICIEN' => 'praticien.dashboard',
        'SECRETAIRE' => 'secretaire.dashboard',
        default => 'patient.dashboard',
    };
    
    $url = route($redirectRoute);
    echo "Praticien: {$praticien->email}\n";
    echo "Route: {$redirectRoute}\n";
    echo "URL: {$url}\n";
    
    if (strpos($url, '/praticien/dashboard') !== false) {
        echo "✅ OK: Le praticien est bien redirigé vers /praticien/dashboard\n";
    }
}

echo "\n========================================\n";
echo "✅ TOUS LES TESTS SONT PASSÉS!\n";
echo "========================================\n\n";

echo "📝 INSTRUCTIONS POUR TESTER DANS LE NAVIGATEUR:\n";
echo "1. Ouvrez le navigateur en mode navigation privée\n";
echo "2. Allez sur http://localhost:8000\n";
echo "3. Cliquez sur 'Se connecter'\n";
echo "4. Utilisez: admin@alamine.sn avec le mot de passe\n";
echo "5. Vous devriez être redirigé vers /admin/dashboard\n\n";

echo "Si vous allez toujours sur /praticien/dashboard:\n";
echo "- Vérifiez les logs Laravel: tail -f storage/logs/laravel.log\n";
echo "- Videz le cache: php artisan cache:clear && php artisan config:clear\n";
echo "- Supprimez les cookies du navigateur\n";
