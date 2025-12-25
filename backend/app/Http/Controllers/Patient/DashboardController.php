<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\RendezVous;
use App\Models\Facture;
use App\Models\Paiement;
use App\Models\MesureSante;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    public function index()
    {
        $patient = auth()->user()->patient;

        // Statistiques pour le dashboard
        $stats = [
            'rdv_a_venir' => RendezVous::where('patient_id', $patient->id)
                ->where('date_heure_rdv', '>=', now()->startOfDay())
                ->whereIn('statut', ['CONFIRME', 'EN_ATTENTE'])
                ->count(),
            'consultations_total' => $patient->consultations()->count(),
            'factures_impayees' => Facture::where('patient_id', $patient->id)
                ->whereIn('statut', ['EMISE', 'BROUILLON'])
                ->count(),
            'montant_du' => Facture::where('patient_id', $patient->id)
                ->whereIn('statut', ['EMISE', 'BROUILLON'])
                ->sum('montant_restant'),
        ];

        // Prochains rendez-vous
        $prochainsRdv = RendezVous::with(['praticien.user', 'praticien.specialites'])
            ->where('patient_id', $patient->id)
            ->where('date_heure_rdv', '>=', now()->startOfDay())
            ->whereIn('statut', ['CONFIRME', 'EN_ATTENTE'])
            ->orderBy('date_heure_rdv')
            ->take(5)
            ->get();

        // Données de suivi santé pour les graphiques (3 derniers mois)
        $mesuresSante = MesureSante::where('patient_id', $patient->id)
            ->where('date_mesure', '>=', now()->subMonths(3))
            ->orderBy('date_mesure', 'asc')
            ->get();

        $chartData = [
            'dates' => $mesuresSante->pluck('date_mesure')->map(fn($date) => $date->format('d/m'))->toArray(),
            'poids' => $mesuresSante->pluck('poids')->toArray(),
            'tension_systolique' => $mesuresSante->pluck('tension_systolique')->toArray(),
            'tension_diastolique' => $mesuresSante->pluck('tension_diastolique')->toArray(),
        ];

        return view('patient.dashboard', compact('stats', 'prochainsRdv', 'chartData'));
    }

    public function mesRdv()
    {
        $patient = auth()->user()->patient;

        $rendezVous = RendezVous::with(['praticien.user', 'praticien.specialites', 'consultation', 'demandeRdv'])
            ->where('patient_id', $patient->id)
            ->orderBy('date_heure_rdv', 'desc')
            ->paginate(15);

        return view('patient.mes-rdv', compact('rendezVous'));
    }

    public function showRendezVous(RendezVous $rendezVous): JsonResponse
    {
        $patient = auth()->user()->patient;

        abort_if($rendezVous->patient_id !== $patient->id, 403);

        $rendezVous->loadMissing([
            'praticien.user',
            'praticien.specialites',
            'demandeRdv',
            'consultation',
        ]);

        $date = $rendezVous->date_heure_rdv;
        $specialites = $rendezVous->praticien
            ? $rendezVous->praticien->specialites->pluck('nom')->filter()->values()->all()
            : [];

        $payload = [
            'id' => $rendezVous->id,
            'statut' => $rendezVous->statut_display,
            'praticien' => $rendezVous->praticien?->user?->nom_complet,
            'specialites' => $specialites,
            'date' => $date?->locale('fr')->isoFormat('dddd D MMMM YYYY'),
            'heure' => $date?->format('H:i'),
            'duree' => $rendezVous->duree_minutes ?: null,
            'notes' => $rendezVous->notes,
            'motif' => $rendezVous->demandeRdv?->motif,
            'lieu' => $rendezVous->praticien?->numero_bureau,
            'diff' => $date?->diffForHumans(),
            'consultation' => $rendezVous->consultation ? [
                'diagnostic' => $rendezVous->consultation->diagnostic,
                'traitement' => $rendezVous->consultation->traitement,
            ] : null,
        ];

        if ($payload['duree']) {
            $payload['duree_formatee'] = sprintf('%d min', $payload['duree']);
        }

        return response()->json($payload);
    }

    public function factures()
    {
        $patient = auth()->user()->patient;

        $factures = Facture::with([
                'consultation.praticien.user',
                'demandeRdv.praticien.user',
                'demandeRdv.specialite',
                'paiements'
            ])
            ->where('patient_id', $patient->id)
            ->orderBy('date_facture', 'desc')
            ->paginate(15);

        return view('patient.factures', compact('factures'));
    }

    public function showFacture(Facture $facture)
    {
        // Vérifier que la facture appartient au patient connecté
        if ($facture->patient_id !== auth()->user()->patient->id) {
            abort(403);
        }

        $facture->load(['consultation.praticien.user', 'paiements']);

        return view('patient.facture-detail', compact('facture'));
    }

    public function paiement(Facture $facture)
    {
        // Vérifier que la facture appartient au patient connecté
        if ($facture->patient_id !== auth()->user()->patient->id) {
            abort(403);
        }

        // Vérifier que la facture n'est pas déjà payée
        if ($facture->statut === 'PAYEE') {
            return redirect()->route('patient.factures')->with('error', 'Cette facture est déjà payée.');
        }

        return view('patient.paiement', compact('facture'));
    }

    public function traiterPaiement(Request $request, Facture $facture)
    {
        // Vérifier que la facture appartient au patient connecté
        if ($facture->patient_id !== auth()->user()->patient->id) {
            abort(403);
        }

        $request->validate([
            'methode_paiement' => 'required|in:ESPECES,WAVE,ORANGE_MONEY,FREE_MONEY,CARTE',
            'montant' => 'required|numeric|min:0|max:' . $facture->montant_restant,
        ]);

        // Créer le paiement
        $paiement = Paiement::create([
            'facture_id' => $facture->id,
            'patient_id' => $facture->patient_id,
            'montant' => $request->montant,
            'methode_paiement' => $request->methode_paiement,
            'date_paiement' => now(),
            'reference' => generateReference('PAY'),
            'numero_transaction' => $request->methode_paiement !== 'ESPECES' ? 'TXN-' . strtoupper(uniqid()) : null,
            'statut' => 'VALIDE',
        ]);

        // Mettre à jour la facture
        $facture->montant_paye += $request->montant;
        $facture->montant_restant = $facture->montant_total - $facture->montant_paye;

        if ($facture->montant_restant <= 0) {
            $facture->statut = 'PAYEE';
        }

        $facture->save();

        return redirect()->route('patient.facture.show', $facture)
            ->with('success', 'Paiement effectué avec succès. Référence: ' . $paiement->reference);
    }

    public function annulerRdv(RendezVous $rendezVous)
    {
        // Vérifier que le RDV appartient au patient
        if ($rendezVous->patient_id !== auth()->user()->patient->id) {
            abort(403);
        }

        // Vérifier que le RDV n'est pas déjà annulé
        if ($rendezVous->statut === 'ANNULE') {
            return back()->with('error', 'Ce rendez-vous est déjà annulé.');
        }

        // Vérifier que le RDV n'est pas dans le passé
        if ($rendezVous->date_heure_rdv < now()) {
            return back()->with('error', 'Impossible d\'annuler un rendez-vous passé.');
        }

        $rendezVous->update(['statut' => 'ANNULE']);

        return back()->with('success', 'Rendez-vous annulé avec succès.');
    }

    public function reprogrammerRdv(RendezVous $rendezVous)
    {
        // Vérifier que le RDV appartient au patient
        if ($rendezVous->patient_id !== auth()->user()->patient->id) {
            abort(403);
        }

        // Récupérer les disponibilités du praticien
        $praticien = $rendezVous->praticien;
        $disponibilites = $praticien->disponibilites()
            ->where('est_disponible', true)
            ->orderBy('jour_semaine')
            ->orderBy('heure_debut')
            ->get();

        return view('patient.rdv.reprogrammer', compact('rendezVous', 'disponibilites'));
    }

    public function updateRdv(Request $request, RendezVous $rendezVous)
    {
        // Vérifier que le RDV appartient au patient
        if ($rendezVous->patient_id !== auth()->user()->patient->id) {
            abort(403);
        }

        $validated = $request->validate([
            'date_heure_rdv' => 'required|date|after:now',
            'motif' => 'nullable|string',
        ]);

        $rendezVous->update($validated);

        return redirect()->route('patient.mes-rdv')
            ->with('success', 'Rendez-vous reprogrammé avec succès.');
    }
}

