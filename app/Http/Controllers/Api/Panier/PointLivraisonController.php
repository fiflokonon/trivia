<?php

namespace App\Http\Controllers\Api\Panier;

use App\Http\Controllers\Controller;
use App\Models\Pays;
use App\Models\PointLivraison;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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

    public function ajoutPointLivraison($pays_id, Request $request)
    {
        $pays = Pays::find($pays_id);
        if ($pays && $pays->statut){
            $user = auth()->user();
            if (!$user || !$user->admin) {
                return response()->json(['success' => false, 'message' => 'Accès non autorisé'], 403);
            }else {
                $validator = Validator::make($request->all(), [
                    'intitule' => ['required', 'string', 'max:255'],
                    'description' => ['nullable', 'string', 'max:255']
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
                } else {
                    try {
                        PointLivraison::create([
                            'pays_id' => $pays_id,
                            'intitule' => $request->intitule,
                            'description' => $request->description,
                            'statut' => true
                        ]);
                        return response()->json(['success' => true, 'response' => Pays::with('points')->where('id', $pays_id)->get(), 'message' => 'Point de livraison ajouté avec succès']);
                    } catch (\Exception $exception) {
                        return response()->json(['success' => false, 'message' => $exception->getMessage()], 400);
                    }
                }
            }
        }else{
            return response()->json(['success' => false, 'message' => 'Pays indisponible'], 404);
        }
    }

    public function activerPointLivraison($point_id, Request $request)
    {
        $user = $request->user();
        if (!$user || !$user->admin) {
            return response()->json(['success' => false, 'message' => 'Accès non autorisé'], 403);
        }else {
            $point = PointLivraison::find($point_id);
            if ($point){
                $point->statut = true;
                $point->save();
                return response()->json(['success' => true, 'message' => 'Point de livraison activé avec succès']);
            }else{
                return response()->json(['success' => false, 'message' => 'Point de livraison indisponible']);
            }
        }
    }

    public function desactiverPointLivraison($point_id, Request $request)
    {
        $user = $request->user();
        if (!$user || !$user->admin) {
            return response()->json(['success' => false, 'message' => 'Accès non autorisé'], 403);
        }else {
            $point = PointLivraison::find($point_id);
            if ($point && $point->statut){
                $point->statut = false;
                $point->save();
                return response()->json(['success' => true, 'message' => 'Point de livraison désactivé avec succès']);
            }else{
                return response()->json(['success' => false, 'message' => 'Point de livraison indisponible']);
            }
        }
    }
}
