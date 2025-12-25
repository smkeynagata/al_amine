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
        Schema::create('mesures_sante', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->onDelete('cascade');
            $table->date('date_mesure');
            $table->decimal('poids', 5, 2)->nullable(); // en kg
            $table->decimal('taille', 5, 2)->nullable(); // en cm
            $table->integer('tension_systolique')->nullable(); // en mmHg
            $table->integer('tension_diastolique')->nullable(); // en mmHg
            $table->integer('frequence_cardiaque')->nullable(); // battements/min
            $table->decimal('temperature', 4, 1)->nullable(); // en °C
            $table->decimal('glycemie', 5, 2)->nullable(); // en g/L
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['patient_id', 'date_mesure']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mesures_sante');
    }
};
