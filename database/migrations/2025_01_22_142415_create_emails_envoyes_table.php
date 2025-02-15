<?php
 use Illuminate\Database\Migrations\Migration;
 use Illuminate\Database\Schema\Blueprint;
 use Illuminate\Support\Facades\Schema;
 
 class CreateEmailEnvoyesTable extends Migration
 {
     public function up()
     {
         Schema::create('email_envoyes', function (Blueprint $table) {
             $table->id();
             $table->string('reference')->index();
             $table->text('destinataires'); // JSON ou texte pour stocker plusieurs e-mails
             $table->string('email_expediteur');
             $table->string('sujet');
             $table->text('contenu')->nullable(); // Peut être optionnel
             $table->timestamps();
         });
     }
 
     public function down()
     {
         Schema::dropIfExists('email_envoyes');
     }
 }
