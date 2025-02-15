<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEquipesTable extends Migration
{
    public function up()
    {
        Schema::create('equipes', function (Blueprint $table) {
            $table->id();
            $table->string('nometprenom');
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->enum('role', [1, 2]); // Exemple de rôles, modifiez si nécessaire
            $table->string('image')->nullable();
            $table->unsignedBigInteger('marque_id'); // Assurez-vous que cette colonne correspond à la clé primaire de 'marques'
            $table->foreign('marque_id') // Définir la clé étrangère avec une action de suppression en cascade si nécessaire
                  ->references('id') // Colonne de référence dans la table 'marques'
                  ->on('marques') // Table de référence
                  ->onDelete('cascade'); // Action de suppression en cascade si la marque est supprimée
            $table->timestamps();
            $table->integer('id_team')->nullable();

        });

     
            Schema::table('team', function (Blueprint $table) {
                $table->unsignedBigInteger('marque_id')->change();
            });
        

    }

    public function down()
    {
        // Pour éviter les erreurs de contrainte de clé étrangère lors de la suppression de la table
        Schema::table('equipes', function (Blueprint $table) {
            $table->dropForeign(['marque_id']);
        });
        Schema::table('team', function (Blueprint $table) {
            $table->integer('marque_id')->change();
        });
        Schema::dropIfExists('equipes');
    }
}
