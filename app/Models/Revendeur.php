<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Revendeur extends Model
{
    use HasFactory;

    // Nom de la table
    protected $table = 'revendeurs';

    // Attributs remplissables
    protected $fillable = [
        'name',
        'email',
        'email_2',
        'nometprenom',
        'telephone',
        'telephone_2',
        'logo',
        'etat',
        'remarque',
        'direction_id',
        'commercial_id',
        'address_red',
        'revendeur_type',
        'id_team',
    ];
    // App\Models\Revendeur.php
    public function commercials()
    {
        return $this->belongsTo(Commercial::class, 'commercial_id'); // La clé étrangère est 'commercial_id'
    }

    public function revendeur()
    {
        return $this->hasMany(Revendeur::class, 'id_team', 'id_team');
    }

    // Relation avec Direction (si c'est nécessaire)
    public function direction()
    {
        return $this->belongsTo(Direction::class, 'direction_id'); // La clé étrangère est 'direction_id'
    }

}
