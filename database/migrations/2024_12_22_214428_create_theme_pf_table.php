<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // database/migrations/xxxx_xx_xx_xxxxxx_create_theme_pf_table.php

    public function up()
    {
        Schema::create('theme_pf', function (Blueprint $table) {
            $table->id('id_theme');
            $table->string('titre_theme', 255);
            $table->string('type_pf', 50);
            $table->text('description');
            $table->foreignId('affectation1')->constrained('utilisateur_pf', 'id_utilisateur')->onDelete('cascade');
            $table->foreignId('affectation2')->constrained('utilisateur_pf', 'id_utilisateur')->onDelete('cascade');
            $table->foreignId('encadrant_president')->nullable();
            $table->foreignId('co_encadrant')->constrained('enseignant', 'id_ens')->onDelete('cascade');
            $table->foreignId('intitule_option')->constrained('option', 'id_option')->onDelete('cascade');
            $table->timestamps();
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('theme_pf');
    }
};
