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
        Schema::create('etudiant', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_utilisateur'); 
            $table->unsignedBigInteger('intitule_option'); 
            $table->float('moyenne_m1');
            $table->timestamps();
            
           
            $table->foreign('id_utilisateur')->references('id_utilisateur')->on('utilisateur_pf')->onDelete('cascade');
            $table->foreign('intitule_option')->references('id_option')->on('option')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('etudiant');
    }
};
