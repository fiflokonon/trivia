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
      'statut_livraison',
      'point_livraison_id',
      'nom',
      'contact',
      'indicatif',
      'email',
      'type_recepteur',
      'nom_prenom_recepteur',
      'contact_recepteur',
      'lien_qr_code',
      'lien_facture',
      'total',
      'user_id',
      'statut_paiement',
      'id_transaction',
      'statut',
      'commercant_id',
      'total'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function commercant()
    {
        return $this->belongsTo(Commercant::class);
    }

    public function point_livraison()
    {
        return $this->belongsTo(PointLivraison::class);
    }
}
