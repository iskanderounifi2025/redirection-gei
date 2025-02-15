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
        Schema::create('redirections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('brand_id')->constrained()->onDelete('cascade');
            $table->text('reference')->nullable(); // Rendre 'reference' nullable
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->text('nom_produit'); // Ajoute nom_produit après product_id
            $table->integer('qts_produit');//Quantitie de  produit
            $table->integer('prix_intial');
            $table->integer('timber_fiscal');
            $table->integer('frais_laivraison');
            $table->integer('prix_produit'); // Ajoute prix_produit après nom_produit
            $table->integer('reduction_produit'); // Ajoute reduction_produit après prix_produit
            $table->string('client_nom');
            $table->string('client_prenom');
            $table->string('client_email');
            $table->string('client_phone');
            $table->text('client_adresse'); // Nouvelle colonne ajoutée pour l'adresse du client
            $table->string('revendeur_id');
            $table->integer('etat_red')->default(1); // Vous pouvez définir une valeur par défaut ici
            $table->integer('id_team');
            $table->timestamps(); 
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('redirections');
    }
};
