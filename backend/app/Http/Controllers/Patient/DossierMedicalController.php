<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use App\Models\Ordonnance;
use App\Models\Examen;
use App\Models\DocumentMedical;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class DossierMedicalController extends Controller
{
    public function index()
    {
        $patient = auth()->user()->patient;
        
        // Récupérer toutes les données du dossier médical
        $consultations = $patient->consultations()
            ->with(['praticien.user', 'rendezVous'])
            ->orderBy('date_consultation', 'desc')
            ->paginate(10);
        
        $ordonnances = $patient->ordonnances()
            ->with(['praticien.user', 'consultation'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        $examens = $patient->examens()
            ->with(['praticien.user', 'consultation'])
            ->orderBy('date_examen', 'desc')
            ->get();
        
        $documents = $patient->documentsMedicaux()
            ->with(['praticien.user'])
            ->orderBy('date_document', 'desc')
            ->get();

        return view('patient.dossier-medical.index', compact(
            'consultations',
            'ordonnances',
            'examens',
            'documents'
        ));
    }

    public function showConsultation(Consultation $consultation)
    {
        // Vérifier que la consultation appartient au patient
        if ($consultation->patient_id !== auth()->user()->patient->id) {
            abort(403);
        }

        return view('patient.dossier-medical.consultation-detail', compact('consultation'));
    }

    public function downloadOrdonnance(Ordonnance $ordonnance)
    {
        // Vérifier que l'ordonnance appartient au patient
        if ($ordonnance->patient_id !== auth()->user()->patient->id) {
            abort(403);
        }

        // Générer le PDF
        $pdf = Pdf::loadView('pdf.ordonnance', compact('ordonnance'));
        
        return $pdf->download('ordonnance_' . $ordonnance->id . '.pdf');
    }

    public function downloadExamen(Examen $examen)
    {
        // Vérifier que l'examen appartient au patient
        if ($examen->patient_id !== auth()->user()->patient->id) {
            abort(403);
        }

        if (!$examen->fichier_resultat) {
            return back()->with('error', 'Aucun fichier disponible pour cet examen.');
        }

        return response()->download(storage_path('app/public/' . $examen->fichier_resultat));
    }

    public function downloadDocument(DocumentMedical $document)
    {
        // Vérifier que le document appartient au patient
        if ($document->patient_id !== auth()->user()->patient->id) {
            abort(403);
        }

        return response()->download(storage_path('app/public/' . $document->fichier));
    }

    public function allergiesAntecedents()
    {
        $patient = auth()->user()->patient;
        
        return view('patient.dossier-medical.allergies-antecedents', compact('patient'));
    }

    public function updateAllergiesAntecedents(Request $request)
    {
        $validated = $request->validate([
            'allergies' => 'nullable|string',
            'antecedents' => 'nullable|string',
        ]);

        auth()->user()->patient->update($validated);

        return redirect()->route('patient.dossier-medical.allergies-antecedents')
            ->with('success', 'Vos allergies et antécédents ont été mis à jour avec succès.');
    }
}
