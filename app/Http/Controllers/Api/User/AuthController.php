<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

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

    public function inscription(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom' => ['required', 'string', 'max:255'],
            'prenoms' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'string', 'email', 'max:255',
                Rule::unique('users')->where(function ($query) use ($request) {
                    return $query->where('email', $request->email)->where('id', '<>', $request->id);
                })],
            'phone' => ['required', 'string', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
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
            $user = User::create([
                'nom' => $request->nom,
                'prenoms' => $request->prenoms,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'statut' => true
            ]);
            $token = $user->createToken('Token Name')->accessToken;
            return response()->json(
                [
                    'success' => true,
                    'response' => [
                        'token' => $token->token, '
                    user' => $user
                    ]]
                , 201);
        }
    }

    /*public function register(Request $request)
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
    }*/

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
