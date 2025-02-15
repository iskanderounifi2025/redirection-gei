<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Redirection extends Model
{
    use HasFactory;

    // Nom de la table
    protected $table = 'redirections';

    // Colonnes remplissables
    protected $fillable = [
        'brand_id',
        'product_id',
        'nom_produit' ,
        'prix_intial',
        'qts_produit',
         'prix_produit',
         'timber_fiscal',
         'frais_laivraison',
        'reduction_produit',
        'reference',
        'client_nom',
        'client_prenom',
        'client_email',
        'client_phone',
        'client_adresse', // Nouvelle colonne ajoutée
        'revendeur_id',
        'date_naissance_client',
        'sexe',
        'etat_red',
        'source_red',
        'evenements_id',
        'id_team',
    ];

    // Relations avec les autres modèles si nécessaire
    public function brand()
    {
        return $this->belongsTo(Brand::class);
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);

    }
    public function produit()
{
    return $this->belongsTo(Produit::class, 'product_id'); // Assurez-vous que 'product_id' est la clé étrangère correcte
    return $this->belongsTo(Produit::class);
    return $this->hasMany(Produit::class, 'redirection_id');

}
public function produits()
{
    return $this->hasMany(Produit::class);
 
}
 
public function scopeYearToDate($query)
{
    return $query->whereYear('created_at', now()->year);
}

public function scopeMonthToDate($query)
{
    return $query->whereYear('created_at', now()->year)
                 ->whereMonth('created_at', now()->month);
}

public function scopeDayToDate($query)
{
    return $query->whereDate('created_at', today());
}
public function evenement()
    {
        return $this->belongsTo(Evenement::class, 'evenements_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public static function getPendingRedirections()
    {
        return self::where('etat_red', 1) // état non validé
            ->where('created_at', '<', Carbon::now()->subMinutes(30)) // Plus de 30 minutes
            ->get();
    }
}
