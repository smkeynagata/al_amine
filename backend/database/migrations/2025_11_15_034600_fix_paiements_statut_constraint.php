<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Corriger la contrainte pour inclure CONFIRME
        DB::statement("ALTER TABLE paiements DROP CONSTRAINT paiements_statut_check");
        DB::statement("ALTER TABLE paiements ADD CONSTRAINT paiements_statut_check CHECK (statut IN ('EN_ATTENTE', 'CONFIRME', 'VALIDE', 'PAYE', 'ECHOUE', 'REMBOURSE'))");
    }

    public function down(): void
    {
        // Revenir à l'ancienne contrainte
        DB::statement("ALTER TABLE paiements DROP CONSTRAINT paiements_statut_check");
        DB::statement("ALTER TABLE paiements ADD CONSTRAINT paiements_statut_check CHECK (statut IN ('EN_ATTENTE', 'VALIDE', 'PAYE', 'ECHOUE', 'REMBOURSE'))");
    }
};
