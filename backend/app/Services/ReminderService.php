<?php

namespace App\Services;

use App\Mail\ReminderMail;
use App\Models\Facture;
use App\Models\ReminderLog;
use App\Models\ReminderTemplate;
use App\Models\RendezVous;
use App\Models\Secretaire;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Throwable;

class ReminderService
{
    /**
     * Send a reminder for an unpaid invoice.
     */
    public function sendFactureReminder(ReminderTemplate $template, Facture $facture, array $channels, ?Secretaire $secretaire = null): void
    {
        $replacements = $this->factureReplacements($facture);

        if (in_array('email', $channels, true) && $template->channel_email) {
            $this->sendEmail($template, $facture->patient->user->email ?? null, $replacements, $template->id, $facture->patient_id, null, $facture->id, $secretaire);
        }

        if (in_array('sms', $channels, true) && $template->channel_sms) {
            $this->logSms($template, $facture->patient->user->telephone ?? null, $replacements, $template->id, $facture->patient_id, null, $facture->id, $secretaire);
        }
    }

    /**
     * Send a reminder for an upcoming appointment.
     */
    public function sendRdvReminder(ReminderTemplate $template, RendezVous $rendezVous, array $channels, ?Secretaire $secretaire = null): void
    {
        $replacements = $this->rendezVousReplacements($rendezVous);

        if (in_array('email', $channels, true) && $template->channel_email) {
            $this->sendEmail($template, $rendezVous->patient->user->email ?? null, $replacements, $template->id, $rendezVous->patient_id, $rendezVous->id, null, $secretaire);
        }

        if (in_array('sms', $channels, true) && $template->channel_sms) {
            $this->logSms($template, $rendezVous->patient->user->telephone ?? null, $replacements, $template->id, $rendezVous->patient_id, $rendezVous->id, null, $secretaire);
        }
    }

    /**
     * Prepare replacements for an appointment reminder.
     */
    protected function rendezVousReplacements(RendezVous $rendezVous): array
    {
        $start = $rendezVous->date_heure_rdv ?? Carbon::now();
        $end = (clone $start)->addMinutes($rendezVous->duree ?? 30);

        return [
            'patient_nom' => $rendezVous->patient->user->name ?? '',
            'patient_prenom' => $rendezVous->patient->user->prenom ?? '',
            'patient_nom_complet' => $rendezVous->patient->user->nom_complet ?? '',
            'praticien_nom' => optional($rendezVous->praticien->user)->nom_complet ?? '',
            'rdv_date' => $start->locale('fr')->isoFormat('dddd D MMMM YYYY'),
            'rdv_heure' => $start->format('H:i'),
            'rdv_duree' => ($rendezVous->duree ?? 30) . ' minutes',
            'rdv_fin_heure' => $end->format('H:i'),
            'lien_rdv' => route('patient.rendezvous.show', $rendezVous),
        ];
    }

    /**
     * Prepare replacements for an invoice reminder.
     */
    protected function factureReplacements(Facture $facture): array
    {
        return [
            'patient_nom' => $facture->patient->user->name ?? '',
            'patient_prenom' => $facture->patient->user->prenom ?? '',
            'patient_nom_complet' => $facture->patient->user->nom_complet ?? '',
            'facture_numero' => $facture->numero_facture,
            'facture_date' => optional($facture->date_facture)->format('d/m/Y') ?? '',
            'facture_montant_total' => number_format((float) $facture->montant_total, 0, ',', ' '),
            'facture_montant_restant' => number_format((float) $facture->montant_restant, 0, ',', ' '),
            'lien_paiement' => route('patient.paiement', $facture),
        ];
    }

    /**
     * Replace placeholders and dispatch the email.
     */
    protected function sendEmail(ReminderTemplate $template, ?string $email, array $replacements, int $templateId, ?int $patientId, ?int $rendezVousId, ?int $factureId, ?Secretaire $secretaire = null): void
    {
        if (empty($email)) {
            $this->storeLog($templateId, $patientId, $rendezVousId, $factureId, 'email', 'SKIPPED', 'Adresse email manquante', $secretaire);
            return;
        }

        $subject = $this->applyReplacements((string) $template->email_subject, $replacements);
        $body = $this->applyReplacements((string) $template->email_body, $replacements);

        try {
            Mail::to($email)->send(new ReminderMail($subject, $body));
            $this->storeLog($templateId, $patientId, $rendezVousId, $factureId, 'email', 'SENT', null, $secretaire, true);
        } catch (Throwable $exception) {
            Log::error('Reminder email failed', [
                'email' => $email,
                'exception' => $exception->getMessage(),
            ]);
            $this->storeLog($templateId, $patientId, $rendezVousId, $factureId, 'email', 'FAILED', $exception->getMessage(), $secretaire);
        }
    }

    /**
     * Create a log entry for an SMS reminder (placeholder for real gateway).
     */
    protected function logSms(ReminderTemplate $template, ?string $phone, array $replacements, int $templateId, ?int $patientId, ?int $rendezVousId, ?int $factureId, ?Secretaire $secretaire = null): void
    {
        if (empty($phone)) {
            $this->storeLog($templateId, $patientId, $rendezVousId, $factureId, 'sms', 'SKIPPED', 'Numéro de téléphone manquant', $secretaire);
            return;
        }

        $message = $this->applyReplacements((string) $template->sms_body, $replacements);
        Log::info('SMS reminder queued', [
            'to' => $phone,
            'message' => $message,
        ]);

        $this->storeLog($templateId, $patientId, $rendezVousId, $factureId, 'sms', 'QUEUED', null, $secretaire, true);
    }

    /**
     * Execute the placeholder replacement logic.
     */
    protected function applyReplacements(string $content, array $replacements): string
    {
        if ($content === '') {
            return $content;
        }

        $search = [];
        $replace = [];

        foreach ($replacements as $key => $value) {
            $search[] = '{{' . $key . '}}';
            $replace[] = (string) $value;
        }

        return Str::of($content)->replace($search, $replace)->toString();
    }

    /**
     * Persist the reminder log.
     */
    protected function storeLog(int $templateId, ?int $patientId, ?int $rendezVousId, ?int $factureId, string $channel, string $status, ?string $error, ?Secretaire $secretaire, bool $markSent = false): void
    {
        ReminderLog::create([
            'template_id' => $templateId,
            'patient_id' => $patientId,
            'rendez_vous_id' => $rendezVousId,
            'facture_id' => $factureId,
            'secretaire_id' => $secretaire?->id,
            'channel' => $channel,
            'status' => $status,
            'error' => $error,
            'sent_at' => $markSent ? now() : null,
        ]);
    }
}
