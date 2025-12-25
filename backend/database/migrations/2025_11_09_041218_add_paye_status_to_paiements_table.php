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
        // Modifier l'enum pour ajouter PAYE
        DB::statement("ALTER TABLE paiements DROP CONSTRAINT paiements_statut_check");
        DB::statement("ALTER TABLE paiements ADD CONSTRAINT paiements_statut_check CHECK (statut IN ('EN_ATTENTE', 'VALIDE', 'PAYE', 'ECHOUE', 'REMBOURSE'))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revenir à l'ancien enum
        DB::statement("ALTER TABLE paiements DROP CONSTRAINT paiements_statut_check");
        DB::statement("ALTER TABLE paiements ADD CONSTRAINT paiements_statut_check CHECK (statut IN ('EN_ATTENTE', 'VALIDE', 'ECHOUE', 'REMBOURSE'))");
    }
};
