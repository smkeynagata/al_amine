<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Patient;
use App\Models\Praticien;
use App\Models\DemandeRdv;
use App\Models\Specialite;
use Carbon\Carbon;

class DemandeRdvSeeder extends Seeder
{
    public function run(): void
    {
        // Récupérer le praticien existant
        $praticienUser = User::where('email', 'praticien1@alamine.sn')->first();

        if (!$praticienUser) {
            echo "Praticien non trouvé.\n";
            return;
        }

        $praticien = $praticienUser->praticien;

        // Récupérer des patients existants
        $patients = Patient::with('user')->limit(5)->get();

        if ($patients->isEmpty()) {
            echo "Aucun patient trouvé. Veuillez d'abord créer des patients.\n";
            return;
        }

        // Récupérer une spécialité
        $specialite = Specialite::first();

        // Motifs de consultation variés
        $motifs = [
            'Consultation de contrôle',
            'Douleurs abdominales',
            'Fièvre persistante',
            'Suivi post-opératoire',
            'Bilan de santé annuel',
            'Maux de tête fréquents',
            'Problèmes respiratoires',
            'Consultation urgente',
        ];

        // Créer des demandes de RDV en attente
        foreach ($patients as $index => $patient) {
            // Demandes EN_ATTENTE (pour le dashboard)
            DemandeRdv::create([
                'patient_id' => $patient->id,
                'praticien_id' => $praticien->id,
                'specialite_id' => $specialite->id ?? null,
                'date_heure_souhaitee' => now()->addDays(rand(1, 7))->addHours(rand(8, 17)),
                'motif' => $motifs[$index % count($motifs)],
                'statut' => 'EN_ATTENTE',
                'created_at' => now()->subHours(rand(1, 24)),
                'updated_at' => now()->subHours(rand(1, 24)),
            ]);
        }

        // Créer quelques demandes acceptées
        foreach ($patients->take(3) as $index => $patient) {
            DemandeRdv::create([
                'patient_id' => $patient->id,
                'praticien_id' => $praticien->id,
                'specialite_id' => $specialite->id ?? null,
                'date_heure_souhaitee' => now()->addDays(rand(8, 14))->addHours(rand(8, 17)),
                'motif' => $motifs[($index + 3) % count($motifs)],
                'statut' => 'CONFIRMEE',
                'reponse_secretaire' => 'Votre demande a été acceptée.',
                'created_at' => now()->subDays(rand(1, 3)),
                'updated_at' => now()->subHours(rand(1, 12)),
            ]);
        }

        echo "✅ Demandes de RDV créées avec succès !\n";
        echo "- 5 demandes en attente\n";
        echo "- 3 demandes acceptées\n";
    }
}
