<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // Import correct du trait
use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'url', 'consumer_key', 'consumer_secret','brand_id'
    ];

 // Relation avec les commandes, si vous avez une telle table dans votre base de données
 public function orders()
 {
     return $this->hasMany(Order::class);  // Ajustez si nécessaire
 }
 public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
    public function Revendeur()
    {
        return $this->belongsTo(Revendeur::class);
    }
}