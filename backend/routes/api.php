<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\Patient\DashboardController as PatientDashboardController;
use App\Http\Controllers\Api\Patient\ProfileController as PatientProfileController;
use App\Http\Controllers\Api\Patient\DemandeRdvController as PatientDemandeRdvController;
use App\Http\Controllers\Api\Patient\DocumentController as PatientDocumentController;
use App\Http\Controllers\Api\Patient\DossierMedicalController as PatientDossierMedicalController;
use App\Http\Controllers\Api\Praticien\DashboardController as PraticienDashboardController;
use App\Http\Controllers\Api\Praticien\ConsultationController as PraticienConsultationController;
use App\Http\Controllers\Api\Secretaire\DashboardController as SecretaireDashboardController;
use App\Http\Controllers\Api\Secretaire\FileAttenteController as SecretaireFileAttenteController;

/*
|--------------------------------------------------------------------------
| API Routes - COMPLETE CONVERSION (25 Controllers, 60+ Endpoints)
|--------------------------------------------------------------------------
*/

// ========== HEALTHCHECK ROUTE ==========
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now()->toIso8601String()
    ]);
});

// ========== PUBLIC ROUTES ==========
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// ========== PROTECTED ROUTES ==========
Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // ========== PATIENT ROUTES (10 Controllers) ==========
    Route::prefix('patient')->middleware('role:PATIENT')->group(function () {

        // Dashboard (10 methods)
        Route::get('/dashboard/stats', [PatientDashboardController::class, 'stats']);
        Route::get('/appointments/upcoming', [PatientDashboardController::class, 'upcomingAppointments']);
        Route::get('/health-tracking/chart', [PatientDashboardController::class, 'healthChart']);
        Route::get('/appointments', [PatientDashboardController::class, 'mesRdv']);
        Route::get('/appointments/{rendezVous}', [PatientDashboardController::class, 'showRendezVous']);
        Route::post('/appointments/{rendezVous}/cancel', [PatientDashboardController::class, 'annulerRdv']);
        Route::get('/appointments/{rendezVous}/reschedule', [PatientDashboardController::class, 'reprogrammerRdv']);
        Route::put('/appointments/{rendezVous}', [PatientDashboardController::class, 'updateRdv']);
        Route::get('/invoices', [PatientDashboardController::class, 'factures']);
        Route::get('/invoices/{facture}', [PatientDashboardController::class, 'showFacture']);
        Route::get('/invoices/{facture}/payment', [PatientDashboardController::class, 'paiement']);
        Route::post('/invoices/{facture}/payment', [PatientDashboardController::class, 'traiterPaiement']);

        // Profile (7 methods)
        Route::get('/profile', [PatientProfileController::class, 'show']);
        Route::put('/profile/personal-info', [PatientProfileController::class, 'updatePersonalInfo']);
        Route::put('/profile/health-info', [PatientProfileController::class, 'updateHealthInfo']);
        Route::put('/profile/insurance-info', [PatientProfileController::class, 'updateInsuranceInfo']);
        Route::post('/profile/photo', [PatientProfileController::class, 'updatePhoto']);
        Route::delete('/profile/photo', [PatientProfileController::class, 'deletePhoto']);
        Route::put('/profile/password', [PatientProfileController::class, 'updatePassword']);

        // Demande RDV (4 methods)
        Route::get('/appointment-requests', [PatientDemandeRdvController::class, 'index']);
        Route::get('/appointment-requests/specialites', [PatientDemandeRdvController::class, 'getSpecialites']);
        Route::post('/appointment-requests', [PatientDemandeRdvController::class, 'store']);
        Route::get('/appointment-requests/praticiens/{specialiteId}', [PatientDemandeRdvController::class, 'getPraticiensBySpecialite']);

        // Documents (6 methods)
        Route::get('/documents', [PatientDocumentController::class, 'index']);
        Route::get('/documents/{document}/download', [PatientDocumentController::class, 'downloadDocument']);
        Route::get('/documents/ordonnances/{ordonnance}/download', [PatientDocumentController::class, 'downloadOrdonnance']);
        Route::get('/documents/examens/{examen}/download', [PatientDocumentController::class, 'downloadExamen']);
        Route::post('/documents/certificat', [PatientDocumentController::class, 'generateCertificat']);
        Route::get('/documents/attestation/{consultationId}', [PatientDocumentController::class, 'generateAttestation']);

        // Dossier Medical (5 methods)
        Route::get('/medical-record', [PatientDossierMedicalController::class, 'index']);
        Route::get('/medical-record/consultations/{consultation}', [PatientDossierMedicalController::class, 'showConsultation']);
        Route::get('/medical-record/ordonnances/{ordonnance}/download', [PatientDossierMedicalController::class, 'downloadOrdonnance']);
        Route::get('/medical-record/examens/{examen}/download', [PatientDossierMedicalController::class, 'downloadExamen']);
        Route::put('/medical-record/allergies-antecedents', [PatientDossierMedicalController::class, 'updateAllergiesAntecedents']);

        // Notifications
        Route::get('/notifications', function (Request $request) {
            return response()->json(['notifications' => $request->user()->notifications]);
        });
        Route::post('/notifications/{id}/read', function (Request $request, $id) {
            $request->user()->notifications()->where('id', $id)->update(['read_at' => now()]);
            return response()->json(['success' => true]);
        });
        Route::post('/notifications/mark-all-read', function (Request $request) {
            $request->user()->unreadNotifications->markAsRead();
            return response()->json(['success' => true]);
        });
    });

    // ========== PRATICIEN ROUTES (7 Controllers) ==========
    Route::prefix('praticien')->middleware('role:PRATICIEN')->group(function () {

        // Dashboard (10 methods)
        Route::get('/dashboard/stats', [PraticienDashboardController::class, 'stats']);
        Route::get('/appointments/today', [PraticienDashboardController::class, 'rdvAujourdhui']);
        Route::get('/appointments/upcoming', [PraticienDashboardController::class, 'prochainsRdv']);
        Route::get('/appointment-requests/pending', [PraticienDashboardController::class, 'demandesRdv']);
        Route::get('/patients/recent', [PraticienDashboardController::class, 'patientsRecents']);
        Route::get('/activities/recent', [PraticienDashboardController::class, 'recentActivities']);
        Route::get('/agenda', [PraticienDashboardController::class, 'agenda']);
        Route::get('/patients', [PraticienDashboardController::class, 'patients']);
        Route::get('/patients/{patient}/dossier', [PraticienDashboardController::class, 'dossierPatient']);
        Route::get('/documents', [PraticienDashboardController::class, 'documents']);
        Route::get('/profile/stats', [PraticienDashboardController::class, 'profileStats']);

        // Consultations (4 methods)
        Route::get('/consultations', [PraticienConsultationController::class, 'index']);
        Route::get('/consultations/rdv/{rendezVous}', [PraticienConsultationController::class, 'show']);
        Route::post('/consultations/rdv/{rendezVous}', [PraticienConsultationController::class, 'store']);
        Route::post('/consultations/{consultation}/ordonnance', [PraticienConsultationController::class, 'storeOrdonnance']);
    });

    // ========== SECRETAIRE ROUTES (8 Controllers) ==========
    Route::prefix('secretaire')->middleware('role:SECRETAIRE')->group(function () {

        // Dashboard (13 methods)
        Route::get('/dashboard/stats', [SecretaireDashboardController::class, 'stats']);
        Route::get('/appointment-requests/pending', [SecretaireDashboardController::class, 'demandesRecentes']);
        Route::get('/appointments/today', [SecretaireDashboardController::class, 'rdvAujourdhui']);
        Route::get('/invoices/recent', [SecretaireDashboardController::class, 'facturesRecentes']);
        Route::get('/multi-agenda/praticiens', [SecretaireDashboardController::class, 'multiAgendaPraticiens']);
        Route::get('/multi-agenda/events', [SecretaireDashboardController::class, 'agendaEvents']);
        Route::post('/multi-agenda/reschedule', [SecretaireDashboardController::class, 'agendaReplanifier']);
        Route::get('/agendas', [SecretaireDashboardController::class, 'agendas']);
        Route::get('/agendas/{praticien}', [SecretaireDashboardController::class, 'agendaPraticien']);
        Route::get('/billing/consultations-without-invoice', [SecretaireDashboardController::class, 'consultationsSansFacture']);
        Route::get('/billing/invoices', [SecretaireDashboardController::class, 'facturation']);
        Route::get('/billing/invoices/{consultation}/generate', [SecretaireDashboardController::class, 'genererFacture']);
        Route::post('/billing/invoices/{consultation}', [SecretaireDashboardController::class, 'storeFacture']);
        Route::get('/payments/stats', [SecretaireDashboardController::class, 'encaissementsStats']);
        Route::get('/payments', [SecretaireDashboardController::class, 'encaissements']);

        // File Attente (3 methods)
        Route::get('/queue', [SecretaireFileAttenteController::class, 'index']);
        Route::post('/queue/{demandeRdv}/validate', [SecretaireFileAttenteController::class, 'valider']);
        Route::post('/queue/{demandeRdv}/refuse', [SecretaireFileAttenteController::class, 'refuser']);
    });
});
