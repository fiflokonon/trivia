<?php

namespace App\Http\Controllers\Api\Panier;

use App\Http\Controllers\Controller;
use App\Models\Commercant;
use App\Models\Parametre;
use App\Models\PointLivraison;
use App\Models\Publicite;

class ParametreController extends Controller
{
    public function listeParametres()
    {
        $parametres = Parametre::where('statut', true)->get();
        if ($parametres->isNotEmpty())
        {
            return response()->json(['success' => true, 'reponse' => $parametres]);
        }
        else
        {
            return response()->json(['success' => false, 'message' => 'Pas de paramètre actif']);
        }
    }

    public function allParametres()
    {
        $commercants = Commercant::where('statut', true)->get();
        $infos_trivia = Parametre::where('statut', true)->get();
        $points = PointLivraison::where('statut', true)->get();
        $slides = Publicite::where('statut', true)->get();
        $parametres ['commercants'] = $commercants;
        $parametres['infos_trivia'] = $infos_trivia;
        $parametres['points'] = $points;
        $parametres['slides'] = $slides;
        if ($parametres)
        {
            return response()->json(['success' => true, 'response' => $parametres]);
        }
        else
        {
            return response()->json(['success' => false, 'message' => 'Pas de parametre actif'], 404);
        }

    }
}
