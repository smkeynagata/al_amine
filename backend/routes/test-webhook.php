<?php

use App\Models\Paiement;
use App\Services\StripeService;
use Illuminate\Support\Facades\Route;

Route::get('/test-webhook/{paiement_id}', function ($paiement_id) {
    $paiement = Paiement::find($paiement_id);
    
    if (!$paiement) {
        return response()->json(['error' => 'Paiement non trouvé'], 404);
    }

    $stripeService = app(StripeService::class);
    $session = $stripeService->syncPaymentStatus($paiement);

    $paiement->refresh();

    return response()->json([
        'paiement_id' => $paiement->id,
        'paiement_statut' => $paiement->statut,
        'demande_id' => $paiement->demande_rdv_id,
        'demande_statut' => $paiement->demandeRdv?->statut,
        'paiement_effectue' => $paiement->demandeRdv?->paiement_effectue,
        'stripe_session' => $session ? [
            'id' => $session->id,
            'payment_status' => $session->payment_status,
        ] : null,
    ]);
})->name('test.webhook');
