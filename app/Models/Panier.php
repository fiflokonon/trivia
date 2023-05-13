<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Panier extends Model
{
    use HasFactory;

    protected $table = 'paniers';
    protected $fillable = [
      'produits',
      'total',
      'user_id',
      'statut'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
