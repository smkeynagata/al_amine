<?php

namespace App\Notifications;

use App\Models\RendezVous;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RendezVousStatusNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public RendezVous $rendezVous,
        public string $status
    ) {
        $this->status = strtoupper($status);
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
        $patientUser = $this->rendezVous->patient?->user;
        $praticienUser = $this->rendezVous->praticien?->user;
        $dateRdv = $this->rendezVous->date_heure_rdv;

        $greetingName = $patientUser?->prenom ?: ($patientUser?->name ?? 'cher patient');
        $praticienNom = $praticienUser?->nom_complet ?? 'votre praticien';
        $dateFormatee = $dateRdv?->locale('fr')->translatedFormat('l d F Y');
        $heureFormatee = $dateRdv?->format('H\hi');

        $mail = (new MailMessage)
            ->subject($this->status === 'CONFIRME'
                ? 'Votre rendez-vous est confirmé'
                : 'Votre rendez-vous a été annulé')
            ->greeting('Bonjour ' . ucfirst($greetingName))
            ->line('Rendez-vous avec Dr. ' . $praticienNom)
            ->line('Date : ' . ($dateFormatee ? ucfirst($dateFormatee) : 'À confirmer'))
            ->line('Heure : ' . ($heureFormatee ?? '—'));

        if ($this->status === 'CONFIRME') {
            $mail->line("Votre rendez-vous a été confirmé. Merci d'arriver 10 minutes à l'avance.")
                ->action('Voir mes rendez-vous', route('patient.mes-rdv'));
        } else {
            $mail->line('Ce rendez-vous a été annulé.')
                ->action('Voir mes rendez-vous', route('patient.mes-rdv'));
        }

        return $mail->line('Merci de votre confiance envers l’Hôpital Al-Amine.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'rendez_vous_status',
            'rendez_vous_id' => $this->rendezVous->id,
            'status' => $this->status,
            'date_heure_rdv' => $this->rendezVous->date_heure_rdv?->format('Y-m-d H:i:s'),
            'praticien_nom' => $this->rendezVous->praticien?->user?->nom_complet,
            'message' => $this->status === 'CONFIRME'
                ? 'Votre rendez-vous a été confirmé.'
                : 'Votre rendez-vous a été annulé.',
        ];
    }
}
