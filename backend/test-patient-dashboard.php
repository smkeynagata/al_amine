<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🔍 Test du dashboard patient:\n\n";

$patient = App\Models\Patient::first();
if (!$patient) {
    echo "❌ Aucun patient trouvé\n";
    exit(1);
}

echo "✅ Patient: {$patient->user->nom_complet}\n\n";

// Test de la requête qui causait l'erreur
try {
    $prochainsRdv = App\Models\RendezVous::with(['praticien.user', 'praticien.specialites'])
        ->where('patient_id', $patient->id)
        ->where('date_heure_rdv', '>=', now()->startOfDay())
        ->whereIn('statut', ['CONFIRME', 'EN_ATTENTE'])
        ->orderBy('date_heure_rdv')
        ->take(5)
        ->get();
    
    echo "✅ Requête prochains RDV réussie!\n";
    echo "   Nombre de RDV: {$prochainsRdv->count()}\n";
    
    foreach ($prochainsRdv as $rdv) {
        echo "   - RDV avec {$rdv->praticien->user->nom_complet} le {$rdv->date_heure_rdv->format('d/m/Y à H:i')}\n";
        if ($rdv->praticien->specialites->count() > 0) {
            echo "     Spécialités: " . $rdv->praticien->specialites->pluck('nom')->join(', ') . "\n";
        }
    }
    
    echo "\n✅ Toutes les relations fonctionnent correctement!\n";
    echo "\n🎉 Le dashboard patient devrait maintenant fonctionner!\n";
} catch (\Exception $e) {
    echo "❌ Erreur: {$e->getMessage()}\n";
    exit(1);
}
