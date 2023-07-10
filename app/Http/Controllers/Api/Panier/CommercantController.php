<?php

namespace App\Http\Controllers\Api\Panier;

use App\Http\Controllers\Controller;
use App\Models\Commercant;
use Illuminate\Http\Request;

class CommercantController extends Controller
{
    public function listeCommercants()
    {
        $commercants = Commercant::where('statut', true)->get();
        if ($commercants->isNotEmpty())
        {
            return response()->json(['success' => true, 'reponse' => $commercants]);
        }
        else
        {
            return response()->json(['success' => false, 'message' => 'Pas de commerçant actif disponible'], 404);
        }
    }



}
