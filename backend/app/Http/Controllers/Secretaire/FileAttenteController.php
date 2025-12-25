<?php

namespace App\Http\Controllers\Secretaire;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\ManagesRendezVous;
use App\Models\DemandeRdv;
use App\Models\Praticien;
use App\Models\RendezVous;
use App\Services\PlanningService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class FileAttenteController extends Controller
{
    use ManagesRendezVous;

    public function index(Request $request)
    {
        $statut = strtoupper($request->get('statut', 'EN_ATTENTE'));

        $query = DemandeRdv::with(['patient.user', 'praticien.user', 'specialite', 'paiements'])
            ->orderBy('date_heure_souhaitee', 'asc');

        // Filtrer pour afficher les demandes:
        // 1. Payées en ligne (paiement CONFIRME)
        // 2. Paiement sur place (pas de paiement ou paiement EN_ATTENTE)
        // 3. Statut PAYEE (paiement effectué)
        $query->where(function ($q) {
            // Cas 1: Paiement en ligne confirmé
            $q->whereHas('paiements', function ($subQ) {
                $subQ->where('statut', 'CONFIRME');
            })
            // Cas 2: Paiement sur place (pas de paiement confirmé)
            ->orWhereDoesntHave('paiements', function ($subQ) {
                $subQ->where('statut', 'CONFIRME');
            })
            // Cas 3: Statut PAYEE (paiement effectué)
            ->orWhere('statut', 'PAYEE');
        });

        if ($statut && $statut !== 'TOUS') {
            if ($statut === 'EN_ATTENTE') {
                $query->whereIn('statut', ['EN_ATTENTE', 'PAYEE']);
            } else {
                $query->where('statut', $statut);
            }
        }

        $demandes = $query->paginate(10)->withQueryString();

        $periodeDebut = now()->startOfDay();
        $periodeFin = $periodeDebut->copy()->addWeeks(2)->endOfDay();

        $praticiensData = collect();

        $specialiteIds = $demandes->pluck('specialite_id')->filter()->unique();

        if ($specialiteIds->isNotEmpty()) {
            $praticiens = Praticien::with([
                    'user:id,name,prenom',
                    'specialites:id',
                    'disponibilites' => fn($query) => $query->where('est_active', true),
                    'rendezVous' => fn($query) => $query
                        ->where('date_heure_rdv', '>=', $periodeDebut)
                        ->where('date_heure_rdv', '<=', $periodeFin),
                ])
                ->whereHas('specialites', fn($query) => $query->whereIn('specialite_id', $specialiteIds))
                ->get();

            $praticiensData = $praticiens->mapWithKeys(function (Praticien $praticien) use ($periodeDebut, $periodeFin) {
                $planning = PlanningService::availability($praticien, $periodeDebut, $periodeFin, $this->slotDuration);

                return [
                    $praticien->id => [
                        'id' => $praticien->id,
                        'nom' => 'Dr. ' . $praticien->user->nom_complet,
                        'specialites' => $praticien->specialites->pluck('id')->values(),
                        'creneaux' => $planning['slots'],
                        'rdvs' => $planning['rdvs'],
                    ],
                ];
            });
        }

        // Statistiques pour les demandes visibles au secrétaire
        // (Payées en ligne OU paiement sur place)
        $statsQuery = DemandeRdv::where(function ($q) {
            $q->whereHas('paiements', function ($subQ) {
                $subQ->where('statut', 'CONFIRME');
            })
            ->orWhereDoesntHave('paiements', function ($subQ) {
                $subQ->where('statut', 'CONFIRME');
            });
        });

        $stats = [
            'EN_ATTENTE' => (clone $statsQuery)->whereIn('statut', ['EN_ATTENTE', 'PAYEE'])->count(),
            'CONFIRMEE' => (clone $statsQuery)->where('statut', 'CONFIRMEE')->count(),
            'REFUSEE' => (clone $statsQuery)->where('statut', 'REFUSEE')->count(),
        ];

        return view('secretaire.file-attente', [
            'demandes' => $demandes,
            'stats' => $stats,
            'statut' => $statut,
            'praticiensData' => $praticiensData,
        ]);
    }

    public function valider(Request $request, DemandeRdv $demandeRdv)
    {
        $bag = 'demande_' . $demandeRdv->id;

        $validated = $request->validateWithBag($bag, [
            'praticien_id' => 'required|exists:praticiens,id',
            'date_heure_rdv' => 'required|date_format:Y-m-d\TH:i|after:now',
            'duree' => 'required|integer|min:15|max:120',
        ]);

        $praticien = Praticien::with(['specialites:id', 'disponibilites' => fn($query) => $query->where('est_active', true)])
            ->findOrFail($validated['praticien_id']);

        if ($demandeRdv->specialite_id && !$praticien->specialites->pluck('id')->contains($demandeRdv->specialite_id)) {
            return back()
                ->withErrors(['praticien_id' => 'Ce praticien ne correspond pas à la spécialité demandée.'], $bag)
                ->withInput();
        }

        $dateRdv = Carbon::createFromFormat('Y-m-d\TH:i', $validated['date_heure_rdv'], config('app.timezone'));
        $duree = (int) $validated['duree'];

        if ($this->hasConflict($praticien, $dateRdv, $duree)) {
            return back()
                ->withErrors(['date_heure_rdv' => 'Ce créneau est déjà occupé par un autre rendez-vous.'], $bag)
                ->withInput();
        }

        // Créer le rendez-vous
        $rendezVous = RendezVous::create([
            'patient_id' => $demandeRdv->patient_id,
            'praticien_id' => $praticien->id,
            'demande_rdv_id' => $demandeRdv->id,
            'date_heure_rdv' => $dateRdv,
            'duree' => $duree,
            'statut' => 'CONFIRME',
            'valide_par' => auth()->user()->secretaire->id,
        ]);

        // Mettre à jour la demande
        $demandeRdv->update([
            'statut' => 'CONFIRMEE',
            'praticien_id' => $praticien->id,
            'traite_par' => auth()->user()->secretaire->id,
            'date_traitement' => now(),
        ]);

        $demandeRdv->setRelation('rendezVous', $rendezVous);
        $demandeRdv->loadMissing(['patient.user', 'praticien.user']);

        // Envoyer une notification au patient
        $demandeRdv->patient->user->notify(new \App\Notifications\DemandeRdvStatusNotification($demandeRdv, 'validee'));

        return redirect()->route('secretaire.file-attente')
            ->with('success', 'Rendez-vous confirmé avec succès.');
    }

    public function refuser(Request $request, DemandeRdv $demandeRdv)
    {
        $request->validate([
            'reponse_secretaire' => 'required|string|max:500',
        ]);

        $demandeRdv->update([
            'statut' => 'REFUSEE',
            'traite_par' => auth()->user()->secretaire->id,
            'reponse_secretaire' => $request->reponse_secretaire,
            'date_traitement' => now(),
        ]);

        // Envoyer une notification au patient
        $demandeRdv->patient->user->notify(new \App\Notifications\DemandeRdvStatusNotification($demandeRdv, 'refusee'));

        return redirect()->route('secretaire.file-attente')
            ->with('success', 'Demande refusée.');
    }

    /**
     * Génère les créneaux disponibles pour un praticien.
     */
}

