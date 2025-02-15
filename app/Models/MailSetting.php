<?php
 namespace App\Models;

 use Illuminate\Database\Eloquent\Factories\HasFactory;
 use Illuminate\Database\Eloquent\Model;
 
 class MailSetting extends Model
 {
     use HasFactory;
 
     protected $fillable = [
         'smtp_host',
         'smtp_port',
         'smtp_username',
         'smtp_password',
         'smtp_encryption',
         'imap_host',
         'imap_port',
         'imap_username',
         'imap_password',
         'id_team',
     ];
 }
 