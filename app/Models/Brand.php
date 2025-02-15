<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;

    // Si le nom de la table n'est pas la forme plurielle du nom du modèle
    protected $table = 'marques';

    // Définir les attributs mass assignable
    protected $fillable = ['id', 'name', 'commercial_id', 'logo','etat','id_team',];

    // Pas de méthode "brand" ici, car elle doit être dans le modèle Produit.
    
public function produits()
{
    return $this->hasMany(Produit::class, 'brand_id');
}
public function campaigns()
{
    return $this->hasMany(Campaign::class, 'brand_id');
}

// Définir la relation avec la table "redirections"
public function redirections()
{
    return $this->hasMany(Redirection::class, 'brand_id');
}

// Exemple de relation hasMany entre Brand et Equipe
public function equipes()
{
    return $this->hasMany(Equipe::class);
    return $this->hasMany(Equipe::class, 'marque_id');

}


}

