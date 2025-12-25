<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Patient;
use App\Models\Praticien;
use App\Models\RendezVous;
use App\Models\Consultation;
use App\Models\Ordonnance;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class PraticienTestDataSeeder extends Seeder
{
    public function run(): void
    {
        // Récupérer le praticien existant
        $praticienUser = User::where('email', 'praticien1@alamine.sn')->first();

        if (!$praticienUser) {
            echo "Praticien non trouvé. Veuillez d'abord créer le praticien.\n";
            return;
        }

        $praticien = $praticienUser->praticien;

        // Créer des patients de test
        $patients = [];
        $patientNames = [
            ['prenom' => 'Amadou', 'name' => 'Diallo', 'telephone' => '771234567'],
            ['prenom' => 'Fatou', 'name' => 'Sall', 'telephone' => '772345678'],
            ['prenom' => 'Moussa', 'name' => 'Ba', 'telephone' => '773456789'],
            ['prenom' => 'Aissatou', 'name' => 'Sy', 'telephone' => '774567890'],
            ['prenom' => 'Ousmane', 'name' => 'Ndiaye', 'telephone' => '775678901'],
            ['prenom' => 'Mariama', 'name' => 'Fall', 'telephone' => '776789012'],
            ['prenom' => 'Ibrahima', 'name' => 'Diop', 'telephone' => '777890123'],
            ['prenom' => 'Binta', 'name' => 'Kane', 'telephone' => '778901234'],
        ];

        foreach ($patientNames as $index => $patientData) {
            $sexe = $index % 2 === 0 ? 'M' : 'F';
            $email = strtolower($patientData['prenom']) . '.' . strtolower($patientData['name']) . '@patient.sn';

            // Vérifier si l'utilisateur existe déjà
            $user = User::where('email', $email)->first();

            if (!$user) {
                $userId = DB::table('users')->insertGetId([
                    'prenom' => $patientData['prenom'],
                    'name' => $patientData['name'],
                    'email' => $email,
                    'telephone' => $patientData['telephone'],
                    'numero_cni' => '1' . str_pad($index + 100, 12, '0', STR_PAD_LEFT),
                    'sexe' => $sexe,
                    'password' => Hash::make('password'),
                    'role' => 'PATIENT',
                    'statut_compte' => 'ACTIF',
                    'ville' => 'Dakar',
                    'email_verified_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $patient = Patient::create([
                    'user_id' => $userId,
                    'numero_securite_sociale' => 'NSS' . str_pad($index + 1, 10, '0', STR_PAD_LEFT),
                    'allergies' => null,
                    'antecedents' => null,
                    'mutuelle' => null,
                ]);
            } else {
                $patient = $user->patient ?? Patient::where('user_id', $user->id)->first();
                if (!$patient) {
                    $patient = Patient::create([
                        'user_id' => $user->id,
                        'numero_securite_sociale' => 'NSS' . str_pad($index + 1, 10, '0', STR_PAD_LEFT),
                        'allergies' => null,
                        'antecedents' => null,
                        'mutuelle' => null,
                    ]);
                }
            }

            $patients[] = $patient;
        }

        // Créer des rendez-vous pour aujourd'hui avec différentes heures
        $today = Carbon::today();
        $heures = [8, 9, 10, 11, 13, 14, 15, 16, 17];
        foreach (array_slice($patients, 0, 4) as $index => $patient) {
            RendezVous::create([
                'patient_id' => $patient->id,
                'praticien_id' => $praticien->id,
                'date_heure_rdv' => $today->copy()->addHours($heures[$index])->addMinutes(rand(0, 30)),
                'statut' => ['CONFIRME', 'PLANIFIE', 'EN_COURS'][rand(0, 2)],
                'duree' => 30,
                'notes' => ['Consultation générale', 'Contrôle', 'Urgence', 'Suivi'][rand(0, 3)],
            ]);
        }

        // Créer des rendez-vous pour toute la semaine (lundi à dimanche)
        $daysOfWeek = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
        $startOfWeek = now()->startOfWeek();

        for ($dayIndex = 0; $dayIndex < 7; $dayIndex++) {
            $currentDate = $startOfWeek->copy()->addDays($dayIndex);

            // Créer 2 à 4 rendez-vous par jour
            $rdvCount = rand(2, 4);
            $baseHour = 8;

            for ($rdvIndex = 0; $rdvIndex < $rdvCount; $rdvIndex++) {
                $patient = $patients[rand(0, count($patients) - 1)];

                RendezVous::create([
                    'patient_id' => $patient->id,
                    'praticien_id' => $praticien->id,
                    'date_heure_rdv' => $currentDate->copy()
                        ->addHours($baseHour + ($rdvIndex * 2))
                        ->addMinutes(rand(0, 50)),
                    'statut' => ['CONFIRME', 'PLANIFIE', 'EN_COURS', 'TERMINE'][rand(0, 3)],
                    'duree' => 30,
                    'notes' => ['Consultation générale', 'Suivi', 'Urgence', 'Contrôle médical'][rand(0, 3)],
                ]);
            }
        }

        // Créer des consultations passées avec ordonnances
        foreach (array_slice($patients, 0, 5) as $patient) {
            for ($i = 0; $i < rand(2, 4); $i++) {
                $dateConsultation = Carbon::now()->subDays(rand(1, 60));

                $rdv = RendezVous::create([
                    'patient_id' => $patient->id,
                    'praticien_id' => $praticien->id,
                    'date_heure_rdv' => $dateConsultation,
                    'statut' => 'TERMINE',
                    'duree' => 30,
                    'notes' => 'Consultation',
                ]);

                $consultation = Consultation::create([
                    'rendez_vous_id' => $rdv->id,
                    'praticien_id' => $praticien->id,
                    'patient_id' => $patient->id,
                    'date_consultation' => $dateConsultation,
                    'motif' => 'Consultation générale',
                    'diagnostic' => 'État général satisfaisant',
                    'notes' => 'Patient en bonne santé',
                    'est_validee' => true,
                    'validee_le' => $dateConsultation,
                ]);

                // Créer une ordonnance
                Ordonnance::create([
                    'consultation_id' => $consultation->id,
                    'praticien_id' => $praticien->id,
                    'patient_id' => $patient->id,
                    'date_ordonnance' => $dateConsultation->format('Y-m-d'),
                    'medicaments' => [
                        [
                            'nom' => 'Paracétamol',
                            'posologie' => '1g, 3 fois par jour',
                            'duree' => '5 jours'
                        ]
                    ],
                    'conseils' => 'Repos recommandé. Boire beaucoup d\'eau.',
                ]);
            }
        }

        echo "✅ Données de test créées avec succès !\n";
        echo "- " . count($patients) . " patients créés\n";
        echo "- Rendez-vous aujourd'hui créés\n";
        echo "- Rendez-vous futurs créés\n";
        echo "- Consultations et ordonnances créées\n";
    }
}

