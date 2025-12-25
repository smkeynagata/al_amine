<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\DocumentMedical;
use App\Models\Ordonnance;
use App\Models\Examen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class DocumentController extends Controller
{
    public function index(Request $request)
    {
        $patient = auth()->user()->patient;
        
        // Documents médicaux
        $documents = DocumentMedical::where('patient_id', $patient->id)
            ->with(['consultation.praticien.user']);
        
        if ($request->filled('type')) {
            $documents->where('type_document', $request->type);
        }
        
        if ($request->filled('search')) {
            $documents->where('titre', 'like', '%' . $request->search . '%');
        }
        
        $documents = $documents->orderBy('date_document', 'desc')->paginate(12);
        
        // Ordonnances archivées (plus de 3 mois)
        $ordonnances = Ordonnance::whereHas('consultation', function($query) use ($patient) {
            $query->where('patient_id', $patient->id);
        })
        ->with(['consultation.praticien.user'])
        ->where('created_at', '<', now()->subMonths(3))
        ->orderBy('created_at', 'desc')
        ->get();
        
        // Examens
        $examens = Examen::where('patient_id', $patient->id)
            ->with(['praticien.user'])
            ->whereNotNull('fichier_resultat')
            ->orderBy('date_examen', 'desc')
            ->get();
        
        // Statistiques
        $stats = [
            'total_documents' => DocumentMedical::where('patient_id', $patient->id)->count(),
            'certificats' => DocumentMedical::where('patient_id', $patient->id)->where('type_document', 'certificat_medical')->count(),
            'ordonnances' => $ordonnances->count(),
            'examens' => $examens->count(),
        ];
        
        return view('patient.documents.index', compact('documents', 'ordonnances', 'examens', 'stats'));
    }
    
    public function downloadDocument(DocumentMedical $document)
    {
        $patient = auth()->user()->patient;
        
        if ($document->patient_id !== $patient->id) {
            abort(403);
        }
        
        return Storage::disk('public')->download($document->fichier, $document->titre . '.pdf');
    }
    
    public function downloadOrdonnance(Ordonnance $ordonnance)
    {
        $patient = auth()->user()->patient;
        
        if ($ordonnance->consultation->patient_id !== $patient->id) {
            abort(403);
        }
        
        $ordonnance->load(['consultation.patient.user', 'consultation.praticien.user', 'medicaments']);
        
        $pdf = Pdf::loadView('pdf.ordonnance', compact('ordonnance'));
        
        return $pdf->download('ordonnance-' . $ordonnance->created_at->format('Y-m-d') . '.pdf');
    }
    
    public function downloadExamen(Examen $examen)
    {
        $patient = auth()->user()->patient;
        
        if ($examen->patient_id !== $patient->id) {
            abort(403);
        }
        
        if (!$examen->fichier_resultat) {
            abort(404, 'Aucun fichier disponible');
        }
        
        return Storage::disk('public')->download($examen->fichier_resultat, $examen->titre . '.pdf');
    }
    
    public function generateCertificat(Request $request)
    {
        $patient = auth()->user()->patient;
        $patient->load('user');
        
        $data = [
            'patient' => $patient,
            'date' => now(),
            'motif' => $request->input('motif', 'À la demande du patient'),
        ];
        
        $pdf = Pdf::loadView('pdf.certificat-medical', $data);
        
        return $pdf->download('certificat-medical-' . now()->format('Y-m-d') . '.pdf');
    }
    
    public function generateAttestation($consultationId)
    {
        $patient = auth()->user()->patient;
        
        $consultation = \App\Models\Consultation::with(['praticien.user', 'patient.user'])
            ->where('patient_id', $patient->id)
            ->findOrFail($consultationId);
        
        $data = [
            'consultation' => $consultation,
            'patient' => $patient,
            'date' => now(),
        ];
        
        $pdf = Pdf::loadView('pdf.attestation-consultation', $data);
        
        return $pdf->download('attestation-consultation-' . $consultation->created_at->format('Y-m-d') . '.pdf');
    }
}
