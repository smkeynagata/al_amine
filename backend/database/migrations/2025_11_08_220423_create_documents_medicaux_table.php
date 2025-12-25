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
        Schema::create('documents_medicaux', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->foreignId('consultation_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('praticien_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('type_document', [
                'certificat_medical',
                'attestation_consultation',
                'ordonnance',
                'compte_rendu',
                'resultat_examen',
                'rapport_hospitalisation',
                'autre'
            ]);
            $table->string('titre');
            $table->text('description')->nullable();
            $table->string('fichier'); // Chemin vers le fichier
            $table->date('date_document');
            $table->string('delivre_par')->nullable(); // Nom du praticien
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents_medicaux');
    }
};
