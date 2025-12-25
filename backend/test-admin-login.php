<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Test de la logique de redirection
$admin = App\Models\User::where('email', 'admin@alamine.sn')->first();

if (!$admin) {
    echo "❌ Aucun utilisateur admin trouvé avec l'email admin@alamine.sn\n";
    exit(1);
}

echo "✅ Utilisateur trouvé:\n";
echo "   Email: {$admin->email}\n";
echo "   Rôle: [{$admin->role}]\n";
echo "   Nom: {$admin->nom_complet}\n\n";

// Test du match de redirection (comme dans le contrôleur)
echo "🔍 Test de la logique de redirection:\n";

$redirectRoute = match ($admin->role) {
    'ADMIN' => 'admin.dashboard',
    'PATIENT' => 'patient.dashboard',
    'PRATICIEN' => 'praticien.dashboard',
    'SECRETAIRE' => 'secretaire.dashboard',
    default => 'patient.dashboard',
};

echo "   Route calculée: {$redirectRoute}\n";
echo "   URL complète: " . route($redirectRoute) . "\n\n";

// Vérifier que la route existe
try {
    $url = route($redirectRoute);
    echo "✅ La route '{$redirectRoute}' existe et est accessible\n";
} catch (\Exception $e) {
    echo "❌ Erreur avec la route '{$redirectRoute}': " . $e->getMessage() . "\n";
}

// Vérifier le middleware
echo "\n🔒 Vérification des permissions:\n";
$routes = app('router')->getRoutes();
$adminDashboardRoute = $routes->getByName('admin.dashboard');

if ($adminDashboardRoute) {
    $middleware = $adminDashboardRoute->middleware();
    echo "   Middlewares sur admin.dashboard: " . implode(', ', $middleware) . "\n";
    
    // Chercher le middleware role
    foreach ($middleware as $mw) {
        if (strpos($mw, 'role') !== false) {
            echo "   ✅ Middleware de rôle trouvé: {$mw}\n";
        }
    }
} else {
    echo "   ❌ Route admin.dashboard non trouvée!\n";
}

echo "\n✅ Test terminé avec succès!\n";
