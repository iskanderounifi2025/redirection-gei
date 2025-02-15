<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    use HasFactory;

    protected $table = 'campaigns';
    public $timestamps = true; 
    protected $fillable = [
        'nom', 'evenement_id', 'type', 'budget', 'date_debut', 'date_fin',
        'nom_influencer', 'plateforme', 'montant', 'nombre_reels',
        'nom_ugc', 'montant_ugc', 'plateforme_ugc'
    ];

    public static function validationRules()
    {
        return [
            'nom' => 'required|string|max:255',
            'evenement_id' => 'required|exists:evenements,id',
            'type' => 'required|string|in:ads,influence,ugc,urbain,tv',
            'budget' => 'nullable|numeric|min:0',
            'date_debut' => 'nullable|date',
            'date_fin' => 'nullable|date|after_or_equal:date_debut',
            'nom_influencer' => 'nullable|string|max:255',
            'plateforme' => 'nullable|string|in:facebook,instagram,tiktok',
            'montant' => 'nullable|numeric|min:0',
            'nombre_reels' => 'nullable|integer|min:0',
            'nom_ugc' => 'nullable|string|max:255',
            'montant_ugc' => 'nullable|numeric|min:0',
            'plateforme_ugc' => 'nullable|string|in:facebook,instagram,tiktok',
        ];
    }
    public function evenement()
    {
        return $this->belongsTo(Evenement::class);
    }

   
    // Définir la relation avec la table "marques"
    public function marque()
    {
        return $this->belongsTo(Marque::class, 'brand_id');
    }

    // Définir la relation avec la table "redirections"
    public function redirections()
    {
        return $this->hasMany(Redirection::class, 'evenements_id');
    }
}
