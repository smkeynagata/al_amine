<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('demande_rdvs', function (Blueprint $table) {
            $table->enum('mode_paiement', ['EN_LIGNE', 'SUR_PLACE'])->default('SUR_PLACE')->after('statut');
            $table->enum('methode_paiement_choisie', ['CARTE_BANCAIRE', 'WAVE', 'ORANGE_MONEY', 'SUR_PLACE'])->nullable()->after('mode_paiement');
            $table->boolean('paiement_effectue')->default(false)->after('methode_paiement_choisie');
            $table->string('paydunya_token')->nullable()->after('paiement_effectue');
            $table->decimal('montant_avance', 10, 2)->nullable()->after('paydunya_token')->comment('Montant payé en avance');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('demande_rdvs', function (Blueprint $table) {
            $table->dropColumn(['mode_paiement', 'methode_paiement_choisie', 'paiement_effectue', 'paydunya_token', 'montant_avance']);
        });
    }
};
