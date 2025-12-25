<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('consultations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rendez_vous_id')->constrained('rendez_vous')->onDelete('cascade');
            $table->foreignId('praticien_id')->constrained()->onDelete('restrict');
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->dateTime('date_consultation');
            $table->text('motif');
            $table->json('constantes')->nullable(); // tension, poids, température, etc.
            $table->text('examen_clinique')->nullable();
            $table->text('diagnostic')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('est_validee')->default(false);
            $table->timestamp('validee_le')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('consultations');
    }
};

