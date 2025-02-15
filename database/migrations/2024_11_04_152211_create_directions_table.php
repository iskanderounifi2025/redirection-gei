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
        Schema::create('directions', function (Blueprint $table) {
            $table->id(); // Créé une colonne 'id' de type auto-incrémentée en tant que clé primaire
            $table->string('nom', 100); // Colonne pour le nom
            $table->string('prenom', 100); // Colonne pour le prénom
            $table->string('email', 100)->unique(); // Colonne pour l'email unique
            $table->string('telephone', 15)->nullable(); // Colonne pour le numéro de téléphone
            $table->string('poste', 100)->nullable(); // Colonne pour le poste occupé
            $table->timestamp('date_creat')->useCurrent(); // Colonne pour la date de création
            $table->timestamp('date_update')->useCurrent()->useCurrentOnUpdate(); // Colonne pour la date de mise à jour
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('directions');
    }
};
