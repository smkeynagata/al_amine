<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\MesureSante;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SuiviSanteController extends Controller
{
    public function index(Request $request)
    {
        $patient = auth()->user()->patient;
        
        $periode = $request->input('periode', '6mois'); // 1mois, 3mois, 6mois, 1an, tout
        
        $query = MesureSante::where('patient_id', $patient->id);
        
        switch ($periode) {
            case '1mois':
                $query->where('date_mesure', '>=', now()->subMonth());
                break;
            case '3mois':
                $query->where('date_mesure', '>=', now()->subMonths(3));
                break;
            case '6mois':
                $query->where('date_mesure', '>=', now()->subMonths(6));
                break;
            case '1an':
                $query->where('date_mesure', '>=', now()->subYear());
                break;
        }
        
        $mesures = $query->orderBy('date_mesure', 'desc')->get();
        
        // Statistiques
        $stats = [
            'total_mesures' => $mesures->count(),
            'poids_actuel' => $mesures->where('poids', '!=', null)->first()?->poids,
            'tension_actuelle' => $mesures->where('tension_systolique', '!=', null)->first(),
            'derniere_mesure' => $mesures->first()?->date_mesure,
        ];
        
        // Données pour les graphiques
        $chartData = $this->prepareChartData($mesures);
        
        return view('patient.suivi-sante.index', compact('mesures', 'stats', 'chartData', 'periode'));
    }
    
    private function prepareChartData($mesures)
    {
        return [
            'dates' => $mesures->pluck('date_mesure')->map(fn($d) => $d->format('d/m/Y'))->reverse()->values(),
            'poids' => $mesures->pluck('poids')->reverse()->values(),
            'tension_systolique' => $mesures->pluck('tension_systolique')->reverse()->values(),
            'tension_diastolique' => $mesures->pluck('tension_diastolique')->reverse()->values(),
            'frequence_cardiaque' => $mesures->pluck('frequence_cardiaque')->reverse()->values(),
            'temperature' => $mesures->pluck('temperature')->reverse()->values(),
            'glycemie' => $mesures->pluck('glycemie')->reverse()->values(),
        ];
    }
}
