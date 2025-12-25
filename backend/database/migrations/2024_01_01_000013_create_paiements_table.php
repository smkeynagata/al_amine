<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('paiements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('facture_id')->constrained()->onDelete('cascade');
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->decimal('montant', 10, 2);
            $table->enum('methode_paiement', ['ESPECES', 'WAVE', 'ORANGE_MONEY', 'FREE_MONEY', 'CARTE']);
            $table->dateTime('date_paiement');
            $table->string('reference')->unique();
            $table->string('numero_transaction')->nullable();
            $table->foreignId('effectue_par')->nullable()->constrained('secretaires')->onDelete('set null');
            $table->enum('statut', ['EN_ATTENTE', 'VALIDE', 'ECHOUE', 'REMBOURSE'])->default('VALIDE');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paiements');
    }
};

