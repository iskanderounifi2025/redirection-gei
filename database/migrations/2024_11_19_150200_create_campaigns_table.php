<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCampaignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaigns', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->string('nom');
            $table->foreignId('evenement_id')->constrained('evenements'); // Référence à la table `evenements`
            $table->string('type', 50);
            $table->decimal('budget', 10, 2)->nullable();
            $table->date('date_debut')->nullable();
            $table->date('date_fin')->nullable();
            $table->string('nom_influencer')->nullable();
            $table->string('plateforme', 50)->nullable();
            $table->decimal('montant', 10, 2)->nullable();
            $table->integer('nombre_reels')->nullable();
            $table->string('nom_ugc')->nullable();
            $table->decimal('montant_ugc', 10, 2)->nullable();
            $table->string('plateforme_ugc', 50)->nullable();
            $tabme->integer('id_team')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('campaigns');
    }
}
