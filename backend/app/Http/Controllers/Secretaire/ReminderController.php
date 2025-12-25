<?php

namespace App\Http\Controllers\Secretaire;

use App\Http\Controllers\Controller;
use App\Models\Facture;
use App\Models\ReminderLog;
use App\Models\ReminderTemplate;
use App\Models\RendezVous;
use App\Services\ReminderService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ReminderController extends Controller
{
    public function __construct(private ReminderService $reminderService)
    {
    }

    public function index()
    {
        $templates = ReminderTemplate::orderBy('type')->get()->keyBy('type');

        $factures = Facture::with('patient.user')
            ->where('statut', 'EMISE')
            ->where('montant_restant', '>', 0)
            ->orderBy('date_facture')
            ->get();

        $rdvs = RendezVous::with(['patient.user', 'praticien.user'])
            ->whereBetween('date_heure_rdv', [Carbon::now(), Carbon::now()->addDays(7)])
            ->whereIn('statut', ['PLANIFIE', 'CONFIRME'])
            ->orderBy('date_heure_rdv')
            ->get();

        $logs = ReminderLog::with(['patient.user', 'template'])
            ->orderByDesc('created_at')
            ->limit(20)
            ->get();

        return view('secretaire.reminders.index', compact('templates', 'factures', 'rdvs', 'logs'));
    }

    public function updateTemplate(Request $request, ReminderTemplate $template)
    {
        $validated = $request->validate([
            'email_subject' => ['nullable', 'string', 'max:190'],
            'email_body' => ['nullable', 'string'],
            'sms_body' => ['nullable', 'string'],
            'channel_email' => ['nullable', 'boolean'],
            'channel_sms' => ['nullable', 'boolean'],
        ]);

        $template->update([
            'email_subject' => $validated['email_subject'] ?? $template->email_subject,
            'email_body' => $validated['email_body'] ?? $template->email_body,
            'sms_body' => $validated['sms_body'] ?? $template->sms_body,
            'channel_email' => array_key_exists('channel_email', $validated) ? (bool) $validated['channel_email'] : $template->channel_email,
            'channel_sms' => array_key_exists('channel_sms', $validated) ? (bool) $validated['channel_sms'] : $template->channel_sms,
        ]);

        return back()->with('success', 'Modèle de relance mis à jour.');
    }

    public function send(Request $request)
    {
        $validated = $request->validate([
            'type' => ['required', 'in:facture,rdv'],
            'target_id' => ['required', 'integer'],
            'channels' => ['nullable', 'array'],
            'channels.*' => ['in:email,sms'],
        ]);

        $channels = $validated['channels'] ?? ['email'];
        $secretaire = optional(auth()->user())->secretaire;

        if ($validated['type'] === 'facture') {
            $facture = Facture::with(['patient.user'])->findOrFail($validated['target_id']);
            $template = ReminderTemplate::where('type', ReminderTemplate::TYPE_FACTURE_IMPAYEE)->firstOrFail();
            $this->reminderService->sendFactureReminder($template, $facture, $channels, $secretaire);
        } else {
            $rendezVous = RendezVous::with(['patient.user', 'praticien.user'])->findOrFail($validated['target_id']);
            $template = ReminderTemplate::where('type', ReminderTemplate::TYPE_RDV_CONFIRMATION)->firstOrFail();
            $this->reminderService->sendRdvReminder($template, $rendezVous, $channels, $secretaire);
        }

        return response()->json(['status' => 'ok']);
    }
}
