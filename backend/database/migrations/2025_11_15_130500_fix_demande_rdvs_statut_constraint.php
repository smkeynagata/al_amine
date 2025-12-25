<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Supprimer l'ancienne contrainte et en ajouter une nouvelle avec PAYEE
        DB::statement("ALTER TABLE demande_rdvs DROP CONSTRAINT IF EXISTS demande_rdvs_statut_check");
        DB::statement("ALTER TABLE demande_rdvs ADD CONSTRAINT demande_rdvs_statut_check CHECK (statut IN ('EN_ATTENTE_PAIEMENT', 'EN_ATTENTE', 'PAYEE', 'CONFIRMEE', 'REFUSEE', 'ANNULEE'))");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE demande_rdvs DROP CONSTRAINT IF EXISTS demande_rdvs_statut_check");
        DB::statement("ALTER TABLE demande_rdvs ADD CONSTRAINT demande_rdvs_statut_check CHECK (statut IN ('EN_ATTENTE_PAIEMENT', 'EN_ATTENTE', 'CONFIRMEE', 'REFUSEE', 'ANNULEE'))");
    }
};
