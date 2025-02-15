<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   // Dans la migration de création de la table pivot
public function up()
{
    Schema::create('team_marque', function (Blueprint $table) {
        $table->id();
        $table->foreignId('team_id')->constrained('team')->onDelete('cascade'); // Clé étrangère vers la table 'team'
        $table->foreignId('marque_id')->constrained('marques')->onDelete('cascade'); // Clé étrangère vers la table 'marques'
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('team_marque');
    }
};
