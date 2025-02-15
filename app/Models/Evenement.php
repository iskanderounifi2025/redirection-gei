<?php
 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evenement extends Model
{
    use HasFactory;

    protected $table = 'evenements';
    public $timestamps = true;  // Cela est activé par défaut, donc vous n'avez normalement pas besoin de le spécifier

    protected $fillable = [
        'nom',
        'date_debut',
        'date_fin',
        'brand_id',
        'id_team',
    ];

    /**
     * Relation avec le modèle Brand.
     */
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
    public function redirections()
    {
        return $this->hasMany(Redirection::class, 'evenements_id');
    }

   

    // Définir la relation avec la table "campaigns"
    public function campaigns()
    {
        return $this->hasMany(Campaign::class, 'evenement_id');
    }
}
