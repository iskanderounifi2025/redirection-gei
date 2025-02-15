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
    Schema::create('sites', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('url');
        $table->string('consumer_key');
        $table->string('consumer_secret');
        $table->unsignedBigInteger('brand_id');
        $table->integer('etat_s')->default(1);
        $table->timestamps();
    });
}

    public function down()
    {
        Schema::dropIfExists('sites');
    }
};
