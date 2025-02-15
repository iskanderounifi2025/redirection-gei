<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produit extends Model
{
    // Table associée à ce modèle
    protected $table = 'produit';

    // Attributs assignables en masse
    protected $fillable = [
        'brand_id',   // Correspond à la clé étrangère de la table marques
        'name',
        'sku',
        'etat',
        'price',
        'image_path',
        'id_team',
    ];

    /**
     * Relation avec le modèle Brand.
     * 
     * Retourne la marque associée au produit.
     */
    public function brand()
    {
        // Si la clé étrangère est 'brand_id' dans la table 'produit', vous pouvez préciser la clé étrangère (pas nécessaire si c'est le nom par défaut 'brand_id')
        return $this->belongsTo(Brand::class, 'brand_id');
    }
    public function redirection()
{
    return $this->belongsTo(Redirection::class, 'redirection_id');
}
public function redirections()
    {
        return $this->belongsToMany(Redirection::class, 'produit_redirection');
    }
}
