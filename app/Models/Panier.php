<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Panier extends Model
{
    use HasFactory;

    protected $table = 'paniers';
    protected $fillable = [
      'numero_panier',
      'produits',
      'sous_total',
      'frais_fournisseur',
      'frais_livraison',
      'pays_livraison',
      'point_relais',
      'nom',
      'prenoms',
      'contact',
      'email',
      'type_recepteur',
      'nom_prenom_recepteur',
      'lien_qr_code',
      'total',
      'user_id',
      'statut_paiement',
      'id_transaction',
      'statut'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
