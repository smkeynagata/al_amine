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
        Schema::table('patients', function (Blueprint $table) {
            $table->decimal('poids', 5, 2)->nullable()->after('numero_mutuelle')->comment('Poids en kg');
            $table->decimal('taille', 5, 2)->nullable()->after('poids')->comment('Taille en cm');
            $table->enum('groupe_sanguin', ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'])->nullable()->after('taille');
            $table->text('maladies_chroniques')->nullable()->after('groupe_sanguin')->comment('Diabète, hypertension, etc.');
            $table->text('medicaments_actuels')->nullable()->after('maladies_chroniques');
            $table->text('chirurgies_passees')->nullable()->after('medicaments_actuels');
            $table->text('personne_contact_urgence')->nullable()->after('chirurgies_passees')->comment('Nom et téléphone');
            $table->boolean('fumeur')->default(false)->after('personne_contact_urgence');
            $table->boolean('consommation_alcool')->default(false)->after('fumeur');
            $table->text('notes_supplementaires')->nullable()->after('consommation_alcool');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropColumn([
                'poids',
                'taille',
                'groupe_sanguin',
                'maladies_chroniques',
                'medicaments_actuels',
                'chirurgies_passees',
                'personne_contact_urgence',
                'fumeur',
                'consommation_alcool',
                'notes_supplementaires'
            ]);
        });
    }
};
