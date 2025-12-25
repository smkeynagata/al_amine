<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Patient;
use App\Models\Praticien;
use App\Models\Secretaire;
use App\Models\Specialite;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // ADMIN
        User::create([
            'name' => 'DIOP',
            'prenom' => 'Amadou',
            'email' => 'admin@alamine.sn',
            'password' => Hash::make('password'),
            'date_naissance' => '1975-03-15',
            'sexe' => 'M',
            'numero_cni' => '1234567890123',
            'telephone' => '771234567',
            'adresse' => 'Rue 10, Angle Boulevard',
            'quartier' => 'Plateau',
            'ville' => 'Dakar',
            'role' => 'ADMIN',
            'statut_compte' => 'ACTIF',
        ]);

        // SECRÉTAIRES
        $secretaire1 = User::create([
            'name' => 'FALL',
            'prenom' => 'Aïssatou',
            'email' => 'secretaire1@alamine.sn',
            'password' => Hash::make('password'),
            'date_naissance' => '1990-07-20',
            'sexe' => 'F',
            'numero_cni' => '2345678901234',
            'telephone' => '772345678',
            'adresse' => 'Cité Keur Gorgui',
            'quartier' => 'Mermoz',
            'ville' => 'Dakar',
            'role' => 'SECRETAIRE',
            'statut_compte' => 'ACTIF',
        ]);
        Secretaire::create([
            'user_id' => $secretaire1->id,
            'matricule' => 'SEC001',
        ]);

        $secretaire2 = User::create([
            'name' => 'SARR',
            'prenom' => 'Fatou',
            'email' => 'secretaire2@alamine.sn',
            'password' => Hash::make('password'),
            'date_naissance' => '1992-11-12',
            'sexe' => 'F',
            'numero_cni' => '3456789012345',
            'telephone' => '773456789',
            'adresse' => 'Parcelles U25',
            'quartier' => 'Parcelles Assainies',
            'ville' => 'Dakar',
            'role' => 'SECRETAIRE',
            'statut_compte' => 'ACTIF',
        ]);
        Secretaire::create([
            'user_id' => $secretaire2->id,
            'matricule' => 'SEC002',
        ]);

        // PRATICIENS
        $praticiens = [
            [
                'user' => [
                    'name' => 'NDIAYE',
                    'prenom' => 'Dr. Cheikh',
                    'email' => 'praticien1@alamine.sn',
                    'password' => Hash::make('passer123'),
                    'date_naissance' => '1980-05-10',
                    'sexe' => 'M',
                    'numero_cni' => '4567890123456',
                    'telephone' => '774567890',
                    'adresse' => 'Villa 45, Almadies',
                    'quartier' => 'Almadies',
                    'ville' => 'Dakar',
                    'role' => 'PRATICIEN',
                    'statut_compte' => 'ACTIF',
                ],
                'praticien' => [
                    'service_id' => 1,
                    'tarif_consultation' => 10000,
                    'annees_experience' => 15,
                    'biographie' => "Médecin généraliste avec 15 ans d'expérience",
                ],
                'specialites' => [1],
            ],
            [
                'user' => [
                    'name' => 'BA',
                    'prenom' => 'Dr. Mariama',
                    'email' => 'praticien2@alamine.sn',
                    'password' => Hash::make('password'),
                    'date_naissance' => '1978-08-02',
                    'sexe' => 'F',
                    'numero_cni' => '5678901234567',
                    'telephone' => '775678901',
                    'adresse' => 'Rue 12, Point E',
                    'quartier' => 'Point E',
                    'ville' => 'Dakar',
                    'role' => 'PRATICIEN',
                    'statut_compte' => 'ACTIF',
                ],
                'praticien' => [
                    'service_id' => 2,
                    'tarif_consultation' => 20000,
                    'annees_experience' => 12,
                    'biographie' => 'Cardiologue spécialisée en insuffisance cardiaque',
                ],
                'specialites' => [2],
            ],
            [
                'user' => [
                    'name' => 'FALL',
                    'prenom' => 'Dr. Moustapha',
                    'email' => 'praticien3@alamine.sn',
                    'password' => Hash::make('password'),
                    'date_naissance' => '1985-01-18',
                    'sexe' => 'M',
                    'numero_cni' => '6789012345678',
                    'telephone' => '776789012',
                    'adresse' => 'Immeuble Azur, Appartement 4',
                    'quartier' => 'Sacré-Coeur 3',
                    'ville' => 'Dakar',
                    'role' => 'PRATICIEN',
                    'statut_compte' => 'ACTIF',
                ],
                'praticien' => [
                    'service_id' => 3,
                    'tarif_consultation' => 15000,
                    'annees_experience' => 8,
                    'biographie' => 'Pédiatre passionné par la vaccination et la nutrition infantile',
                ],
                'specialites' => [3],
            ],
            [
                'user' => [
                    'name' => 'DIOP',
                    'prenom' => 'Dr. Khady',
                    'email' => 'praticien4@alamine.sn',
                    'password' => Hash::make('password'),
                    'date_naissance' => '1983-04-30',
                    'sexe' => 'F',
                    'numero_cni' => '7890123456789',
                    'telephone' => '777890123',
                    'adresse' => 'Résidence Fann',
                    'quartier' => 'Fann Hock',
                    'ville' => 'Dakar',
                    'role' => 'PRATICIEN',
                    'statut_compte' => 'ACTIF',
                ],
                'praticien' => [
                    'service_id' => 5,
                    'tarif_consultation' => 18000,
                    'annees_experience' => 10,
                    'biographie' => 'Dermatologue experte en dermatoses tropicales',
                ],
                'specialites' => [4],
            ],
            [
                'user' => [
                    'name' => 'SY',
                    'prenom' => 'Dr. Fatima',
                    'email' => 'praticien5@alamine.sn',
                    'password' => Hash::make('password'),
                    'date_naissance' => '1981-09-22',
                    'sexe' => 'F',
                    'numero_cni' => '8901234567890',
                    'telephone' => '778901234',
                    'adresse' => 'Point E',
                    'quartier' => 'Point E',
                    'ville' => 'Dakar',
                    'role' => 'PRATICIEN',
                    'statut_compte' => 'ACTIF',
                ],
                'praticien' => [
                    'service_id' => 1,
                    'tarif_consultation' => 17000,
                    'annees_experience' => 13,
                    'biographie' => 'Gynécologue obstétricienne',
                ],
                'specialites' => [5],
            ],
        ];

        foreach ($praticiens as $data) {
            $user = User::create($data['user']);
            $praticien = Praticien::create(array_merge(
                $data['praticien'],
                ['user_id' => $user->id]
            ));

            // Attacher les spécialités
            foreach ($data['specialites'] as $specialiteId) {
                $praticien->specialites()->attach($specialiteId, [
                    'niveau_expertise' => 'EXPERT',
                    'est_principale' => true,
                ]);
            }
        }

        // PATIENTS (30 patients)
        $prenoms = ['Abdou', 'Moussa', 'Ibrahima', 'Mamadou', 'Ousmane', 'Fatou', 'Awa', 'Aminata', 'Mariama', 'Khady'];
        $noms = ['DIOP', 'FALL', 'NDIAYE', 'BA', 'SY', 'SARR', 'CISSE', 'SECK', 'FAYE', 'GUEYE'];
        $quartiers = ['Plateau', 'Médina', 'Parcelles Assainies', 'Liberté 6', 'Mermoz', 'HLM Grand Yoff', 'Ouakam', 'Almadies', 'Ngor', 'Yoff'];

        for ($i = 1; $i <= 30; $i++) {
            $sexe = $i % 2 == 0 ? 'F' : 'M';
            $prenom = $prenoms[array_rand($prenoms)];
            $nom = $noms[array_rand($noms)];

            $user = User::create([
                'name' => $nom,
                'prenom' => $prenom,
                'email' => 'patient' . $i . '@example.sn',
                'password' => Hash::make('password'),
                'date_naissance' => date('Y-m-d', strtotime('-' . rand(20, 60) . ' years')),
                'sexe' => $sexe,
                'numero_cni' => '90' . str_pad($i, 11, '0', STR_PAD_LEFT),
                'telephone' => '77' . rand(1000000, 9999999),
                'adresse' => 'Rue ' . rand(1, 50) . ', Villa ' . rand(1, 100),
                'quartier' => $quartiers[array_rand($quartiers)],
                'ville' => 'Dakar',
                'role' => 'PATIENT',
                'statut_compte' => 'ACTIF',
            ]);

            Patient::create([
                'user_id' => $user->id,
                'numero_securite_sociale' => 'SS-' . str_pad($i, 10, '0', STR_PAD_LEFT),
                'allergies' => $i % 3 == 0 ? 'Pénicilline' : null,
                'antecedents' => $i % 4 == 0 ? 'Hypertension' : null,
                'mutuelle' => $i % 2 == 0 ? 'IPM' : 'IPRESS',
            ]);
        }
    }
}

