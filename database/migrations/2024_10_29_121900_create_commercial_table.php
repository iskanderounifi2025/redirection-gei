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
        Schema::create('commercial', function (Blueprint $table) {
            $table->id();
            $table->string('nomprenom');
            $table->string('email')->unique();
            $table->string('telephone')->nullable();
            $table->string('image')->nullable();
            $table->integer('etat')->default(1); // État, par défaut à 1
            $tabel->integer('id_team')->nullable();
            $table->timestamps();
            Schema::table('revendeurs', function (Blueprint $table) {
                $table->integer('etat')->default(1)->change();
               
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commercial');
    }
};
