<?php

namespace App\Http\Controllers\Api\Panier;

use App\Http\Controllers\Controller;
use App\Models\Parametre;
use Illuminate\Http\Request;

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
}
