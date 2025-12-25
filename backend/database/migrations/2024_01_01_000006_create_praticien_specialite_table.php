<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('praticien_specialite', function (Blueprint $table) {
            $table->id();
            $table->foreignId('praticien_id')->constrained()->onDelete('cascade');
            $table->foreignId('specialite_id')->constrained()->onDelete('cascade');
            $table->enum('niveau_expertise', ['JUNIOR', 'CONFIRME', 'SENIOR', 'EXPERT'])->default('CONFIRME');
            $table->boolean('est_principale')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('praticien_specialite');
    }
};
