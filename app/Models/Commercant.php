<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commercant extends Model
{
    use HasFactory;
    protected $table = 'commercants';
    protected $fillable = [
      'nom',
      'montant_min',
      'montant_max',
      'frais',
      'logo'
    ];
}
