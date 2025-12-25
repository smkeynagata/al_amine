<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('demande_rdvs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->onDelete('cascade');
            $table->foreignId('praticien_id')->constrained('praticiens')->onDelete('cascade');
            $table->foreignId('specialite_id')->constrained('specialites')->onDelete('cascade');
            $table->dateTime('date_heure_souhaitee');
            $table->text('motif');
            $table->enum('statut', ['EN_ATTENTE', 'CONFIRMEE', 'REFUSEE', 'ANNULEE'])->default('EN_ATTENTE');
            $table->foreignId('traite_par')->nullable()->constrained('secretaires')->nullOnDelete();
            $table->text('reponse_secretaire')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('demande_rdvs');
    }
};
