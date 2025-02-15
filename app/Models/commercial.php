<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class commercial extends Model
{
  

    use HasFactory;

    // Specify the table if it's not the plural form of the model name
    protected $table = 'commercial';

    // Mass assignable attributes
    protected $fillable = ['nomprenom', 'email', 'telephone', 'image','direction_id','id_team'];
    // App\Models\Commercial.php
    public function revendeurs()
    {
        return $this->hasMany(Revendeur::class, 'commercial_id'); // La clé étrangère est 'commercial_id'
    }
}



