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
        Schema::create('marques', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Colonne pour le nom
            $table->text('description'); // Ajout de la colonne description
            $table->text('commercial_id')->nullable(); // Colonne pour la description, nullable si pas toujours requise
            $table->text('id_team')->nullable(); // Colonne pour la description, nullable si pas toujours requise
            $table->string('logo')->nullable(); // Colonne pour le logo, nullable si pas obligatoire
            $table->integer('etat')->default(1); // 1 pour actif, 0 pour inactif
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('marques', function (Blueprint $table) {
            // Supprimer la clé étrangère avant de supprimer la table
            $table->dropForeign(['commercial_id']);
            $table->dropColumn('etat');

        });
        Schema::dropIfExists('marques');
    }
};
