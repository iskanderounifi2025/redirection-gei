<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Direction extends Model
{
    protected $table = 'directions'; // Spécifiez le nom de la table si différent du modèle

    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'telephone',
        'poste'
    ];

    public $timestamps = false; // Si vous gérez vos timestamps manuellement
    }
