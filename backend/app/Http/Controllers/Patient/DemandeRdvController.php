<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\DemandeRdv;
use App\Models\Paiement;
use App\Models\Specialite;
use App\Models\Praticien;
use App\Services\StripeService;
use App\Services\PaydunyaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class DemandeRdvController extends Controller
{
    public function index()
    {
        $patient = auth()->user()->patient;

        $demandes = DemandeRdv::with(['praticien.user', 'specialite', 'traitePar.user'])
            ->where('patient_id', $patient->id)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('patient.mes-demandes', compact('demandes'));
    }

    public function create()
    {
        $specialites = Specialite::with(['praticiens.user', 'praticiens.service'])->get();

        return view('patient.demander-rdv', compact('specialites'));
    }

    public function store(Request $request, StripeService $stripeService, PaydunyaService $paydunyaService)
    {
        $request->validate([
            'specialite_id' => 'required|exists:specialites,id',
            'praticien_id' => 'required|exists:praticiens,id',
            'date_heure_souhaitee' => 'required|date|after:now',
            'motif' => 'required|string|max:500',
            'mode_paiement' => 'required|in:EN_LIGNE,SUR_PLACE',
            'methode_paiement' => 'required_if:mode_paiement,EN_LIGNE|nullable|in:CARTE_BANCAIRE,WAVE,ORANGE_MONEY',
        ]);

        $patient = auth()->user()->patient;
        $praticien = Praticien::findOrFail($request->praticien_id);

        DB::beginTransaction();

        try {
            // Déterminer le statut initial selon le mode de paiement
            $statutInitial = $request->mode_paiement === 'EN_LIGNE' ? 'EN_ATTENTE_PAIEMENT' : 'EN_ATTENTE';

            // Créer la demande de RDV
            $demande = DemandeRdv::create([
                'patient_id' => $patient->id,
                'praticien_id' => $request->praticien_id,
                'specialite_id' => $request->specialite_id,
                'date_heure_souhaitee' => $request->date_heure_souhaitee,
                'motif' => $request->motif,
                'statut' => $statutInitial,
                'mode_paiement' => $request->mode_paiement,
                'methode_paiement_choisie' => $request->methode_paiement,
                'paiement_effectue' => false,
            ]);

            // Si paiement sur place, on termine ici (statut déjà EN_ATTENTE)
            if ($request->mode_paiement === 'SUR_PLACE') {
                DB::commit();

                return redirect()->route('patient.mes-demandes')
                    ->with('success', 'Votre demande de rendez-vous a été envoyée avec succès. Vous pourrez payer lors de votre visite.');
            }

            // Paiement en ligne via Stripe Checkout
            $montant = (int) ($praticien->tarif_consultation ?? 15000);

            $methodePaiement = $request->methode_paiement;

            $paiement = Paiement::create([
                'patient_id' => $patient->id,
                'montant' => $montant,
                'methode_paiement' => match ($methodePaiement) {
                    'CARTE_BANCAIRE' => 'CARTE',
                    'WAVE' => 'WAVE',
                    'ORANGE_MONEY' => 'ORANGE_MONEY',
                    default => 'AUTRE',
                },
                'statut' => 'EN_ATTENTE',
                'demande_rdv_id' => $demande->id,
                'reference' => 'RDV-' . Str::upper(Str::random(10)),
            ]);

            $demande->update([
                'montant_avance' => $montant,
            ]);

            if ($methodePaiement === 'CARTE_BANCAIRE') {
                $successUrl = route('patient.stripe.checkout.success', ['paiement' => $paiement->id]);
                $cancelUrl = route('patient.stripe.checkout.cancel', ['paiement' => $paiement->id]);

                $session = $stripeService->createCheckoutSession($paiement, [
                    'success_url' => $successUrl,
                    'cancel_url' => $cancelUrl,
                ]);

                DB::commit();

                Log::info('Stripe - Redirection vers Checkout', [
                    'demande_id' => $demande->id,
                    'paiement_id' => $paiement->id,
                    'session_id' => $session->id,
                    'url' => $session->url,
                ]);

                return redirect()->away($session->url);
            }

            // PayDunya (Wave / Orange Money)
            $invoice = $paydunyaService->createInvoice($demande, $paiement, $methodePaiement, $montant);

            $paiement->update([
                'numero_transaction' => $invoice['token'] ?? null,
            ]);

            DB::commit();

            Log::info('PayDunya - Redirection vers checkout', [
                'demande_id' => $demande->id,
                'paiement_id' => $paiement->id,
                'token' => $invoice['token'] ?? null,
                'url' => $invoice['invoice_url'] ?? null,
            ]);

            if (!empty($invoice['invoice_url'])) {
                return redirect()->away($invoice['invoice_url']);
            }

            throw new \RuntimeException('URL de paiement PayDunya indisponible.');

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Erreur lors de la création du paiement en ligne', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de la création du paiement. Veuillez réessayer ou choisir de payer sur place.');
        }
    }

    public function getPraticiensBySpecialite($specialiteId)
    {
        $praticiens = Praticien::with('user')
            ->whereHas('specialites', function($query) use ($specialiteId) {
                $query->where('specialite_id', $specialiteId);
            })
            ->get()
            ->map(function($praticien) {
                return [
                    'id' => $praticien->id,
                    'nom' => $praticien->user->nom_complet,
                    'tarif' => $praticien->tarif_format,
                    'experience' => $praticien->annees_experience . ' ans',
                ];
            });

        return response()->json($praticiens);
    }
}

