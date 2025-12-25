<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\RendezVous;
use App\Models\Consultation;
use App\Models\Facture;
use App\Models\Paiement;
use App\Models\AuditTrail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistiques globales
        $stats = [
            'total_users' => User::count(),
            'patients' => User::where('role', 'PATIENT')->count(),
            'praticiens' => User::where('role', 'PRATICIEN')->count(),
            'secretaires' => User::where('role', 'SECRETAIRE')->count(),
            'rdv_mois' => RendezVous::whereMonth('date_heure_rdv', now()->month)->count(),
            'consultations_mois' => Consultation::whereMonth('date_consultation', now()->month)->count(),
            'ca_mois' => Paiement::whereMonth('date_paiement', now()->month)->sum('montant'),
            'factures_impayees' => Facture::where('statut', '!=', 'PAYEE')->count(),
            'montant_impaye' => Facture::where('statut', '!=', 'PAYEE')->sum('montant_restant'),
        ];

        // Générer tous les mois des 6 derniers mois
        $moisList = collect();
        for ($i = 5; $i >= 0; $i--) {
            $moisList->push(now()->subMonths($i)->startOfMonth());
        }

        // Graphique RDV par mois (6 derniers mois)
        $rdvData = RendezVous::select(
                DB::raw('DATE_TRUNC(\'month\', date_heure_rdv) as mois'),
                DB::raw('COUNT(*) as total')
            )
            ->where('date_heure_rdv', '>=', now()->subMonths(6))
            ->groupBy('mois')
            ->orderBy('mois')
            ->get()
            ->keyBy(fn($item) => \Carbon\Carbon::parse($item->mois)->format('Y-m'));

        // Remplir tous les mois pour RDV
        $rdvParMois = $moisList->map(function($mois) use ($rdvData) {
            $key = $mois->format('Y-m');
            return (object)[
                'mois' => $mois->format('Y-m-d'),
                'total' => $rdvData->has($key) ? $rdvData[$key]->total : 0
            ];
        });

        // Chiffre d'affaires par mois
        $caData = Paiement::select(
                DB::raw('DATE_TRUNC(\'month\', date_paiement) as mois'),
                DB::raw('SUM(montant) as total')
            )
            ->where('date_paiement', '>=', now()->subMonths(6))
            ->groupBy('mois')
            ->orderBy('mois')
            ->get()
            ->keyBy(fn($item) => \Carbon\Carbon::parse($item->mois)->format('Y-m'));

        // Remplir tous les mois pour CA
        $caParMois = $moisList->map(function($mois) use ($caData) {
            $key = $mois->format('Y-m');
            return (object)[
                'mois' => $mois->format('Y-m-d'),
                'total' => $caData->has($key) ? $caData[$key]->total : 0
            ];
        });

        // Dernières activités
        $dernieresActivites = AuditTrail::with('user')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'rdvParMois', 'caParMois', 'dernieresActivites'));
    }

    public function agendasGlobaux()
    {
        $rdv = RendezVous::with(['patient.user', 'praticien.user'])
            ->where('date_heure_rdv', '>=', now()->startOfMonth())
            ->where('date_heure_rdv', '<=', now()->endOfMonth()->addMonths(1))
            ->get();

        return view('admin.agendas-globaux', compact('rdv'));
    }

    public function services()
    {
        $services = \App\Models\Service::with('praticiens.user')->get();

        return view('admin.services', compact('services'));
    }

    public function storeService(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'localisation' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        \App\Models\Service::create([
            'nom' => $request->nom,
            'localisation' => $request->localisation,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.services')->with('success', 'Service créé avec succès !');
    }

    public function destroyService($id)
    {
        $service = \App\Models\Service::findOrFail($id);
        
        // Vérifier qu'il n'y a pas de praticiens associés
        if ($service->praticiens()->count() > 0) {
            return redirect()->route('admin.services')->with('error', 'Impossible de supprimer un service avec des praticiens associés.');
        }

        $service->delete();

        return redirect()->route('admin.services')->with('success', 'Service supprimé avec succès !');
    }

    public function specialites()
    {
        $specialites = \App\Models\Specialite::withCount('praticiens')->get();

        return view('admin.specialites', compact('specialites'));
    }

    public function rapports()
    {
        return view('admin.rapports');
    }

    public function audit(Request $request)
    {
        $audits = AuditTrail::with('user')
            ->when($request->filled('search'), function ($query) use ($request) {
                $term = $request->search;
                $query->where(function ($q) use ($term) {
                    $q->where('action', 'ILIKE', "%{$term}%")
                      ->orWhere('description', 'ILIKE', "%{$term}%")
                      ->orWhereHas('user', function ($userQuery) use ($term) {
                          $userQuery->where('name', 'ILIKE', "%{$term}%")
                              ->orWhere('prenom', 'ILIKE', "%{$term}%")
                              ->orWhere('email', 'ILIKE', "%{$term}%");
                      });
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(50)
            ->withQueryString();

        return view('admin.audit', compact('audits'));
    }

    public function rapportActivite(Request $request)
    {
        $dateDebut = $request->input('date_debut', now()->startOfMonth());
        $dateFin = $request->input('date_fin', now()->endOfMonth());

        $data = [
            'rdv_total' => RendezVous::whereBetween('date_heure_rdv', [$dateDebut, $dateFin])->count(),
            'consultations_total' => Consultation::whereBetween('date_consultation', [$dateDebut, $dateFin])->count(),
            'nouveaux_patients' => User::where('role', 'PATIENT')
                ->whereBetween('created_at', [$dateDebut, $dateFin])
                ->count(),
            'taux_presence' => $this->calculerTauxPresence($dateDebut, $dateFin),
        ];

        // Générer le PDF
        $pdf = \PDF::loadView('admin.rapport-activite-pdf', compact('data', 'dateDebut', 'dateFin'));
        return $pdf->download('rapport-activite-' . now()->format('Y-m-d') . '.pdf');
    }

    public function rapportFinancier(Request $request)
    {
        $dateDebut = $request->input('date_debut', now()->startOfMonth());
        $dateFin = $request->input('date_fin', now()->endOfMonth());

        $data = [
            'ca_total' => Paiement::whereBetween('date_paiement', [$dateDebut, $dateFin])->sum('montant'),
            'factures_emises' => Facture::whereBetween('date_facture', [$dateDebut, $dateFin])->count(),
            'factures_payees' => Facture::where('statut', 'PAYEE')
                ->whereBetween('date_facture', [$dateDebut, $dateFin])
                ->count(),
            'paiements_par_methode' => Paiement::select('methode_paiement', DB::raw('SUM(montant) as total'))
                ->whereBetween('date_paiement', [$dateDebut, $dateFin])
                ->groupBy('methode_paiement')
                ->get(),
        ];

        // Générer le PDF
        $pdf = \PDF::loadView('admin.rapport-financier-pdf', compact('data', 'dateDebut', 'dateFin'));
        return $pdf->download('rapport-financier-' . now()->format('Y-m-d') . '.pdf');
    }

    private function calculerTauxPresence($dateDebut, $dateFin)
    {
        $rdvTotal = RendezVous::whereBetween('date_heure_rdv', [$dateDebut, $dateFin])->count();
        $rdvTermines = RendezVous::where('statut', 'TERMINE')
            ->whereBetween('date_heure_rdv', [$dateDebut, $dateFin])
            ->count();

        return $rdvTotal > 0 ? round(($rdvTermines / $rdvTotal) * 100, 2) : 0;
    }
}

