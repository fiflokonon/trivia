<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Mail\VerificationCodeMail;
use App\Models\User;
use App\Models\VerificationCode;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        if (Auth::attempt($credentials)) {
            $user = $request->user();
            if (!$user->verified_email)
            {
                $code = $this->generateCode($user->email);
                $emailSent = $this->sendVerificationCode($user->email, $code);
                if ($emailSent)
                {
                    return response()->json(['success' => false, 'activation' => true , 'message' => "Votre compte n'est pas activé. Vous venez de recevoir un code d'activation par e-mail. Veuillez entrer le code pour activer votre compte."], 401);
                }
                else
                {
                    return response()->json(['success' => false, 'message' => "Erreu lors de l'envoi de l'email! Veuillez reéssayer la connexion"], 500);
                }
            }
            else
            {
                // Générer un nouveau jeton d'authentification pour l'utilisateur
                $token = $user->createToken('AuthToken')->plainTextToken;
                $token = explode('|', $token)[1];
                return response()->json([
                    'success' => true,
                    'response' => [
                        'token' => $token,
                        'user' => $user
                    ]
                ], 200);
            }

        }
        else
        {
            return response()->json(['success' => false, 'message' => 'Identifiants invalides'], 401);
        }
    }

    public function inscription(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'string', 'email', 'max:255',
                Rule::unique('users')->where(function ($query) use ($request) {
                    return $query->where('email', $request->email)->where('id', '<>', $request->id);
                })],
            'indicatif' => ['required', 'string'],
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
            try {
                $code = $this->generateCode($request->email);
                $email = $this->sendVerificationCode($request->email, $code);
                if ($email)
                {
                    User::create([
                    'nom' => $request->nom,
                    #'prenoms' => $request->prenoms,
                    'email' => $request->email,
                    'indicatif' => $request->indicatif,
                    'phone' => $request->phone,
                    'password' => Hash::make($request->password),
                    'statut' => true
                ]);
                    return response()->json(['success' => true,
                        'reponse' => $code,
                        'message' => 'Vous avez reçu un code par email! Veuillez l\'entrer afin de valider votre compte'], 201);
                }
                else
                {
                    return response()->json(['success' => false, 'message' => "Erreur lors de l'envoi de l'email"], 400);
                }

            }catch (\Exception $exception)
            {
                return response()->json(['success' => false, 'message' => $exception->getMessage()], 400);
            }
        }
    }


    public function getMe(Request $request)
    {
        $user = $request->user();
        if ($user)
        {
            return response()->json(['success' => true, 'reponse' => $user]);
        }
        else
        {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }
    }

    public function generateCode($email) {

        $code = str_pad(mt_rand(0, 9999), 4, '0', STR_PAD_LEFT);
        // Vérifier si la clé temporaire existe déjà pour l'email fourni
        while (VerificationCode::where('email', $email)->where('code', $code)->exists()) {
            // Si la clé existe déjà, générer une nouvelle clé unique
            $code = str_pad(mt_rand(0, 9999), 4, '0', STR_PAD_LEFT);
        }
        // Enregistrer la clé temporaire dans la base de données
        VerificationCode::create([
            'id' => Str::uuid(),
            'email' => $email,
            'code' => $code,
            'expires_at' => now()->addHours(24),
        ]);
        return $code;
    }

    public function sendVerificationCode($email, $code)
    {
        try {
            Mail::to($email)->send(new VerificationCodeMail($code));
            return true; // Email sent successfully
        } catch (\Throwable $e) {
            #die(var_dump($e->getMessage()));
            return false; // Error occurred during email sending
        }
    }

    public function validateCode(Request $request) {
        $code = $request->code;
        $email = $request->email;
        $password = $request->password;
        $user = User::where('email', $email)->first();
        if ($user)
        {
            $verification_code = VerificationCode::where('is_used', false)
                ->where('code', $code)
                ->where('email', $email)
                ->where('expires_at', '>', now())
                ->first();
            if ($verification_code) {
                try {
                    // Vérifier si le mot de passe de l'utilisateur correspond
                    if (Hash::check($password, $user->password)) {
                        $verification_code->markAsUsed();
                        $user->verified_email = true;
                        $user->statut = true;
                        $user->save();
                        $token = $user->createToken('Token Name')->plainTextToken;
                        $token = explode('|', $token)[1];
                        return response()->json(
                            [
                                'success' => true,
                                'response' => [
                                    'token' => $token,
                                    'user' => $user
                                ]]
                            , 201);
                    } else {
                        // Si le mot de passe ne correspond pas, annuler la validation du compte
                        $user->verified_email = false;
                        $user->statut = false;
                        $user->save();
                        return response()->json(['success' => false, 'message' => 'Échec de l\'authentification'], 401);
                    }
                } catch (Exception $exception) {
                    return response()->json(['success' => false, 'message' => $exception->getMessage()], 500);
                }
            }
            else
            {
                return response()->json(['success' => false, 'message' => 'Code Invalide']);
            }
        }
        else
        {
            return response()->json(['success' => false, 'message' => 'Utilisateur non trouvé ou inactif'], 404);
        }
    }

    public function addProfilePhoto(Request $request)
    {
        $user = $request->user();
        if($user && $user->statut)
        {
            $photo = $this->uploadPhoto($request);
            if ($photo)
            {
                $user->photo_profil = 'profil/'.$photo;
                try {
                    $user->save();
                    return response()->json(['success' => true, 'response' => $user], 200);
                }catch (\Exception $exception)
                {
                    return response()->json(['success' => false, 'message' => $exception->getMessage()], 400);
                }
            }
            else
            {
                response()->json(['success' => false, 'message' => 'Erreur lors de l\'enregistrement de l\'image' ], 500);
            }
        }
        else
        {
            return response()->json(['success' => false, 'message' => 'U'], 401);
        }
    }

    public function uploadPhoto(Request $request)
    {
        $file = $request->file('photo');
        if ($file)
        {
            $valid_extensions = ['jpeg', 'png', 'jpg'];
            if (!$file->isValid() || !in_array($file->getClientOriginalExtension(), $valid_extensions)) {
                return response()->json(['success' => false, 'message' => 'Le fichier est invalide'], 400);
            }
            $filename = uniqid(). '.' .$file->getClientOriginalExtension();
            $file->move(public_path('images/profil/'), $filename);
            return $filename;
        }
        else
        {
            return response()->json(['success' => false, 'message' => 'Le fichier est vide'], 400);
        }
    }
}
