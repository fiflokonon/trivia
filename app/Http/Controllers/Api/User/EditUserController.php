<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class EditUserController extends Controller
{

    public function editProfile(Request $request)
    {
        if ($request->user()) {
            $user = $request->user();

            $data = $request->only([
                'nom',
                'indicatif',
                'phone',
                'sexe',
                'date_naissance',
                'point_livraison_id',
                'password',
            ]);

            //Vérification et validation des champs de formulaire
            $validator = Validator::make($data, [
                'nom' => 'required|string|max:255',
                'indicatif' => 'required|string|min:3',
                'phone' => 'nullable|string|max:20',
                'password' => 'nullable|string|min:6',
                'sexe' => 'nullable|string|max:1',
                'date_naissance' => 'nullable|date',
                'point_livraison_id' => 'nullable|integer'
            ]);
            if ($validator->fails()) {
                return response()->json(['success' => false, 'message' => $validator->errors()], 400);
            }

            // Vérifier si un nouveau mot de passe a été fourni et le hasher
            if ($request->has('password')) {
                $data['password'] = Hash::make($data['password']);
            }

            // Mise à jour des informations de l'utilisateur
            $user->update($data);
            return response()->json(['success' => true, 'message' => 'Informations de profil mises à jour avec succès', 'user' => $user], 200);
        } else {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }
    }


}
