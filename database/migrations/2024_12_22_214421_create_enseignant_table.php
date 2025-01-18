<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // database/migrations/xxxx_xx_xx_xxxxxx_create_enseignant_table.php

    public function up()
    {
        Schema::create('enseignant', function (Blueprint $table) {
            $table->id('id_ens');
            $table->foreignId('id_utilisateur')->constrained('utilisateur_pf', 'id_utilisateur')->onDelete('cascade');
            $table->date('date_recrutement');
            $table->string('grade_ens', 50);
            $table->boolean('est_responsable');
            $table->timestamps();
        });
    }
    


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enseignant');
    }
};
