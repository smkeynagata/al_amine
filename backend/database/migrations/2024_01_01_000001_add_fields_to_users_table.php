<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('prenom')->after('name');
            $table->date('date_naissance')->nullable()->after('email');
            $table->enum('sexe', ['M', 'F'])->after('date_naissance');
            $table->string('numero_cni', 13)->unique()->after('sexe');
            $table->string('telephone', 20)->after('numero_cni');
            $table->text('adresse')->nullable()->after('telephone');
            $table->string('quartier')->nullable()->after('adresse');
            $table->string('ville')->default('Dakar')->after('quartier');
            $table->enum('role', ['PATIENT', 'PRATICIEN', 'SECRETAIRE', 'ADMIN'])->default('PATIENT')->after('ville');
            $table->enum('statut_compte', ['ACTIF', 'SUSPENDU', 'DESACTIVE'])->default('ACTIF')->after('role');
            $table->string('photo')->nullable()->after('statut_compte');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'prenom', 'date_naissance', 'sexe', 'numero_cni',
                'telephone', 'adresse', 'quartier', 'ville',
                'role', 'statut_compte', 'photo'
            ]);
        });
    }
};

