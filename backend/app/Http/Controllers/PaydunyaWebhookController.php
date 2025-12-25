<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Paiement;
use App\Models\Facture;
use App\Models\DemandeRdv;
use App\Notifications\PaiementConfirmeNotification;
use Illuminate\Support\Facades\Log;

class PaydunyaWebhookController extends Controller
{
    /**
     * Endpoint IPN pour recevoir les notifications PayDunya
     * URL à configurer dans PayDunya: https://votre-domaine.com/paydunya/ipn
     */
    public function handleIPN(Request $request)
    {
        // Logger toutes les données reçues pour debug
        Log::info('PayDunya IPN received', [
            'data' => $request->all(),
            'headers' => $request->headers->all()
        ]);

        try {
            // Récupérer les données de PayDunya
            $data = $request->all();

            $status = $data['status'] ?? null;
            $customData = $data['custom_data'] ?? [];
            $transactionId = $data['transaction_id'] ?? null;

            if (isset($data['data'])) {
                $payload = $data['data'];
                $status = $payload['status'] ?? $status;
                $customData = $payload['custom_data'] ?? $customData;
                $transactionId = $payload['transaction_id'] ?? $transactionId;

                if (isset($payload['invoice'])) {
                    $invoice = $payload['invoice'];
                    $status = $invoice['status'] ?? $status;
                    $customData = $invoice['custom_data'] ?? $customData;
                    $transactionId = $invoice['token'] ?? $transactionId;
                }
            }
            
            if (is_string($customData)) {
                $decoded = json_decode($customData, true);
                $customData = is_array($decoded) ? $decoded : [];
            } elseif (is_object($customData)) {
                $customData = (array) $customData;
            }

            // Vérifier que les données essentielles sont présentes
            if (!$status || empty($customData)) {
                Log::error('PayDunya IPN: Missing required fields', [
                    'status' => $status,
                    'custom_data' => $customData,
                    'raw_data' => $data,
                ]);
                return response()->json(['status' => 'error', 'message' => 'Missing required fields'], 400);
            }
            
            // Récupérer l'ID du paiement depuis custom_data
            $paiementId = isset($customData['paiement_id']) ? (int) $customData['paiement_id'] : null;
            $demandeRdvId = isset($customData['demande_rdv_id']) ? (int) $customData['demande_rdv_id'] : (isset($customData['demande_id']) ? (int) $customData['demande_id'] : null);

            if (!$paiementId && !$demandeRdvId) {
                Log::error('PayDunya IPN: No paiement_id or demande_id in custom_data', ['custom_data' => $customData]);
                return response()->json(['status' => 'error', 'message' => 'No paiement_id or demande_id'], 400);
            }

            // Si on a seulement demande_id, trouver le paiement via la demande
            if (!$paiementId && $demandeRdvId) {
                $paiement = Paiement::where('demande_rdv_id', $demandeRdvId)->latest()->first();
            } else {
                $paiement = Paiement::find($paiementId);
            }

            $demandeRdv = $demandeRdvId ? DemandeRdv::find($demandeRdvId) : null;
            
            if (!$paiement) {
                Log::error('PayDunya IPN: Paiement not found', ['paiement_id' => $paiementId, 'demande_rdv_id' => $demandeRdvId]);
                return response()->json(['status' => 'error', 'message' => 'Paiement not found'], 404);
            }

            // Traiter selon le statut
            if ($status === 'completed' || $status === 'success') {
                // Créer une facture d'acompte si inexistante
                if (!$paiement->facture) {
                    $facture = Facture::create([
                        'consultation_id' => null,
                        'demande_rdv_id' => $demandeRdv?->id,
                        'patient_id' => $paiement->patient_id,
                        'numero_facture' => generateReference('FAC'),
                        'date_facture' => now()->toDateString(),
                        'montant_total' => $paiement->montant,
                        'montant_paye' => $paiement->montant,
                        'montant_restant' => 0,
                        'statut' => 'PAYEE',
                        'lignes' => [
                            'items' => [[
                                'designation' => 'Acompte demande de rendez-vous',
                                'montant' => (float) $paiement->montant,
                                'praticien' => $demandeRdv?->praticien?->user?->nom_complet,
                                'date_demande' => $demandeRdv?->date_heure_souhaitee?->format('d/m/Y H:i'),
                            ]],
                            'notes' => sprintf(
                                'Paiement en ligne confirmé pour la demande de rendez-vous #%s.',
                                $demandeRdv?->id ?? 'N/A'
                            ),
                        ],
                    ]);

                    $paiement->facture()->associate($facture);
                    $paiement->setRelation('facture', $facture);
                }

                // Paiement réussi
                $paiement->update([
                    'statut' => 'CONFIRME',
                    'numero_transaction' => $transactionId,
                    'date_paiement' => now()
                ]);

                // Mettre à jour la facture
                if ($paiement->facture) {
                    $paiement->facture->update([
                        'statut' => 'PAYEE',
                        'montant_restant' => 0
                    ]);
                }

                // Si c'est un paiement de demande RDV, confirmer la demande
                if ($demandeRdvId) {
                    $demandeRdv = DemandeRdv::find($demandeRdvId);
                    if ($demandeRdv) {
                        $demandeRdv->update([
                            'statut' => 'PAYEE',
                            'paiement_effectue' => true
                        ]);
                        
                        Log::info('PayDunya IPN: Demande RDV mise à jour', [
                            'demande_rdv_id' => $demandeRdvId,
                            'nouveau_statut' => 'PAYEE'
                        ]);
                    }
                }

                // Notifier le patient
                if ($paiement->facture && $paiement->facture->patient) {
                    $paiement->facture->patient->user->notify(
                        new PaiementConfirmeNotification($paiement)
                    );
                }

                Log::info('PayDunya IPN: Payment successful', [
                    'paiement_id' => $paiementId,
                    'transaction_id' => $transactionId
                ]);

                return response()->json([
                    'status' => 'success',
                    'message' => 'Payment processed successfully'
                ]);

            } elseif ($status === 'cancelled' || $status === 'failed') {
                if ($paiement->statut === 'PAYE') {
                    Log::info('PayDunya IPN: Ignoring failure for already paid transaction', [
                        'paiement_id' => $paiementId,
                        'incoming_status' => $status
                    ]);

                    return response()->json([
                        'status' => 'ignored',
                        'message' => 'Payment already marked as paid'
                    ]);
                }

                // Paiement échoué ou annulé
                $paiement->update([
                    'statut' => 'ECHOUE',
                    'numero_transaction' => $transactionId
                ]);

                Log::warning('PayDunya IPN: Payment failed', [
                    'paiement_id' => $paiementId,
                    'status' => $status
                ]);

                return response()->json([
                    'status' => 'failed',
                    'message' => 'Payment failed or cancelled'
                ]);

            } else {
                // Statut inconnu
                Log::warning('PayDunya IPN: Unknown status', [
                    'status' => $status,
                    'paiement_id' => $paiementId
                ]);

                return response()->json([
                    'status' => 'unknown',
                    'message' => 'Unknown payment status'
                ]);
            }

        } catch (\Exception $e) {
            Log::error('PayDunya IPN Error: ' . $e->getMessage(), [
                'exception' => $e,
                'request_data' => $request->all()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Internal server error'
            ], 500);
        }
    }

    /**
     * Page de retour après paiement (succès)
     */
    public function paymentReturn(Request $request)
    {
        $token = $request->get('token');
        
        Log::info('PayDunya Return URL accessed', ['token' => $token]);

        return redirect()->route('patient.mes-demandes')
            ->with('success', 'Votre paiement est en cours de traitement. Vous recevrez une notification une fois confirmé.');
    }

    /**
     * Page de retour après annulation
     */
    public function paymentCancel(Request $request)
    {
        Log::info('PayDunya Cancel URL accessed');

        return redirect()->route('patient.mes-demandes')
            ->with('error', 'Le paiement a été annulé. Vous pouvez réessayer quand vous le souhaitez.');
    }
}
