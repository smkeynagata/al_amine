<?php

namespace App\Console\Commands;

use App\Models\RendezVous;
use App\Notifications\RdvRappelNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendRdvReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rdv:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envoyer des rappels de RDV aux patients 24h avant leur rendez-vous';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Recherche des rendez-vous pour demain...');

        // Récupérer tous les RDV confirmés de demain
        $tomorrow = Carbon::tomorrow();
        
        $rendezVous = RendezVous::where('statut', 'confirme')
            ->whereDate('date_heure_rdv', $tomorrow->toDateString())
            ->with(['patient.user'])
            ->get();

        $count = 0;

        foreach ($rendezVous as $rdv) {
            try {
                // Envoyer la notification au patient
                $rdv->patient->user->notify(new RdvRappelNotification($rdv));
                
                $this->info("Rappel envoyé pour RDV #$rdv->id - Patient: " . $rdv->patient->user->nom_complet);
                $count++;
            } catch (\Exception $e) {
                $this->error("Erreur lors de l'envoi du rappel pour RDV #$rdv->id : " . $e->getMessage());
            }
        }

        $this->info("$count rappel(s) envoyé(s) avec succès.");

        return Command::SUCCESS;
    }
}
