<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    use HasFactory;

    // Les champs qui peuvent être assignés en masse
    protected $fillable = [
        'nom',
        'evenement_id',
        'type',
        'budget',
        'date_debut',
        'date_fin',
        'nom_influencer',
        'plateforme',
        'montant',
        'nombre_reels',
        'nom_ugc',
        'montant_ugc',
        'plateforme_ugc',
        'id_team'
    ];

    
    // Règles de validation
    public static function validationRules()
    {
        return [
            'nom' => 'required|string|max:255',
            'evenement_id' => 'required|exists:evenements,id',
            'type' => 'required|string|max:50',
            'budget' => 'nullable|numeric',
            'date_debut' => 'nullable|date',
            'date_fin' => 'nullable|date',
            'nom_influencer' => 'nullable|string|max:255',
            'plateforme' => 'nullable|string|max:50',
            'montant' => 'nullable|numeric',
            'nombre_reels' => 'nullable|numeric',
            'nom_ugc' => 'nullable|string|max:255',
            'montant_ugc' => 'nullable|numeric',
            'plateforme_ugc' => 'nullable|string|max:50',
        ];
    }
    public function evenement()
    {
        return $this->belongsTo(Evenement::class);
    }
}
