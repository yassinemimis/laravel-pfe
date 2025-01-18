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
        Schema::table('utilisateur_pf', function (Blueprint $table) {
            if (!Schema::hasColumn('utilisateur_pf', 'id_utilisateur')) {
                $table->unsignedBigInteger('id_utilisateur')->first()->autoIncrement()->change(); // تغيير العمود الأساسي
            }
            if (!Schema::hasColumn('utilisateur_pf', 'nom')) {
                $table->string('nom')->nullable();
            }
            if (!Schema::hasColumn('utilisateur_pf', 'prenom')) {
                $table->string('prenom')->nullable();
            }
            if (!Schema::hasColumn('utilisateur_pf', 'adresse_email')) {
                $table->string('adresse_email')->unique()->nullable();
            }
            if (!Schema::hasColumn('utilisateur_pf', 'type_utilisateur')) {
                $table->string('type_utilisateur')->nullable();
            }
            if (!Schema::hasColumn('utilisateur_pf', 'password')) {
                $table->string('password')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('utilisateur_pf', function (Blueprint $table) {
            if (Schema::hasColumn('utilisateur_pf', 'nom')) {
                $table->dropColumn('nom');
            }
            if (Schema::hasColumn('utilisateur_pf', 'prenom')) {
                $table->dropColumn('prenom');
            }
            if (Schema::hasColumn('utilisateur_pf', 'adresse_email')) {
                $table->dropColumn('adresse_email');
            }
            if (Schema::hasColumn('utilisateur_pf', 'type_utilisateur')) {
                $table->dropColumn('type_utilisateur');
            }
            if (Schema::hasColumn('utilisateur_pf', 'password')) {
                $table->dropColumn('password');
            }
        });
    }
};
