<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('praticiens', 'numero_ordre')) {
            Schema::table('praticiens', function (Blueprint $table) {
                $table->dropColumn('numero_ordre');
            });
        }
    }

    public function down(): void
    {
        if (!Schema::hasColumn('praticiens', 'numero_ordre')) {
            Schema::table('praticiens', function (Blueprint $table) {
                $table->string('numero_ordre')->nullable();
            });
        }
    }
};
