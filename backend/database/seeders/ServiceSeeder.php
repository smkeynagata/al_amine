<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            [
                'nom' => 'Médecine Générale',
                'localisation' => 'Bâtiment A - 1er étage',
                'description' => 'Consultations de médecine générale et suivi des patients chroniques',
            ],
            [
                'nom' => 'Cardiologie',
                'localisation' => 'Bâtiment A - 2ème étage',
                'description' => 'Prise en charge complète des pathologies cardiaques',
            ],
            [
                'nom' => 'Pédiatrie',
                'localisation' => 'Bâtiment B - Rez-de-chaussée',
                'description' => 'Service dédié aux enfants et adolescents',
            ],
            [
                'nom' => 'Gynécologie-Obstétrique',
                'localisation' => 'Bâtiment C - 1er étage',
                'description' => 'Suivi de la santé de la femme et maternité',
            ],
            [
                'nom' => 'Dermatologie',
                'localisation' => 'Bâtiment C - Rez-de-chaussée',
                'description' => 'Diagnostic et traitement des maladies de peau',
            ],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }
    }
}

