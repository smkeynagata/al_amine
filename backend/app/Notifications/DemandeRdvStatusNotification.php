<?php

namespace App\Notifications;

use App\Models\DemandeRdv;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DemandeRdvStatusNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $demandeRdv;
    public $status;

    /**
     * Create a new notification instance.
     */
    public function __construct(DemandeRdv $demandeRdv, string $status)
    {
        $this->demandeRdv = $demandeRdv;
        $this->status = $status;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $rendezVous = $this->demandeRdv->rendezVous;
        $dateProgramme = $rendezVous?->date_heure_rdv ?? $this->demandeRdv->date_heure_souhaitee;
        $formattedDate = $dateProgramme ? $dateProgramme->locale('fr')->translatedFormat('l d F Y') : 'À confirmer';
        $formattedHeure = $dateProgramme ? $dateProgramme->format('H\hi') : '—';

        return (new MailMessage)
            ->subject($this->getSubject())
            ->view('emails.notifications.demande-rdv-status', [
                'prenom' => $this->demandeRdv->patient->user->prenom ?? $this->demandeRdv->patient->user->name,
                'status' => $this->status,
                'praticien' => $this->demandeRdv->praticien->user->nom_complet,
                'date_formatee' => ucfirst($formattedDate),
                'heure_formatee' => $formattedHeure,
                'lieu' => 'Hôpital Al-Amine – Centre principal',
                'cta_url' => $this->status === 'validee' ? route('patient.mes-rdv') : route('patient.demander-rdv'),
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'demande_rdv_status',
            'demande_rdv_id' => $this->demandeRdv->id,
            'status' => $this->status,
            'praticien_nom' => $this->demandeRdv->praticien->user->nom_complet,
            'date_programmee' => optional($this->demandeRdv->rendezVous?->date_heure_rdv ?? $this->demandeRdv->date_heure_souhaitee)->format('Y-m-d H:i:s'),
            'message' => $this->status === 'validee' 
                ? 'Votre rendez-vous avec Dr. ' . $this->demandeRdv->praticien->user->nom_complet . ' est confirmé le ' . optional($this->demandeRdv->rendezVous?->date_heure_rdv)->format('d/m/Y à H\hi')
                : 'Votre demande de RDV avec Dr. ' . $this->demandeRdv->praticien->user->nom_complet . ' a été refusée',
        ];
    }

    private function getSubject(): string
    {
        return $this->status === 'validee' 
            ? 'Demande de rendez-vous acceptée - Al-Amine'
            : 'Demande de rendez-vous refusée - Al-Amine';
    }
}
