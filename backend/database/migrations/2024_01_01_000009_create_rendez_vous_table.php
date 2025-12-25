<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rendez_vous', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->onDelete('cascade');
            $table->foreignId('praticien_id')->constrained('praticiens')->onDelete('cascade');
            $table->dateTime('date_heure_rdv');
            $table->integer('duree')->default(30); // en minutes
            $table->enum('statut', ['PLANIFIE', 'CONFIRME', 'EN_COURS', 'TERMINE', 'ANNULE', 'ABSENT'])->default('PLANIFIE');
            $table->foreignId('valide_par')->nullable()->constrained('secretaires')->nullOnDelete();
            $table->foreignId('demande_rdv_id')->nullable()->constrained('demande_rdvs')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rendez_vous');
    }
};
