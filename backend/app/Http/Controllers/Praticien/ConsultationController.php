<?php

namespace App\Http\Controllers\Praticien;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use App\Models\RendezVous;
use App\Models\Ordonnance;
use App\Models\MesureSante;
use Illuminate\Http\Request;

class ConsultationController extends Controller
{
    public function index()
    {
        $praticien = auth()->user()->praticien;

        $consultations = Consultation::with(['rendezVous.patient.user'])
            ->where('praticien_id', $praticien->id)
            ->orderBy('date_consultation', 'desc')
            ->paginate(15);

        return view('praticien.consultations', compact('consultations'));
    }

    public function show(RendezVous $rendezVous)
    {
        // Vérifier que le RDV appartient au praticien connecté
        if ($rendezVous->praticien_id !== auth()->user()->praticien->id) {
            abort(403);
        }

        $rendezVous->load(['patient.user', 'consultation']);

        return view('praticien.consultation-show', compact('rendezVous'));
    }

    public function store(Request $request, RendezVous $rendezVous)
    {
        // Vérifier que le RDV appartient au praticien connecté
        if ($rendezVous->praticien_id !== auth()->user()->praticien->id) {
            abort(403);
        }

        $request->validate([
            'motif' => 'required|string|max:500',
            'diagnostic' => 'required|string|max:1000',
            'traitement' => 'nullable|string|max:2000',
            'examens_complementaires' => 'nullable|string|max:1000',
            'observations' => 'nullable|string|max:2000',
            // Validation des mesures de santé
            'poids' => 'nullable|numeric|min:1|max:500',
            'taille' => 'nullable|numeric|min:1|max:300',
            'temperature' => 'nullable|numeric|min:30|max:45',
            'tension_systolique' => 'nullable|integer|min:1|max:300',
            'tension_diastolique' => 'nullable|integer|min:1|max:200',
            'frequence_cardiaque' => 'nullable|integer|min:1|max:300',
            'notes_mesures' => 'nullable|string|max:500',
        ]);

        // Créer la consultation
        $consultation = Consultation::create([
            'rendez_vous_id' => $rendezVous->id,
            'patient_id' => $rendezVous->patient_id,
            'praticien_id' => $rendezVous->praticien_id,
            'date_consultation' => now(),
            'motif' => $request->motif,
            'diagnostic' => $request->diagnostic,
            'traitement' => $request->traitement,
            'examens_complementaires' => $request->examens_complementaires,
            'observations' => $request->observations,
        ]);

        // Enregistrer les mesures de santé si au moins une mesure est fournie
        if ($request->filled(['poids']) || $request->filled(['taille']) || 
            $request->filled(['temperature']) || $request->filled(['tension_systolique']) || 
            $request->filled(['frequence_cardiaque'])) {
            
            MesureSante::create([
                'patient_id' => $rendezVous->patient_id,
                'date_mesure' => now()->toDateString(),
                'poids' => $request->poids,
                'taille' => $request->taille,
                'temperature' => $request->temperature,
                'tension_systolique' => $request->tension_systolique,
                'tension_diastolique' => $request->tension_diastolique,
                'frequence_cardiaque' => $request->frequence_cardiaque,
                'notes' => $request->notes_mesures,
            ]);
        }

        // Mettre à jour le statut du RDV
        $rendezVous->update(['statut' => 'TERMINE']);

        return redirect()->route('praticien.consultations')
            ->with('success', 'Consultation enregistrée avec succès.');
    }

    public function ordonnance(Consultation $consultation)
    {
        // Vérifier que la consultation appartient au praticien connecté
        if ($consultation->praticien_id !== auth()->user()->praticien->id) {
            abort(403);
        }

        $consultation->load(['patient.user', 'praticien.user', 'rendezVous', 'ordonnances']);

        return view('praticien.ordonnance', compact('consultation'));
    }

    public function storeOrdonnance(Request $request, Consultation $consultation)
    {
        // Vérifier que la consultation appartient au praticien connecté
        if ($consultation->praticien_id !== auth()->user()->praticien->id) {
            abort(403);
        }

        $request->validate([
            'medicaments' => 'required|array|min:1',
            'medicaments.*.nom' => 'required|string',
            'medicaments.*.dosage' => 'required|string',
            'medicaments.*.duree' => 'required|string',
            'medicaments.*.instructions' => 'nullable|string',
        ]);

        // Créer l'ordonnance
        $ordonnance = Ordonnance::create([
            'consultation_id' => $consultation->id,
            'patient_id' => $consultation->patient_id,
            'praticien_id' => $consultation->praticien_id,
            'date_ordonnance' => now(),
            'medicaments' => $request->medicaments,
            'instructions_generales' => $request->instructions_generales,
        ]);

        return redirect()->route('praticien.consultations')
            ->with('success', 'Ordonnance créée avec succès.');
    }
}
