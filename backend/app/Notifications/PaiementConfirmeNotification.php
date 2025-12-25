<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Paiement;
use Illuminate\Support\HtmlString;

class PaiementConfirmeNotification extends Notification
{
    use Queueable;

    protected $paiement;

    public function __construct(Paiement $paiement)
    {
        $this->paiement = $paiement;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        $demande = $this->paiement->demandeRdv;

        return (new MailMessage)
            ->subject('✅ Paiement confirmé - Al-Amine')
            ->view('emails.notifications.paiement-confirme', [
                'prenom' => $notifiable->prenom ?? $notifiable->name,
                'paiement' => $this->paiement,
                'montant_formate' => number_format($this->paiement->montant, 0, ',', ' ') . ' FCFA',
                'methode' => strtoupper(str_replace('_', ' ', $this->paiement->methode_paiement)),
                'reference' => $this->paiement->reference,
                'transaction' => $this->paiement->numero_transaction,
                'demande' => $demande,
                'cta_url' => route('patient.paiements.index'),
            ]);
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'paiement_confirme',
            'paiement_id' => $this->paiement->id,
            'montant' => $this->paiement->montant,
            'methode' => $this->paiement->methode_paiement,
            'numero_transaction' => $this->paiement->numero_transaction,
            'message' => 'Votre paiement de ' . number_format($this->paiement->montant, 0, ',', ' ') . ' FCFA a été confirmé avec succès.'
        ];
    }
}