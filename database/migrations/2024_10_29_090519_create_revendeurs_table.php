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
        Schema::create('revendeurs', function (Blueprint $table) {
            $table->id(); // Clé primaire auto-incrémentée
            $table->string('name'); // Nom du revendeur
            $table->string('email')->nullable(); // Email du responsable eCommerce, nullable
            $table->string('telephone')->nullable(); // Téléphone du responsable eCommerce, nullable
            $table->string('logo')->nullable(); // Logo du revendeur, nullable
            $table->integer('etat')->default(1); // État, par défaut à 1
            $table->timestamps(); // Colonne created_at et updated_at
            Schema::table('revendeurs', function (Blueprint $table) {
                $table->integer('etat')->default(1)->change();
            });
            $table->integer('id_team')->nullable();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('revendeurs');
  
    }
};
