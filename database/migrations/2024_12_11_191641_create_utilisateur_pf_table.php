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
        if (!Schema::hasTable('utilisateur_pf')) {
            Schema::create('utilisateur_pf', function (Blueprint $table) {
                $table->id('id_utilisateur'); 
                $table->string('nom');
                $table->string('prenom');
                $table->string('adresse_email')->unique();
                $table->string('type_utilisateur');
                $table->string('password');
                $table->timestamps(); 
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('utilisateur_pf');
    }
};
