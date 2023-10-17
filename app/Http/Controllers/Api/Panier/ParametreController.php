<?php

namespace App\Http\Controllers\Api\Panier;

use App\Http\Controllers\Controller;
use App\Models\Commercant;
use App\Models\Discussion;
use App\Models\Panier;
use App\Models\Parametre;
use App\Models\PointLivraison;
use App\Models\Message;
use App\Models\Publicite;
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

    public function allParametres(Request $request)
    {
        $commercants = Commercant::where('statut', true)->get();
        $infos_trivia = Parametre::where('statut', true)->get();
        $points = PointLivraison::where('statut', true)->get();
        $slides = Publicite::where('statut', true)->get();
        $total_commandes = Panier::count();
        $total_discussions = Discussion::count();
        $parametres ['commercants'] = $commercants;
        $parametres['infos_trivia'] = $infos_trivia;
        $parametres['points'] = $points;
        $parametres['slides'] = $slides;
        $total_commande_progress = Panier::where('statut_livraison', 'progress')->count();
        $total_discussion_non_lus = Message::where('vu_admin', false)->count();
        $user = auth()->user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Utilisateur non trouvé! Veuillez entrer le token'], 401);
        }elseif (!$user->admin)
        {
            if ($parametres)
            {
                return response()->json(['success' => true, 'response' => $parametres]);
            }
            else
            {
                return response()->json(['success' => false, 'message' => 'Pas de parametre actif'], 404);
            }
        }
        else {
           $parametres['total_commandes'] = $total_commandes;
           $parametres['total_discussions'] = $total_discussions;
           $parametres['total_commande_progress'] = $total_commande_progress;
           $parametres['total_discussion_non_lus'] = $total_discussion_non_lus;
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

    public function countParameters(Request $request)
    {
        $user = auth()->user();
        if (!$user || !$user->admin) {
            return response()->json(['success' => false, 'message' => 'Accès non autorisé'], 403);
        }else {
            $total_commandes = Panier::count();
            $total_discussions = Discussion::count();
            return response()->json(['success' => true, 'response' => ['total_commandes' => $total_commandes, 'total_discussions' => $total_discussions]]);
        }
    }
}
