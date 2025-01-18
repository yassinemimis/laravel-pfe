<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('email_templates', function (Blueprint $table) {
        $table->id();
        $table->string('name'); // Nom du template
        $table->string('subject'); // Sujet du template
        $table->text('content'); // Contenu de l'email
        $table->string('recipient'); // Destinataire (Étudiants, Entreprises, Enseignants)
        $table->timestamp('send_date')->nullable(); // Date d'envoi
        $table->timestamp('reminder_date')->nullable(); // Date de relance
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_templates');
    }
};
