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
        Schema::create('evenements', function (Blueprint $table) {
            $table->id(); // ID auto-incrémenté pour chaque événement
            $table->string('nom'); // Nom de l'événement
            $table->dateTime('date_debut'); // Date de début de l'événement
            $table->dateTime('date_fin'); // Date de fin de l'événement
            $table->foreignId('brand_id')->constrained()->onDelete('cascade'); // Clé étrangère vers la table brands
            $table->integer('id_team');
            $table->timestamps(); // Colonnes created_at et updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evenements');
    }
};
