<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('demande_rdvs', function (Blueprint $table) {
            // Modifier la colonne statut pour ajouter PAYEE
            if (DB::getDriverName() === 'pgsql') {
                // Pour PostgreSQL, utiliser raw SQL
                DB::statement("ALTER TABLE demande_rdvs ALTER COLUMN statut TYPE varchar(255)");
                DB::statement("ALTER TABLE demande_rdvs ALTER COLUMN statut SET DEFAULT 'EN_ATTENTE'");
            } else {
                // Pour MySQL/SQLite
                $table->enum('statut', ['EN_ATTENTE_PAIEMENT', 'EN_ATTENTE', 'PAYEE', 'CONFIRMEE', 'REFUSEE', 'ANNULEE'])
                    ->default('EN_ATTENTE')
                    ->change();
            }
        });
    }

    public function down(): void
    {
        Schema::table('demande_rdvs', function (Blueprint $table) {
            if (DB::getDriverName() === 'mysql') {
                $table->enum('statut', ['EN_ATTENTE_PAIEMENT', 'EN_ATTENTE', 'CONFIRMEE', 'REFUSEE', 'ANNULEE'])
                    ->default('EN_ATTENTE')
                    ->change();
            }
        });
    }
};
