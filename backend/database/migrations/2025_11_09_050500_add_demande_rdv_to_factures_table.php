<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('factures', function (Blueprint $table) {
            $table->foreignId('demande_rdv_id')
                ->nullable()
                ->after('consultation_id')
                ->constrained('demande_rdvs')
                ->nullOnDelete();
        });

        // Rendre consultation_id facultatif pour prendre en charge les factures d'acompte
        DB::statement('ALTER TABLE factures ALTER COLUMN consultation_id DROP NOT NULL');
    }

    public function down(): void
    {
        // Attention : cette opération échouera si des factures existent sans consultation
        DB::statement('ALTER TABLE factures ALTER COLUMN consultation_id SET NOT NULL');

        Schema::table('factures', function (Blueprint $table) {
            $table->dropForeign(['demande_rdv_id']);
            $table->dropColumn('demande_rdv_id');
        });
    }
};
