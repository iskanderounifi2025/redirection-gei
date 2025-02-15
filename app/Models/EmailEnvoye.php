<?php
 namespace App\Models;

 use Illuminate\Database\Eloquent\Factories\HasFactory;
 use Illuminate\Database\Eloquent\Model;
 
 class EmailEnvoye extends Model
 {
     use HasFactory;
 
     protected $table = 'emails_envoyes';
 
     protected $fillable = [
         'reference',
         'destinataires',
         'email_expediteur',
         'sujet',
         'contenu',
     ];
 }
 