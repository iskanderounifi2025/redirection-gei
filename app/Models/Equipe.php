<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;  // Assurez-vous d'importer cette classe
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipe extends Authenticatable  // Étendez Authenticatable ici
{
    use HasFactory, Notifiable;

    // Définir le nom de la table si différent de la convention Laravel
    protected $table = 'team';  // Assurez-vous que la table est correcte

    // Définir les colonnes modifiables
    protected $fillable = [
        'nometprenom', 'username', 'password', 'email', 'role', 'etat', 'image', 'marque_id','id_team'
    ];
 // Convertir la chaîne de marque_id en tableau lors de la récupération des données
 /*protected $casts = [
    'marque_id' => 'integer', // Laravel gère la conversion en tableau
];
*/
// Vérifie si une marque est déjà associée
public function hasMarque($marqueId)
{
    return in_array($marqueId, $this->marque_id);
}

// Ajoute une marque à l'équipe
public function addMarque($marqueId)
{
    if (!$this->hasMarque($marqueId)) {
        $this->marque_id[] = $marqueId;
        $this->save();
    }
}
    // Définir la relation avec la table Marque
    public function brand()
    {
        return $this->belongsTo(Brand::class); // Assurez-vous que 'brand_id' est bien le nom de la clé étrangère
    }

    // Hachage du mot de passe à l'aide de la méthode setPasswordAttribute
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    // Vous pouvez ajouter d'autres méthodes nécessaires ici pour l'authentification
    // Dans le modèle Equipe

// Dans le modèle Equipe (ou Team selon votre nomenclature)
 // Dans le modèle Equipe (anciennement 'Team')
public function marques()
{
    return $this->belongsTo(Marque::class, 'marque_id');
}


}
