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
        Schema::create('examens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->foreignId('consultation_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('praticien_id')->nullable()->constrained()->onDelete('set null');
            $table->string('type_examen'); // Analyse de sang, Radio, IRM, Scanner, etc.
            $table->string('titre');
            $table->text('description')->nullable();
            $table->date('date_examen');
            $table->string('laboratoire')->nullable();
            $table->text('resultats')->nullable();
            $table->string('fichier_resultat')->nullable(); // Chemin vers le fichier PDF
            $table->enum('statut', ['en_attente', 'termine', 'annule'])->default('en_attente');
            $table->text('commentaire_medecin')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('examens');
    }
};
