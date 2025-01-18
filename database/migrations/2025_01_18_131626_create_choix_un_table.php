<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChoixUnTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('choix_un', function (Blueprint $table) {
            $table->id('id_choix');
            $table->unsignedBigInteger('id_etu'); 
            $table->unsignedBigInteger('id_etu2'); 
            $table->unsignedBigInteger('id_theme'); 
            $table->boolean('valid');
            $table->timestamps(); 

            $table->foreign('id_etu2')->references('id_etu')->on('etudiant')->onDelete('cascade');
            $table->foreign('id_etu')->references('id_etu')->on('etudiant')->onDelete('cascade');
            $table->foreign('id_theme')->references('id_theme')->on('theme_pf')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('choix_un');
    }
}
