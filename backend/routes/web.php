<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Patient\DashboardController as PatientDashboardController;
use App\Http\Controllers\Patient\DemandeRdvController;
use App\Http\Controllers\Patient\ProfileController as PatientProfileController;
use App\Http\Controllers\Patient\DossierMedicalController;
use App\Http\Controllers\Patient\NotificationController as PatientNotificationController;
use App\Http\Controllers\Praticien\DashboardController as PraticienDashboardController;
use App\Http\Controllers\Praticien\DisponibiliteController;
use App\Http\Controllers\Praticien\ConsultationController;
use App\Http\Controllers\Praticien\RendezVousController;
use App\Http\Controllers\Secretaire\DashboardController as SecretaireDashboardController;
use App\Http\Controllers\Secretaire\FileAttenteController;
use App\Http\Controllers\Secretaire\RendezVousController as SecretaireRendezVousController;
use App\Http\Controllers\Secretaire\ReminderController;
use App\Http\Controllers\Secretaire\ProfileController as SecretaireProfileController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\PaydunyaWebhookController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing');
})->name('home');

// Routes PayDunya (sans authentification pour les webhooks)
Route::post('/paydunya/ipn', [PaydunyaWebhookController::class, 'handleIPN'])->name('paydunya.ipn');
Route::get('/paydunya/return', [PaydunyaWebhookController::class, 'paymentReturn'])->name('paydunya.return');
Route::get('/paydunya/cancel', [PaydunyaWebhookController::class, 'paymentCancel'])->name('paydunya.cancel');

// Route de test webhook Stripe (à supprimer en production)
require __DIR__.'/test-webhook.php';

// Redirection après login selon le rôle
Route::get('/dashboard', function () {
    $user = auth()->user();

    $redirectRoute = match($user->role) {
        'ADMIN' => 'admin.dashboard',
        'PATIENT' => 'patient.dashboard',
        'PRATICIEN' => 'praticien.dashboard',
        'SECRETAIRE' => 'secretaire.dashboard',
        default => 'home',
    };

    return redirect()->route($redirectRoute);
})->middleware(['auth', 'verified'])->name('dashboard');

// Routes PATIENT
Route::middleware(['auth', 'role:PATIENT'])->prefix('patient')->name('patient.')->group(function () {
    Route::get('/dashboard', [PatientDashboardController::class, 'index'])->name('dashboard');
    
    // Profil
    Route::get('/profile', [PatientProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile/personal', [PatientProfileController::class, 'updatePersonalInfo'])->name('profile.update.personal');
    Route::patch('/profile/health', [PatientProfileController::class, 'updateHealthInfo'])->name('profile.update.health');
    Route::patch('/profile/insurance', [PatientProfileController::class, 'updateInsuranceInfo'])->name('profile.update.insurance');
    Route::post('/profile/photo', [PatientProfileController::class, 'updatePhoto'])->name('profile.update.photo');
    Route::delete('/profile/photo', [PatientProfileController::class, 'deletePhoto'])->name('profile.delete.photo');
    Route::patch('/profile/password', [PatientProfileController::class, 'updatePassword'])->name('profile.update.password');
    
    // Dossier médical
    Route::get('/dossier-medical', [DossierMedicalController::class, 'index'])->name('dossier-medical');
    Route::get('/dossier-medical/consultation/{consultation}', [DossierMedicalController::class, 'showConsultation'])->name('dossier-medical.consultation');
    Route::get('/dossier-medical/ordonnance/{ordonnance}/download', [DossierMedicalController::class, 'downloadOrdonnance'])->name('dossier-medical.ordonnance.download');
    Route::get('/dossier-medical/examen/{examen}/download', [DossierMedicalController::class, 'downloadExamen'])->name('dossier-medical.examen.download');
    Route::get('/dossier-medical/document/{document}/download', [DossierMedicalController::class, 'downloadDocument'])->name('dossier-medical.document.download');
    Route::get('/dossier-medical/allergies-antecedents', [DossierMedicalController::class, 'allergiesAntecedents'])->name('dossier-medical.allergies-antecedents');
    Route::patch('/dossier-medical/allergies-antecedents', [DossierMedicalController::class, 'updateAllergiesAntecedents'])->name('dossier-medical.allergies-antecedents.update');
    
    // Notifications
    Route::get('/notifications', [PatientNotificationController::class, 'index'])->name('notifications');
    Route::post('/notifications/{notification}/mark-as-read', [PatientNotificationController::class, 'markAsRead'])->name('notifications.mark-as-read');
    Route::post('/notifications/mark-all-as-read', [PatientNotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-as-read');
    Route::delete('/notifications/{notification}', [PatientNotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::get('/notifications/unread-count', [PatientNotificationController::class, 'getUnreadCount'])->name('notifications.unread-count');
    
    // RDV
    Route::get('/demander-rdv', [DemandeRdvController::class, 'create'])->name('demander-rdv');
    Route::post('/demander-rdv', [DemandeRdvController::class, 'store'])->name('demander-rdv.store');
    Route::get('/mes-demandes', [DemandeRdvController::class, 'index'])->name('mes-demandes');
    Route::get('/mes-rdv', [PatientDashboardController::class, 'mesRdv'])->name('mes-rdv');
    Route::get('/rendez-vous/{rendezVous}', [PatientDashboardController::class, 'showRendezVous'])->name('rendezvous.show');
    Route::post('/rendez-vous/{rendezVous}/annuler', [PatientDashboardController::class, 'annulerRdv'])->name('rendezvous.annuler');
    Route::get('/rendez-vous/{rendezVous}/reprogrammer', [PatientDashboardController::class, 'reprogrammerRdv'])->name('rendezvous.reprogrammer');
    Route::patch('/rendez-vous/{rendezVous}/update', [PatientDashboardController::class, 'updateRdv'])->name('rendezvous.update');
    
    // Factures et paiements
    Route::get('/factures', [PatientDashboardController::class, 'factures'])->name('factures');
    Route::get('/facture/{facture}', [PatientDashboardController::class, 'showFacture'])->name('facture.show');
    Route::get('/paiement/{facture}', [PatientDashboardController::class, 'paiement'])->name('paiement');
    Route::post('/paiement/{facture}', [PatientDashboardController::class, 'traiterPaiement'])->name('paiement.traiter');
    
    // Messagerie
    Route::get('/messagerie', [\App\Http\Controllers\Patient\ChatController::class, 'index'])->name('messagerie.index');
    Route::prefix('messagerie')->name('messagerie.')->group(function () {
        Route::get('/conversations', [\App\Http\Controllers\Patient\ChatController::class, 'conversations'])->name('conversations');
        Route::post('/conversations', [\App\Http\Controllers\Patient\ChatController::class, 'storeConversation'])->name('conversations.store');
        Route::get('/conversations/{conversation}', [\App\Http\Controllers\Patient\ChatController::class, 'show'])->name('conversations.show');
        Route::post('/conversations/{conversation}/messages', [\App\Http\Controllers\Patient\ChatController::class, 'storeMessage'])->name('conversations.messages.store');
        Route::post('/conversations/{conversation}/read', [\App\Http\Controllers\Patient\ChatController::class, 'markAsRead'])->name('conversations.read');
        Route::post('/conversations/{conversation}/archive', [\App\Http\Controllers\Patient\ChatController::class, 'archive'])->name('conversations.archive');
    });

    // Calendrier
    Route::get('/calendrier', [\App\Http\Controllers\Patient\CalendrierController::class, 'index'])->name('calendrier');
    Route::get('/calendrier/events', [\App\Http\Controllers\Patient\CalendrierController::class, 'getEvents'])->name('calendrier.events');
    Route::get('/calendrier/export/ical', [\App\Http\Controllers\Patient\CalendrierController::class, 'exportIcal'])->name('calendrier.export.ical');
    Route::get('/calendrier/export/google', [\App\Http\Controllers\Patient\CalendrierController::class, 'exportGoogle'])->name('calendrier.export.google');
    
    // Historique paiements
    Route::get('/paiements', [\App\Http\Controllers\Patient\PaiementController::class, 'index'])->name('paiements.index');
    Route::get('/paiements/{paiement}', [\App\Http\Controllers\Patient\PaiementController::class, 'show'])->name('paiements.show');
    Route::get('/paiements/{paiement}/recu', [\App\Http\Controllers\Patient\PaiementController::class, 'downloadRecu'])->name('paiements.recu');
    
    // Documents médicaux
    Route::get('/documents', [\App\Http\Controllers\Patient\DocumentController::class, 'index'])->name('documents.index');
    Route::get('/documents/{document}/download', [\App\Http\Controllers\Patient\DocumentController::class, 'downloadDocument'])->name('documents.download');
    Route::get('/documents/ordonnance/{ordonnance}/download', [\App\Http\Controllers\Patient\DocumentController::class, 'downloadOrdonnance'])->name('documents.ordonnance.download');
    Route::get('/documents/examen/{examen}/download', [\App\Http\Controllers\Patient\DocumentController::class, 'downloadExamen'])->name('documents.examen.download');
    Route::get('/documents/certificat/generate', [\App\Http\Controllers\Patient\DocumentController::class, 'generateCertificat'])->name('documents.certificat.generate');
    Route::get('/documents/attestation/{consultation}', [\App\Http\Controllers\Patient\DocumentController::class, 'generateAttestation'])->name('documents.attestation.generate');
    
    // Suivi santé (lecture seule - rempli par le praticien)
    Route::get('/suivi-sante', [\App\Http\Controllers\Patient\SuiviSanteController::class, 'index'])->name('suivi-sante.index');
});

// Routes PRATICIEN
Route::middleware(['auth', 'role:PRATICIEN'])->prefix('praticien')->name('praticien.')->group(function () {
    Route::get('/dashboard', [PraticienDashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [PraticienDashboardController::class, 'profile'])->name('profile');
    Route::get('/mes-patients', [PraticienDashboardController::class, 'patients'])->name('patients');
    Route::get('/patient/{patient}/dossier', [PraticienDashboardController::class, 'dossierPatient'])->name('patient.dossier');
    Route::get('/mes-documents', [PraticienDashboardController::class, 'documents'])->name('documents');
    Route::get('/mon-agenda', [PraticienDashboardController::class, 'agenda'])->name('agenda');
    Route::prefix('messages')->name('messages.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Praticien\ChatController::class, 'index'])->name('index');
        Route::get('/conversations', [\App\Http\Controllers\Praticien\ChatController::class, 'conversations'])->name('conversations');
        Route::post('/conversations', [\App\Http\Controllers\Praticien\ChatController::class, 'storeConversation'])->name('conversations.store');
        Route::get('/conversations/{conversation}', [\App\Http\Controllers\Praticien\ChatController::class, 'show'])->name('conversations.show');
        Route::post('/conversations/{conversation}/messages', [\App\Http\Controllers\Praticien\ChatController::class, 'storeMessage'])->name('conversations.messages.store');
        Route::post('/conversations/{conversation}/read', [\App\Http\Controllers\Praticien\ChatController::class, 'markAsRead'])->name('conversations.read');
    });
    Route::get('/disponibilites', [DisponibiliteController::class, 'index'])->name('disponibilites');
    Route::post('/disponibilites', [DisponibiliteController::class, 'store'])->name('disponibilites.store');
    Route::get('/consultations', [ConsultationController::class, 'index'])->name('consultations');
    Route::get('/consultation/{rendezVous}', [ConsultationController::class, 'show'])->name('consultation.show');
    Route::post('/consultation/{rendezVous}', [ConsultationController::class, 'store'])->name('consultation.store');
    Route::get('/consultation/{rendezVous}/show', [ConsultationController::class, 'show'])->name('consultation.show');
    Route::get('/ordonnance/{consultation}', [ConsultationController::class, 'ordonnance'])->name('ordonnance');
    Route::post('/ordonnance/{consultation}', [ConsultationController::class, 'storeOrdonnance'])->name('ordonnance.store');
    Route::patch('/rendez-vous/{rendezVous}/replanifier', [RendezVousController::class, 'reschedule'])->name('rendezvous.reschedule');
});

// Routes SECRETAIRE
Route::middleware(['auth', 'role:SECRETAIRE'])->prefix('secretaire')->name('secretaire.')->group(function () {
    Route::get('/dashboard', [SecretaireDashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [SecretaireProfileController::class, 'edit'])->name('profile');
    Route::patch('/profile', [SecretaireProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [SecretaireProfileController::class, 'updatePassword'])->name('profile.password.update');
    Route::delete('/profile', [SecretaireProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/file-attente', [FileAttenteController::class, 'index'])->name('file-attente');
    Route::post('/demande/{demandeRdv}/valider', [FileAttenteController::class, 'valider'])->name('demande.valider');
    Route::post('/demande/{demandeRdv}/refuser', [FileAttenteController::class, 'refuser'])->name('demande.refuser');
    Route::get('/demande/{demandeRdv}/reprogrammation', [SecretaireRendezVousController::class, 'showReprogrammation'])->name('demande.reprogrammation');
    Route::post('/demande/{demandeRdv}/reprogrammation', [SecretaireRendezVousController::class, 'storeReprogrammation'])->name('demande.reprogrammation.store');
    Route::post('/rendez-vous/{rendezVous}/confirmer', [SecretaireRendezVousController::class, 'confirm'])->name('rendezvous.confirmer');
    Route::post('/rendez-vous/{rendezVous}/annuler', [SecretaireRendezVousController::class, 'cancel'])->name('rendezvous.annuler');
    Route::get('/agendas', [SecretaireDashboardController::class, 'agendas'])->name('agendas');
    Route::get('/agendas/planning', [SecretaireDashboardController::class, 'multiAgenda'])->name('agendas.multi');
    Route::get('/agendas/events', [SecretaireDashboardController::class, 'agendaEvents'])->name('agendas.events');
    Route::post('/agendas/replanifier', [SecretaireDashboardController::class, 'agendaReplanifier'])->name('agendas.replanifier');
    Route::get('/agenda/{praticien}', [SecretaireDashboardController::class, 'agendaPraticien'])->name('agenda.praticien');
    Route::get('/facturation', [SecretaireDashboardController::class, 'facturation'])->name('facturation');
    Route::get('/generer-facture/{consultation}', [SecretaireDashboardController::class, 'genererFacture'])->name('facture.generer');
    Route::post('/facture/{consultation}', [SecretaireDashboardController::class, 'storeFacture'])->name('facture.store');
    Route::get('/encaissements', [SecretaireDashboardController::class, 'encaissements'])->name('encaissements');

    Route::prefix('messages')->name('messages.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Secretaire\ChatController::class, 'index'])->name('index');
        Route::get('/conversations', [\App\Http\Controllers\Secretaire\ChatController::class, 'conversations'])->name('conversations');
        Route::post('/conversations', [\App\Http\Controllers\Secretaire\ChatController::class, 'storeConversation'])->name('conversations.store');
        Route::get('/conversations/{conversation}', [\App\Http\Controllers\Secretaire\ChatController::class, 'show'])->name('conversations.show');
        Route::post('/conversations/{conversation}/messages', [\App\Http\Controllers\Secretaire\ChatController::class, 'storeMessage'])->name('conversations.messages.store');
        Route::post('/conversations/{conversation}/read', [\App\Http\Controllers\Secretaire\ChatController::class, 'markAsRead'])->name('conversations.read');
    });

    Route::get('/relances', [ReminderController::class, 'index'])->name('relances.index');
    Route::post('/relances/templates/{template}', [ReminderController::class, 'updateTemplate'])->name('relances.templates.update');
    Route::post('/relances/send', [ReminderController::class, 'send'])->name('relances.send');
});

// Routes ADMIN
Route::middleware(['auth', 'role:ADMIN'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('users', UserController::class);
    Route::get('/agendas-globaux', [AdminDashboardController::class, 'agendasGlobaux'])->name('agendas-globaux');
    Route::get('/services', [AdminDashboardController::class, 'services'])->name('services');
    Route::post('/services', [AdminDashboardController::class, 'storeService'])->name('services.store');
    Route::delete('/services/{id}', [AdminDashboardController::class, 'destroyService'])->name('services.destroy');
    Route::get('/rapports', [AdminDashboardController::class, 'rapports'])->name('rapports');
    Route::get('/audit', [AdminDashboardController::class, 'audit'])->name('audit');
    Route::get('/rapport/activite', [AdminDashboardController::class, 'rapportActivite'])->name('rapport.activite');
    Route::get('/rapport/financier', [AdminDashboardController::class, 'rapportFinancier'])->name('rapport.financier');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
require __DIR__.'/patient-stripe.php';
