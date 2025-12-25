<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Paiement;
use App\Services\StripeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class StripeController extends Controller
{
    public function success(Request $request, Paiement $paiement, StripeService $stripeService): RedirectResponse
    {
        $sessionId = $request->query('session_id');

        if ($sessionId && $sessionId !== $paiement->numero_transaction) {
            Log::warning('Stripe success callback session mismatch', [
                'expected' => $paiement->numero_transaction,
                'received' => $sessionId,
                'paiement_id' => $paiement->id,
            ]);
        }

        $stripeService->syncPaymentStatus($paiement);
        $paiement->refresh();

        if (in_array($paiement->statut, ['CONFIRME', 'PAYE', 'VALIDE'], true) && $paiement->demandeRdv) {
            $paiement->demandeRdv->update([
                'statut' => 'PAYEE',
                'paiement_effectue' => true,
            ]);
        }

        $message = $paiement->statut === 'CONFIRME'
            ? 'Votre paiement a été confirmé. Votre demande est maintenant en attente de validation par nos secrétaires.'
            : "Votre paiement est en cours de traitement. Vous recevrez une notification dès qu'il sera confirmé.";

        return redirect()->route('patient.mes-demandes')->with('success', $message);
    }

    public function cancel(Paiement $paiement): RedirectResponse
    {
        Log::info('Stripe checkout annulé par le patient', [
            'paiement_id' => $paiement->id,
            'numero_transaction' => $paiement->numero_transaction,
        ]);

        return redirect()->route('patient.mes-demandes')
            ->with('error', 'Le paiement a été annulé. Vous pouvez réessayer ou choisir le paiement sur place.');
    }

    public function webhook(Request $request, StripeService $stripeService): Response
    {
        $secret = config('services.stripe.webhook_secret');
        if (!$secret) {
            Log::error('Stripe webhook secret non configuré');
            return response('Webhook secret non configuré', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $payload = $request->getContent();
        $signature = $request->header('Stripe-Signature');

        try {
            $event = $stripeService->verifyWebhookSignature($payload, $signature, $secret);
        } catch (\Exception $exception) {
            Log::warning('Stripe webhook signature invalide', ['error' => $exception->getMessage()]);
            return response('Signature invalide', Response::HTTP_BAD_REQUEST);
        }

        if ($event && $event->type === 'checkout.session.completed') {
            $session = $event->data->object;
            $sessionId = $session->id ?? null;

            if ($sessionId) {
                $paiement = Paiement::where('numero_transaction', $sessionId)->first();
                if ($paiement) {
                    $stripeService->syncPaymentStatus($paiement);
                }
            }
        }

        return response()->json(['status' => 'ok']);
    }
}
