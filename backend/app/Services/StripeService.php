<?php

namespace App\Services;

use App\Models\Paiement;
use App\Notifications\PaiementConfirmeNotification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Stripe\Checkout\Session as StripeSession;
use Stripe\StripeClient;

class StripeService
{
    protected StripeClient $client;

    public function __construct()
    {
        $secretKey = config('services.stripe.secret');

        if (empty($secretKey)) {
            throw new \RuntimeException('La clé secrète Stripe est manquante. Définissez STRIPE_SECRET_KEY dans votre fichier .env.');
        }

        $this->client = new StripeClient($secretKey);
    }

    /**
     * Crée une session de paiement Stripe Checkout pour un paiement donné.
     */
    public function createCheckoutSession(Paiement $paiement, array $options = []): StripeSession
    {
        $paiement->loadMissing(['patient.user', 'demandeRdv.praticien.user', 'demandeRdv.specialite']);

        $amount = (int) round($paiement->montant);
        $currency = 'xof';
        $patientUser = $paiement->patient?->user;
        $demande = $paiement->demandeRdv;
        $description = $demande ? sprintf('Consultation %s - %s', $demande->specialite?->nom ?? 'Spécialité', $demande->praticien?->user?->nom_complet ?? 'Praticien') : 'Consultation médicale';

        $successUrl = $options['success_url'] ?? url('/');
        $cancelUrl = $options['cancel_url'] ?? url('/');

        $session = $this->client->checkout->sessions->create([
            'mode' => 'payment',
            'payment_method_types' => ['card'],
            'success_url' => $successUrl.'?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => $cancelUrl,
            'customer_email' => $patientUser?->email,
            'metadata' => [
                'paiement_id' => (string) $paiement->id,
                'demande_rdv_id' => $demande ? (string) $demande->id : null,
            ],
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => $currency,
                        'unit_amount' => $amount,
                        'product_data' => [
                            'name' => $description,
                        ],
                    ],
                    'quantity' => 1,
                ],
            ],
        ]);

        $paiement->numero_transaction = $session->id;
        $paiement->save();

        return $session;
    }

    /**
     * Synchronise le statut du paiement depuis Stripe Checkout Session.
     */
    public function syncPaymentStatus(Paiement $paiement): ?StripeSession
    {
        if (!$paiement->numero_transaction) {
            return null;
        }

        try {
            $paiement->loadMissing(['patient.user', 'demandeRdv.praticien.user', 'demandeRdv.specialite']);
            $previousStatus = $paiement->statut;

            $session = $this->client->checkout->sessions->retrieve(
                $paiement->numero_transaction,
                ['expand' => ['payment_intent']]
            );

            if ($session && $session->payment_status === 'paid') {
                $paiement->fill([
                    'statut' => 'CONFIRME',
                    'date_paiement' => Carbon::now(),
                ])->save();

                $paiement->demandeRdv?->update([
                    'statut' => 'PAYEE',
                    'paiement_effectue' => true,
                ]);

                if ($previousStatus !== 'CONFIRME' && $paiement->patient?->user) {
                    $paiement->patient->user->notify(new PaiementConfirmeNotification($paiement->fresh(['demandeRdv.praticien.user'])));
                }
            }

            return $session;
        } catch (\Exception $exception) {
            Log::error('Stripe sync error', [
                'paiement_id' => $paiement->id,
                'error' => $exception->getMessage(),
            ]);

            return null;
        }
    }

    public function verifyWebhookSignature(string $payload, string $signature, string $secret)
    {
        return \Stripe\Webhook::constructEvent($payload, $signature, $secret);
    }
}
