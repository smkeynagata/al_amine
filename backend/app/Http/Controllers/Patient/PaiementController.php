<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Paiement;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class PaiementController extends Controller
{
    public function index(Request $request)
    {
        $patient = auth()->user()->patient;
        
        $query = Paiement::where('patient_id', $patient->id)
            ->with(['facture.consultation.praticien.user']);

        // Filtrage par date
        if ($request->filled('date_debut')) {
            $query->whereDate('date_paiement', '>=', $request->date_debut);
        }
        if ($request->filled('date_fin')) {
            $query->whereDate('date_paiement', '<=', $request->date_fin);
        }

        // Filtrage par méthode de paiement
        if ($request->filled('methode')) {
            $query->where('methode_paiement', $request->methode);
        }

        // Filtrage par montant
        if ($request->filled('montant_min')) {
            $query->where('montant', '>=', $request->montant_min);
        }
        if ($request->filled('montant_max')) {
            $query->where('montant', '<=', $request->montant_max);
        }

        $paiements = $query->orderBy('date_paiement', 'desc')->paginate(15);

        // Statistiques
        $stats = [
            'total_paiements' => Paiement::where('patient_id', $patient->id)->count(),
            'montant_total' => Paiement::where('patient_id', $patient->id)->sum('montant'),
            'paiements_mois' => Paiement::where('patient_id', $patient->id)
                ->whereMonth('date_paiement', now()->month)
                ->whereYear('date_paiement', now()->year)
                ->count(),
            'montant_mois' => Paiement::where('patient_id', $patient->id)
                ->whereMonth('date_paiement', now()->month)
                ->whereYear('date_paiement', now()->year)
                ->sum('montant'),
        ];

        // Statistiques par méthode
        $statsMethodes = Paiement::where('patient_id', $patient->id)
            ->selectRaw('methode_paiement, COUNT(*) as count, SUM(montant) as total')
            ->groupBy('methode_paiement')
            ->get();

        return view('patient.paiements.index', compact('paiements', 'stats', 'statsMethodes'));
    }

    public function downloadRecu(Paiement $paiement)
    {
        // Vérifier que le paiement appartient au patient
        if ($paiement->patient_id !== auth()->user()->patient->id) {
            abort(403);
        }

        $paiement->load(['facture.consultation.praticien.user', 'patient.user']);

        $pdf = Pdf::loadView('pdf.recu-paiement', compact('paiement'));
        
        return $pdf->download('recu-paiement-' . $paiement->reference . '.pdf');
    }

    public function show(Paiement $paiement)
    {
        // Vérifier que le paiement appartient au patient
        if ($paiement->patient_id !== auth()->user()->patient->id) {
            abort(403);
        }

        $paiement->load(['facture.consultation.praticien.user', 'patient.user']);

        return view('patient.paiements.detail', compact('paiement'));
    }
}
