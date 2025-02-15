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
    if (!Schema::hasTable('produit')) {
        Schema::create('produit', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('brand_id')->nullable();
            $table->string('name');
            $table->string('sku', 100);
            $table->unsignedInteger('category_id')->nullable();
            $table->integer('etat')->default(1); // État, par défaut à 1
            $table->decimal('price', 10, 2)->nullable();
            $table->text('image_path');
            $table->integer('id_team')->nullable();
            $table->timestamps();

        });
    }
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produit');
    }
};
