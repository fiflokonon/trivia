<?php

namespace App\Http\Controllers\Api\Panier;

use App\Http\Controllers\Controller;
use App\Models\Panier;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\File;

class PanierController extends Controller
{

    public function addPanier(Request $request, $user_id)
    {
        $validator = Validator::make($request->all(), [
            'produits' => 'required|array',
            'produits.*.nom_produit' => 'required|string',
            'produits.*.prix' => 'required|numeric',
            'produits.*.quantite' => 'required|integer',
            'produits.*.prix_promo' => 'nullable|numeric',
            'produits.*.lien_produit' => 'nullable|string',
            'produits.*.lien_image' => 'required|string',
            'produits.*.couleur' => 'nullable|string',
            'produits.*.taille' => 'nullable|string',
            'produits.*.details_produit' => 'nullable|string'
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
        $user = User::find($user_id);
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Utilisateur non trouvé.'], 404);
        }
        $numero_commande =  $this->generateReference();
        $panier = new Panier();
        $panier->produits = json_encode($request->input('produits'));
        $panier->sous_total = $this->calculateTotal($request->input('produits'));
        $panier->user_id = $user_id;
        $panier->numero_panier = $numero_commande;
        $panier->lien_qr_code = 'qr/'.$this->generateQrCode($numero_commande);
        $panier->statut = false;
        $panier->save();
        $panier->produits = json_decode($panier->produits);
        return response()->json(['success' => true, 'response' => $panier], 201);
    }

    private function calculateTotal($produits)
    {
        $total = 0;
        foreach ($produits as $produit) {
            $total += $produit['prix'] * $produit['quantite'];
        }

        return $total;
    }

    public function userPaniers(int $user_id)
    {
        $user = User::find($user_id);
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Utilisateur non trouvé.'], 404);
        }
        else
        {
            if ($user->paniers->isNotEmpty())
            {
                foreach ($user->paniers as $panier)
                {
                    $panier->produits = json_decode($panier->produits);
                }
                return response()->json(['success' => true, 'response' => $user->paniers], 200);
            }
            else
            {
                return response()->json(['success' => false, 'message' => 'Pas de panier disponible'], 404);
            }
        }
    }

    public function generateReference()
    {
        $id_slug = $this->generateUniqueRef(7);
        while ($this->checkRefExist($id_slug))
        {
            $id_slug = $this->generateUniqueRef(7);
        }
        return $id_slug;
    }

    public function checkRefExist($ref)
    {
        $panier = Panier::where('numero_panier', $ref)->first();
        if ($panier && $panier->numero_panier)
            return true;
        else
            return false;
    }

    public function generateQrCode($content)
    {
        $qrCode = QrCode::format('png')->size(200)->generate($content);

        $filename = uniqid() . '.png';
        $filePath = public_path('qr/' . $filename);
        File::put($filePath, $qrCode);
        return $filename;
    }
    /*public function generateQrCode($content)
    {
        $qrCode = QrCode::format('png')->size(200)->generate($content);

        $filePath = public_path('qr/' . uniqid() . '.png');
        File::put($filePath, $qrCode);
        return $filePath;

    }*/
}
