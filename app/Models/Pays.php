<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pays extends Model
{
    use HasFactory;
    protected $table = 'pays';
    protected $fillable = [
      'nom',
      'statut'
    ];

    public function points()
    {
        return $this->hasMany(PointLivraison::class)->where('statut', true);
    }
}
