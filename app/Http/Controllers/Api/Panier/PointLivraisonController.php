<?php

namespace App\Http\Controllers\Api\Panier;

use App\Http\Controllers\Controller;
use App\Models\PointLivraison;
use Illuminate\Http\Request;

class PointLivraisonController extends Controller
{
    public function pointActifs()
    {
        $points = PointLivraison::where('statut', true)->get();
        if ($points->isNotEmpty())
        {
            return response()->json(['success' => true, 'response' => $points]);
        }
        else
        {
            return response()->json(['success' => false, 'message' => 'Pas de point de livraison actif'], 404);
        }
    }
}
