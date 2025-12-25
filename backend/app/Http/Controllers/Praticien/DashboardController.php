<?php

namespace App\Http\Controllers\Praticien;

use App\Http\Controllers\Controller;
use App\Models\Praticien;
use App\Models\RendezVous;
use App\Models\Consultation;
use App\Models\DemandeRdv;
use App\Models\Patient;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $praticien = auth()->user()->praticien;

        // Statistiques
        $stats = [
            'rdv_aujourdhui' => RendezVous::where('praticien_id', $praticien->id)
                ->whereDate('date_heure_rdv', today())
                ->whereIn('statut', ['CONFIRME', 'PLANIFIE', 'EN_COURS'])
                ->count(),
            'consultations_mois' => Consultation::where('praticien_id', $praticien->id)
                ->whereMonth('date_consultation', now()->month)
                ->count(),
            'patients_total' => Consultation::where('praticien_id', $praticien->id)
                ->distinct('patient_id')
                ->count('patient_id'),
            'rdv_a_venir' => RendezVous::where('praticien_id', $praticien->id)
                ->where('date_heure_rdv', '>', now())
                ->where('date_heure_rdv', '<=', now()->addDays(7))
                ->whereIn('statut', ['CONFIRME', 'PLANIFIE'])
                ->count(),
        ];

        // RDV du jour
        $rdvAujourdhui = RendezVous::with(['patient.user', 'consultation'])
            ->where('praticien_id', $praticien->id)
            ->whereDate('date_heure_rdv', today())
            ->whereIn('statut', ['CONFIRME', 'PLANIFIE', 'EN_COURS'])
            ->orderBy('date_heure_rdv')
            ->get();

        // Prochains RDV (7 jours)
        $prochainsRdv = RendezVous::with(['patient.user'])
            ->where('praticien_id', $praticien->id)
            ->where('date_heure_rdv', '>', now())
            ->where('date_heure_rdv', '<=', now()->addDays(7))
            ->whereIn('statut', ['CONFIRME', 'PLANIFIE'])
            ->orderBy('date_heure_rdv')
            ->take(5)
            ->get();

        // Demandes de RDV en attente
        $demandesRdv = DemandeRdv::with(['patient.user', 'praticien.user'])
            ->where('praticien_id', $praticien->id)
            ->where('statut', 'EN_ATTENTE')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Patients récents (qui ont consulté récemment)
        $patientsRecents = Patient::with('user')
            ->whereHas('consultations', function($query) use ($praticien) {
                $query->where('praticien_id', $praticien->id);
            })
            ->withCount(['consultations' => function($query) use ($praticien) {
                $query->where('praticien_id', $praticien->id);
            }])
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get();

        // Notifications récentes (activités)
        $notifications = $this->getRecentActivities($praticien->id);

        return view('praticien.dashboard', compact('stats', 'rdvAujourdhui', 'prochainsRdv', 'demandesRdv', 'patientsRecents', 'notifications'));
    }

    private function getRecentActivities($praticienId)
    {
        $activities = collect();

        // RDV confirmés récemment
        $rdvConfirmes = RendezVous::with('patient.user')
            ->where('praticien_id', $praticienId)
            ->where('statut', 'CONFIRME')
            ->where('updated_at', '>', now()->subHours(24))
            ->orderBy('updated_at', 'desc')
            ->limit(3)
            ->get()
            ->map(function($rdv) {
                return [
                    'type' => 'rdv_confirme',
                    'icon' => '✓',
                    'color' => 'green',
                    'title' => 'RDV confirmé',
                    'description' => $rdv->patient->user->nom_complet . ' - ' . $rdv->date_heure_rdv->format('d/m H:i'),
                    'time' => $rdv->updated_at->diffForHumans(),
                    'created_at' => $rdv->updated_at,
                ];
            });

        // Consultations terminées récemment
        $consultationsTerminees = Consultation::with('patient.user')
            ->where('praticien_id', $praticienId)
            ->where('created_at', '>', now()->subHours(24))
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get()
            ->map(function($consultation) {
                return [
                    'type' => 'consultation_terminee',
                    'icon' => '✓',
                    'color' => 'blue',
                    'title' => 'Consultation terminée',
                    'description' => $consultation->patient->user->nom_complet,
                    'time' => $consultation->created_at->diffForHumans(),
                    'created_at' => $consultation->created_at,
                ];
            });

        // Nouvelles demandes de RDV
        $nouvellesDemandes = DemandeRdv::with('patient.user')
            ->where('praticien_id', $praticienId)
            ->where('statut', 'EN_ATTENTE')
            ->where('created_at', '>', now()->subHours(24))
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get()
            ->map(function($demande) {
                return [
                    'type' => 'nouvelle_demande',
                    'icon' => '📅',
                    'color' => 'orange',
                    'title' => 'Nouvelle demande de RDV',
                    'description' => $demande->patient->user->nom_complet . ' - ' . $demande->date_heure_souhaitee->format('d/m H:i'),
                    'time' => $demande->created_at->diffForHumans(),
                    'created_at' => $demande->created_at,
                ];
            });

        // Ordonnances créées récemment
        $ordonnancesRecentes = Consultation::with('patient.user', 'ordonnances')
            ->where('praticien_id', $praticienId)
            ->whereHas('ordonnances')
            ->where('created_at', '>', now()->subHours(24))
            ->orderBy('created_at', 'desc')
            ->limit(2)
            ->get()
            ->map(function($consultation) {
                return [
                    'type' => 'ordonnance_creee',
                    'icon' => '📋',
                    'color' => 'purple',
                    'title' => 'Ordonnance créée',
                    'description' => $consultation->patient->user->nom_complet,
                    'time' => $consultation->created_at->diffForHumans(),
                    'created_at' => $consultation->created_at,
                ];
            });

        // Combiner toutes les activités et trier par date
        $activities = $rdvConfirmes
            ->concat($consultationsTerminees)
            ->concat($nouvellesDemandes)
            ->concat($ordonnancesRecentes)
            ->sortByDesc('created_at')
            ->take(5);

        return $activities;
    }

    public function agenda()
    {
        $praticien = auth()->user()->praticien;

        $rendezVous = RendezVous::with(['patient.user', 'consultation'])
            ->where('praticien_id', $praticien->id)
            ->where('date_heure_rdv', '>=', now()->startOfWeek())
            ->where('date_heure_rdv', '<=', now()->endOfWeek()->addWeeks(2))
            ->whereIn('statut', ['CONFIRME', 'PLANIFIE', 'EN_COURS'])
            ->orderBy('date_heure_rdv')
            ->get();

        // RDV du jour
        $rdvAujourdhui = RendezVous::with(['patient.user', 'consultation'])
            ->where('praticien_id', $praticien->id)
            ->whereDate('date_heure_rdv', today())
            ->whereIn('statut', ['CONFIRME', 'PLANIFIE', 'EN_COURS'])
            ->orderBy('date_heure_rdv')
            ->get();

        $prochainsRdv = RendezVous::with(['patient.user'])
            ->where('praticien_id', $praticien->id)
            ->where('date_heure_rdv', '>=', now())
            ->whereIn('statut', ['CONFIRME', 'PLANIFIE'])
            ->orderBy('date_heure_rdv')
            ->take(10)
            ->get();

        return view('praticien.agenda', compact('rendezVous', 'rdvAujourdhui', 'prochainsRdv'));
    }

    public function profile()
    {
        $praticien = auth()->user()->praticien;

        // Statistiques pour le profil
        $stats = [
            'total_consultations' => Consultation::where('praticien_id', $praticien->id)->count(),
            'total_patients' => Consultation::where('praticien_id', $praticien->id)
                ->distinct('patient_id')
                ->count('patient_id'),
            'consultations_mois' => Consultation::where('praticien_id', $praticien->id)
                ->whereMonth('date_consultation', now()->month)
                ->count(),
        ];

        return view('praticien.profile', compact('stats'));
    }

    public function patients()
    {
        $praticien = auth()->user()->praticien;

        // Récupérer tous les patients uniques qui ont consulté ce praticien
        $patients = Consultation::with(['patient.user'])
            ->where('praticien_id', $praticien->id)
            ->get()
            ->pluck('patient')
            ->unique('id')
            ->values();

        // Statistiques des patients
        $stats = [
            'total_patients' => $patients->count(),
            'nouveaux_mois' => Consultation::where('praticien_id', $praticien->id)
                ->whereMonth('date_consultation', now()->month)
                ->distinct('patient_id')
                ->count('patient_id'),
            'consultations_total' => Consultation::where('praticien_id', $praticien->id)->count(),
        ];

        return view('praticien.patients', compact('patients', 'stats'));
    }

    public function documents()
    {
        $praticien = auth()->user()->praticien;

        // Récupérer les ordonnances et documents
        $ordonnances = Consultation::with(['patient.user', 'ordonnances'])
            ->where('praticien_id', $praticien->id)
            ->whereHas('ordonnances')
            ->latest('date_consultation')
            ->get();

        $stats = [
            'total_ordonnances' => $ordonnances->sum(fn($c) => $c->ordonnances->count()),
            'ordonnances_mois' => Consultation::where('praticien_id', $praticien->id)
                ->whereHas('ordonnances')
                ->whereMonth('date_consultation', now()->month)
                ->count(),
        ];

        return view('praticien.documents', compact('ordonnances', 'stats'));
    }

    public function chats()
    {
        $praticien = auth()->user()->praticien;

        // Pour l'instant, on retourne une vue simple
        // Plus tard, on pourra ajouter la logique de messagerie réelle
        return view('praticien.chats');
    }

    public function dossierPatient(Patient $patient)
    {
        $praticien = auth()->user()->praticien;

        // Vérifier que ce patient a consulté ce praticien
        $hasConsulted = $patient->consultations()
            ->where('praticien_id', $praticien->id)
            ->exists();

        if (!$hasConsulted) {
            abort(403, 'Vous n\'avez pas accès au dossier de ce patient.');
        }

        // Récupérer toutes les consultations
        $consultations = Consultation::with(['praticien.user', 'ordonnances'])
            ->where('patient_id', $patient->id)
            ->where('praticien_id', $praticien->id)
            ->orderBy('date_consultation', 'desc')
            ->get();

        // Récupérer les rendez-vous
        $rendezVous = RendezVous::with('praticien.user')
            ->where('patient_id', $patient->id)
            ->where('praticien_id', $praticien->id)
            ->whereIn('statut', ['CONFIRME', 'PLANIFIE', 'EN_COURS', 'TERMINE'])
            ->orderBy('date_heure_rdv', 'desc')
            ->get();

        // Statistiques du patient
        $stats = [
            'total_consultations' => $consultations->count(),
            'total_ordonnances' => $consultations->sum(fn($c) => $c->ordonnances->count()),
            'derniere_consultation' => $consultations->first()?->date_consultation,
            'prochain_rdv' => $rendezVous->where('date_heure_rdv', '>', now())->first(),
        ];

        return view('praticien.patient-dossier', compact('patient', 'consultations', 'rendezVous', 'stats'));
    }

    private function getColorByStatut($statut)
    {
        return match($statut) {
            'PLANIFIE' => '#F59E0B',
            'CONFIRME' => '#10B981',
            'EN_COURS' => '#3B82F6',
            'TERMINE' => '#059669',
            'ANNULE' => '#EF4444',
            'ABSENT' => '#6B7280',
            default => '#9CA3AF',
        };
    }
}

