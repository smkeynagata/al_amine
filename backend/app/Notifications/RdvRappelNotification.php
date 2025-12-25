<?php

namespace App\Notifications;

use App\Models\RendezVous;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RdvRappelNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $rendezVous;

    /**
     * Create a new notification instance.
     */
    public function __construct(RendezVous $rendezVous)
    {
        $this->rendezVous = $rendezVous;
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
        $date = $this->rendezVous->date_heure_rdv->locale('fr');

        return (new MailMessage)
                    ->subject('🔔 Rappel de rendez-vous - Al-Amine')
                    ->view('emails.notifications.rdv-rappel', [
                        'prenom' => $this->rendezVous->patient->user->prenom ?? $this->rendezVous->patient->user->name,
                        'praticien' => $this->rendezVous->praticien->user->nom_complet,
                        'date_formatee' => ucfirst($date->translatedFormat('l d F Y')),
                        'heure_formatee' => $date->format('H\hi'),
                        'duree' => $this->rendezVous->duree_minutes . ' minutes',
                        'lieu' => 'Hôpital Al-Amine – Centre principal',
                        'cta_url' => route('patient.rendezvous.show', $this->rendezVous),
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
            'type' => 'rdv_rappel',
            'rendez_vous_id' => $this->rendezVous->id,
            'praticien_nom' => $this->rendezVous->praticien->user->nom_complet,
            'date_heure' => $this->rendezVous->date_heure_rdv->format('Y-m-d H:i'),
            'message' => 'Rappel: Rendez-vous demain avec Dr. ' . $this->rendezVous->praticien->user->nom_complet . ' à ' . $this->rendezVous->date_heure_rdv->format('H:i'),
        ];
    }

}
