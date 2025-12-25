<?php

namespace App\Http\Controllers\Secretaire;

use App\Http\Controllers\Controller;
use App\Models\DemandeRdv;
use App\Models\RendezVous;
use App\Models\Consultation;
use App\Models\Facture;
use App\Models\Paiement;
use App\Models\Praticien;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $secretaire = auth()->user()->secretaire;

        // Statistiques du dashboard
        $stats = [
            'demandes_attente' => DemandeRdv::whereIn('statut', ['EN_ATTENTE', 'EN_ATTENTE_PAIEMENT'])->count(),
            'rdv_aujourdhui' => RendezVous::whereDate('date_heure_rdv', today())
                ->whereIn('statut', ['CONFIRME', 'PLANIFIE', 'EN_COURS'])
                ->count(),
            'factures_impayees' => Facture::whereIn('statut', ['EMISE', 'EN_ATTENTE'])->count(),
            'paiements_aujourdhui' => Paiement::whereDate('created_at', today())->count(),
        ];

        // Données pour le graphique hebdomadaire (7 derniers jours)
        $chartData = $this->getWeeklyActivityData();
        $stats = array_merge($stats, $chartData);

        // Demandes en attente (5 premières)
        $demandesRecentes = DemandeRdv::with(['patient.user', 'praticien.user', 'specialite'])
            ->where('statut', 'EN_ATTENTE')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // RDV du jour
        $rdvAujourdhui = RendezVous::with(['patient.user', 'praticien.user'])
            ->whereDate('date_heure_rdv', today())
            ->orderBy('date_heure_rdv')
            ->get();

        // Factures récentes
        $facturesRecentes = Facture::with(['consultation.patient.user', 'consultation.praticien.user'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('secretaire.dashboard', compact('stats', 'demandesRecentes', 'rdvAujourdhui', 'facturesRecentes'));
    }

    public function agendas()
    {
        $praticiens = Praticien::with(['user', 'service', 'specialites'])
            ->get();

        return view('secretaire.agendas', compact('praticiens'));
    }

    public function multiAgenda()
    {
        $praticiens = Praticien::with('user')->get()->map(function (Praticien $praticien) {
            $praticien->color_code = $this->colorForPracticien($praticien->id);

            return $praticien;
        });

        return view('secretaire.agendas-multi', compact('praticiens'));
    }

    public function agendaEvents(Request $request)
    {
        $start = Carbon::parse($request->query('start', now()->startOfWeek()));
        $end = Carbon::parse($request->query('end', now()->endOfWeek()->addDays(7)));
        $praticienIds = collect($request->query('praticiens', []))->filter()->map(fn ($id) => (int) $id);

        $query = RendezVous::with(['patient.user', 'praticien.user'])
            ->whereBetween('date_heure_rdv', [$start, $end]);

        if ($praticienIds->isNotEmpty()) {
            $query->whereIn('praticien_id', $praticienIds);
        }

        $events = $query->get()->map(function (RendezVous $rdv) {
            $start = $rdv->date_heure_rdv;
            $end = (clone $start)->addMinutes($rdv->duree ?? 30);

            return [
                'id' => $rdv->id,
                'title' => sprintf('Dr. %s · %s', $rdv->praticien->user->nom_complet ?? '', $rdv->patient->user->nom_complet ?? ''),
                'start' => $start->toIso8601String(),
                'end' => $end->toIso8601String(),
                'backgroundColor' => $this->colorForPracticien($rdv->praticien_id),
                'borderColor' => $this->colorForPracticien($rdv->praticien_id),
                'extendedProps' => [
                    'praticien_id' => $rdv->praticien_id,
                    'praticien_nom' => $rdv->praticien->user->nom_complet ?? '',
                    'patient_nom' => $rdv->patient->user->nom_complet ?? '',
                    'statut' => $rdv->statut,
                    'duree' => $rdv->duree ?? 30,
                ],
            ];
        });

        return response()->json($events);
    }

    public function agendaReplanifier(Request $request)
    {
        $data = $request->validate([
            'rendezvous_id' => ['required', 'exists:rendez_vous,id'],
            'praticien_id' => ['required', 'exists:praticiens,id'],
            'start' => ['required', 'date'],
            'end' => ['nullable', 'date'],
        ]);

        $rendezVous = RendezVous::with(['patient.user', 'praticien.user'])->findOrFail($data['rendezvous_id']);
        $start = Carbon::parse($data['start']);
        $end = $data['end'] ? Carbon::parse($data['end']) : (clone $start)->addMinutes($rendezVous->duree ?? 30);
        $dureeMinutes = max(5, $start->diffInMinutes($end));

        $conflict = RendezVous::where('praticien_id', $data['praticien_id'])
            ->where('id', '!=', $rendezVous->id)
            ->whereBetween('date_heure_rdv', [$start->copy()->subMinutes($dureeMinutes), $end])
            ->get()
            ->contains(function (RendezVous $other) use ($start, $end) {
                $otherStart = $other->date_heure_rdv;
                $otherEnd = (clone $otherStart)->addMinutes($other->duree ?? 30);

                return $start < $otherEnd && $end > $otherStart;
            });

        if ($conflict) {
            return response()->json([
                'status' => 'conflict',
                'message' => 'Un autre rendez-vous est déjà planifié sur ce créneau pour ce praticien.',
            ], 409);
        }

        $rendezVous->update([
            'date_heure_rdv' => $start,
            'duree' => $dureeMinutes,
            'praticien_id' => $data['praticien_id'],
        ]);

        return response()->json([
            'status' => 'ok',
            'message' => 'Rendez-vous replanifié avec succès.',
        ]);
    }

    public function agendaPraticien(Praticien $praticien)
    {
        $rendezVous = RendezVous::with(['patient.user', 'consultation'])
            ->where('praticien_id', $praticien->id)
            ->where('date_heure_rdv', '>=', now()->startOfWeek())
            ->where('date_heure_rdv', '<=', now()->endOfWeek()->addWeeks(2))
            ->orderBy('date_heure_rdv')
            ->get();

        return view('secretaire.agenda-praticien', compact('praticien', 'rendezVous'));
    }

    public function facturation()
    {
        $consultationsSansFacture = Consultation::with(['patient.user', 'praticien.user'])
            ->whereDoesntHave('facture')
            ->where('est_validee', true)
            ->orderBy('date_consultation', 'desc')
            ->get();

        $factures = Facture::with(['consultation.patient.user', 'consultation.praticien.user'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('secretaire.facturation', compact('consultationsSansFacture', 'factures'));
    }

    public function genererFacture(Consultation $consultation)
    {
        // Vérifier si une facture existe déjà
        if ($consultation->facture) {
            return redirect()->route('secretaire.facturation')
                ->with('error', 'Une facture existe déjà pour cette consultation.');
        }

        return view('secretaire.generer-facture', compact('consultation'));
    }

    public function storeFacture(Request $request, Consultation $consultation)
    {
        $request->validate([
            'montant' => 'required|numeric|min:0',
            'details' => 'nullable|string',
        ]);

        $facture = Facture::create([
            'consultation_id' => $consultation->id,
            'patient_id' => $consultation->patient_id,
            'praticien_id' => $consultation->praticien_id,
            'numero_facture' => 'FAC-' . now()->format('Ymd') . '-' . str_pad(Facture::count() + 1, 4, '0', STR_PAD_LEFT),
            'date_facture' => now(),
            'montant' => $request->montant,
            'details' => $request->details,
            'statut' => 'EMISE',
            'emise_par' => auth()->user()->secretaire->id,
        ]);

        return redirect()->route('secretaire.facturation')
            ->with('success', 'Facture générée avec succès.');
    }

    public function encaissements()
    {
        $stats = [
            'total_jour' => Paiement::whereDate('created_at', today())->sum('montant'),
            'total_mois' => Paiement::whereMonth('created_at', now()->month)->sum('montant'),
            'nb_paiements_jour' => Paiement::whereDate('created_at', today())->count(),
            'factures_impayees' => Facture::where('statut', 'EMISE')->count(),
        ];

        $paiements = Paiement::with(['facture.consultation.patient.user'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('secretaire.encaissements', compact('stats', 'paiements'));
    }

    private function colorForPracticien(int $praticienId): string
    {
        $palette = [
            '#2563eb', '#16a34a', '#9333ea', '#dc2626', '#f97316',
            '#0ea5e9', '#6366f1', '#db2777', '#f59e0b', '#14b8a6',
        ];

        $index = abs(crc32((string) $praticienId)) % count($palette);

        return $palette[$index];
    }

    private function getWeeklyActivityData(): array
    {
        $days = [];
        $demandes = [];
        $rdvs = [];
        $paiements = [];

        // Récupérer les données des 7 derniers jours
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $days[] = $date->locale('fr')->isoFormat('ddd D');

            // Compter les demandes
            $demandes[] = DemandeRdv::whereDate('created_at', $date->toDateString())->count();

            // Compter les RDV
            $rdvs[] = RendezVous::whereDate('date_heure_rdv', $date->toDateString())
                ->whereIn('statut', ['CONFIRME', 'PLANIFIE', 'EN_COURS', 'TERMINE'])
                ->count();

            // Compter les paiements
            $paiements[] = Paiement::whereDate('created_at', $date->toDateString())->count();
        }

        return [
            'chart_labels' => $days,
            'chart_demandes' => $demandes,
            'chart_rdvs' => $rdvs,
            'chart_paiements' => $paiements,
            'demandes_traitees' => DemandeRdv::whereIn('statut', ['CONFIRMEE', 'PAYEE'])->count(),
            'taux_validation' => 85,
            'taux_satisfaction' => 95,
        ];
    }
}
