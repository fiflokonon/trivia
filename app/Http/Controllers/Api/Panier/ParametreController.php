<?php

namespace App\Http\Controllers\Api\Panier;

use App\Http\Controllers\Controller;
use App\Models\Commercant;
use App\Models\Discussion;
use App\Models\Notification;
use App\Models\Panier;
use App\Models\Parametre;
use App\Models\Pays;
use App\Models\PointLivraison;
use App\Models\Message;
use App\Models\Publicite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
        $user = auth()->user();
        $commercants = Commercant::where('statut', true)->get();
        $infos_trivia = Parametre::where('statut', true)->get();
        $points = PointLivraison::where('statut', true)->get();
        $slides = Publicite::where('statut', true)->get();
        $pays = Pays::all();
        #$pays = Pays::where('statut', true)->get();
        $pays_relais = Pays::with('points')->get();
        $total_commandes = Panier::count();
        $total_discussions = Discussion::count();
        $notifications_count = Notification::where('user_id', $user->id)->where('vu', false)->count();
        $parametres ['commercants'] = $commercants;
        $parametres['infos_trivia'] = $infos_trivia;
        $parametres['points'] = $points;
        $parametres['slides'] = $slides;
        $parametres['pays'] = $pays;
        $parametres['pays_relais'] = $pays_relais;
        $parametres['nombre_notifications'] = $notifications_count;
        #$user = auth()->user();
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
            $total_commande_progress = Panier::where('statut_livraison', 'progress')->count();
            $total_discussion_non_lus = Message::where('vu_admin', false)->count();
            return response()->json(['success' => true, 'response' =>
            [
                'total_commandes' => $total_commandes,
                'total_discussions' => $total_discussions,
                'total_commande_progress' => $total_commande_progress,
                'total_discussion_non_lus' => $total_discussion_non_lus
            ]
        ]);
        }
    }

    public function ajoutPays(Request $request)
    {
        $user = auth()->user();
        if (!$user || !$user->admin) {
            return response()->json(['success' => false, 'message' => 'Accès non autorisé'], 403);
        }else {
            $validator = Validator::make($request->all(), [
                'pays' => ['required', 'string', 'max:255']
            ]);
            if ($validator->fails()) {
                $messages = $validator->errors();
                foreach ($messages->messages() as $key => $value) {
                    if ($messages->has($key . '.required')) {
                        $response = $key;
                        return response()->json([
                            'success' => false,
                            'message' => $response
                        ], 400);
                    } elseif ($messages->has($key . '.unique')) {
                        $response = $key;
                        return response()->json([
                            'success' => false,
                            'message' => $response
                        ], 400);
                    } else {
                        $response = $value[0];
                        return response()->json([
                            'success' => false,
                            'message' => $response
                        ], 400);
                    }
                }
            }
            else{
                try {
                    $pays = Pays::create([
                       'nom' => $request->pays,
                       'statut' => true
                    ]);
                    return response()->json(['success' => true, 'response' => $pays, 'message' => 'Pays ajouté avec succès']);
                }catch (\Exception $exception){
                    return response()->json(['success' => false, 'message' => $exception->getMessage()], 400);
                }
            }
        }
    }

    public function desactiverPays($pays_id, Request $request)
    {
        $user = auth()->user();
        if (!$user || !$user->admin) {
            return response()->json(['success' => false, 'message' => 'Accès non autorisé'], 403);
        }else {
            $pays = Pays::find($pays_id);
            if ($pays && $pays->statut){
                $pays->statut = false;
                $pays->save();
                return response()->json(['success' => true, 'message' => 'Pays désactivé avec succès']);
            }else{
                return response()->json(['success' => false, 'message' => 'Pays indisponible']);
            }
        }
    }

    public function activerPays($pays_id, Request $request)
    {
        $user = auth()->user();
        if (!$user || !$user->admin) {
            return response()->json(['success' => false, 'message' => 'Accès non autorisé'], 403);
        }else {
            $pays = Pays::find($pays_id);
            if ($pays){
                $pays->statut = true;
                $pays->save();
                return response()->json(['success' => true, 'message' => 'Pays activé avec succès']);
            }else{
                return response()->json(['success' => false, 'message' => 'Pays indisponible']);
            }
        }
    }

    public function listePays(Request $request)
    {
        $pays = Pays::with('points')->get();
        if ($pays){
            return response()->json(['success' => true, 'response' => $pays]);
        }else{
            return response()->json(['success' => false, 'message' => 'Liste vide'], 404);
        }
    }
}
