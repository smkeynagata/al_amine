<?php

namespace App\Services;

use App\Models\DemandeRdv;
use App\Models\Paiement;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class PaydunyaService
{
    private string $mode;
    private string $baseUrl;

    public function __construct()
    {
        $this->mode = config('paydunya.mode', 'sandbox');
        $this->baseUrl = $this->mode === 'live'
            ? 'https://app.paydunya.com/api/v1'
            : 'https://app.paydunya.com/sandbox-api/v1';
    }

    /**
     * Crée une facture PayDunya et retourne l'URL de paiement.
     */
    public function createInvoice(DemandeRdv $demande, Paiement $paiement, string $methodePaiement, int $montant): array
    {
        $payload = [
            'invoice' => [
                'items' => [
                    'item_0' => [
                        'name' => 'Consultation médicale',
                        'quantity' => 1,
                        'unit_price' => $montant,
                        'total_price' => $montant,
                        'description' => 'Consultation avec Dr. ' . $demande->praticien->user->nom_complet,
                    ]
                ],
                'total_amount' => $montant,
                'description' => 'Paiement de la demande de rendez-vous #' . $demande->id,
            ],
            'store' => [
                'name' => config('paydunya.store.name'),
                'tagline' => config('paydunya.store.tagline'),
                'postal_address' => config('paydunya.store.postal_address'),
                'phone' => config('paydunya.store.phone'),
                'website_url' => config('paydunya.store.website_url'),
                'logo_url' => config('paydunya.store.logo_url'),
            ],
            'actions' => [
                'cancel_url' => config('paydunya.cancel_url'),
                'return_url' => config('paydunya.return_url'),
                'callback_url' => config('paydunya.ipn_url'),
            ],
            'custom_data' => [
                'demande_id' => $demande->id,
                'patient_id' => $demande->patient_id,
                'paiement_id' => $paiement->id,
                'payment_method' => $methodePaiement,
            ],
        ];

        Log::info('PayDunya - Envoi requête', [
            'url' => $this->baseUrl . '/checkout-invoice/create',
            'payload' => $payload,
            'headers' => array_keys($this->headers())
        ]);

        $response = Http::withHeaders($this->headers())
            ->timeout((int) config('paydunya.timeout', 15))
            ->post($this->baseUrl . '/checkout-invoice/create', $payload);

        Log::info('PayDunya - Réponse reçue', [
            'status' => $response->status(),
            'body' => $response->body()
        ]);

        if ($response->failed()) {
            Log::error('PayDunya invoice creation failed', [
                'demande_id' => $demande->id,
                'status' => $response->status(),
                'response' => $response->json(),
            ]);

            throw new RuntimeException('Impossible de créer la facture PayDunya pour le moment.');
        }

        $data = $response->json();

        if (($data['response_code'] ?? null) !== '00') {
            Log::warning('PayDunya invoice creation returned error', [
                'demande_id' => $demande->id,
                'data' => $data,
            ]);

            throw new RuntimeException($data['response_text'] ?? 'Erreur PayDunya.');
        }

        // Construire l'URL de paiement
        $paymentUrl = $this->getPaymentUrl($data['token'] ?? null, $data['response_text'] ?? null);

        Log::info('PayDunya - Invoice créée', [
            'token' => $data['token'] ?? null,
            'payment_url' => $paymentUrl
        ]);

        return [
            'invoice_url' => $paymentUrl,
            'token' => $data['token'] ?? null,
            'raw' => $data,
        ];
    }

    /**
     * Construire l'URL de paiement à partir du token
     */
    private function getPaymentUrl(?string $token, ?string $responseText): ?string
    {
        // Si response_text contient déjà une URL complète, l'utiliser
        if ($responseText && (str_starts_with($responseText, 'http://') || str_starts_with($responseText, 'https://'))) {
            return $responseText;
        }

        // Sinon, construire l'URL avec le token
        if ($token) {
            $baseCheckoutUrl = $this->mode === 'live'
                ? 'https://app.paydunya.com/checkout'
                : 'https://app.paydunya.com/sandbox-checkout';
            
            return $baseCheckoutUrl . '/invoice/' . $token;
        }

        return null;
    }

    /**
     * Vérifie le statut d'une facture PayDunya.
     */
    public function confirmInvoice(string $token): array
    {
        $response = Http::withHeaders($this->headers())
            ->timeout((int) config('paydunya.timeout', 15))
            ->get($this->baseUrl . '/checkout-invoice/confirm/' . $token);

        if ($response->failed()) {
            Log::error('PayDunya confirm invoice failed', [
                'token' => $token,
                'response' => $response->json(),
            ]);

            throw new RuntimeException('Impossible de vérifier le paiement pour le moment.');
        }

        return $response->json();
    }

    private function headers(): array
    {
        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'PAYDUNYA-MASTER-KEY' => config('paydunya.master_key'),
            'PAYDUNYA-PRIVATE-KEY' => config('paydunya.private_key'),
            'PAYDUNYA-TOKEN' => config('paydunya.token'),
        ];

        if (config('paydunya.public_key')) {
            $headers['PAYDUNYA-PUBLIC-KEY'] = config('paydunya.public_key');
        }

        if (config('paydunya.account_alias')) {
            $headers['PAYDUNYA-ACCOUNT-TOKEN'] = config('paydunya.account_alias');
        }

        if ($this->mode !== 'live') {
            $headers['PAYDUNYA-DEBUG'] = '1';
        }

        return $headers;
    }
}
