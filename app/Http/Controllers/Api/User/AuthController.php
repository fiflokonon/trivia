<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('MyAppToken')->accessToken;
            return response()->json([
                'success' => true,
                'response' => [
                    'token' => $token->token,
                    'user' => $user
                ]
                ], 200);
        } else {
            return response()->json(['success' => false, 'message' => 'Identifiants incorrects'], 401);
        }
    }


    public function register(Request $request)
    {
        // Validation des données du formulaire
        $validatedData = $request->validate([
            'prenoms' => 'required|string|max:255',
            'nom' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users|max:255',
            'password' => 'required|string|min:8|max:255',
            'phone' => 'required|string|unique:users|max:255',
        ]);

        // Créer un nouvel utilisateur avec les données validées
        $user = User::create([
            'nom' => $validatedData['nom'],
            'prenoms' => $validatedData['prenoms'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'phone' => $validatedData['phone'],
            'statut' => true
        ]);
        // Générer un jeton d'API pour l'utilisateur
        $token = $user->createToken('MyAppToken')->accessToken;
        // Renvoyer la réponse JSON avec le jeton d'API
        return response()->json([
            'success' => true,
            'response' => [
                'token' => $token->token,
                'user' => $user
            ]
            ], 201);
    }

    public function getMe(Request $request)
    {
        if (\auth()->check())
        {
            return response()->json(['success' => true, 'user' => \auth()->user()]);
        }
        else
        {
            return response()->json(['success' => false]);
        }
    }
}
