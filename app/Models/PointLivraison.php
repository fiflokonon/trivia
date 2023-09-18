<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PointLivraison extends Model
{
    use HasFactory;
    protected $table = 'point_livraisons';
    protected $fillable = [
        'intitule',
        'description',
        'statut'
    ];
}
