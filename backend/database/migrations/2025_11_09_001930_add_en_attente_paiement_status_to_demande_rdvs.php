<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Pour PostgreSQL, on doit d'abord supprimer la contrainte CHECK existante puis la recréer
        DB::statement("ALTER TABLE demande_rdvs DROP CONSTRAINT IF EXISTS demande_rdvs_statut_check");
        DB::statement("ALTER TABLE demande_rdvs ADD CONSTRAINT demande_rdvs_statut_check CHECK (statut::text = ANY (ARRAY['EN_ATTENTE'::character varying, 'EN_ATTENTE_PAIEMENT'::character varying, 'CONFIRMEE'::character varying, 'REFUSEE'::character varying, 'ANNULEE'::character varying]::text[]))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Retour à l'ancienne contrainte
        DB::statement("ALTER TABLE demande_rdvs DROP CONSTRAINT IF EXISTS demande_rdvs_statut_check");
        DB::statement("ALTER TABLE demande_rdvs ADD CONSTRAINT demande_rdvs_statut_check CHECK (statut::text = ANY (ARRAY['EN_ATTENTE'::character varying, 'CONFIRMEE'::character varying, 'REFUSEE'::character varying, 'ANNULEE'::character varying]::text[]))");
    }
};
