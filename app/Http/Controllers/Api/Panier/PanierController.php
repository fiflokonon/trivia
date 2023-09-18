<?php

namespace App\Http\Controllers\Api\Panier;

use App\Http\Controllers\Controller;
use App\Mail\CommandeMail;
use App\Models\Commercant;
use App\Models\Panier;
use App\Models\PointLivraison;
use BaconQrCode\Renderer\Image\ImagickImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class PanierController extends Controller
{
    public function addPanier(string $id, Request $request)
    {
        $commercant = Commercant::find($id);
        if ($commercant && $commercant->statut)
        {
            $validator = Validator::make($request->all(), [
                'id_transaction' => 'nullable|numeric',
                'frais_fournisseur' => 'required|numeric',
                'frais_livraison' => 'required|numeric',
                'pays_livraison' => 'required|string',
                'point_livraison_id' => 'required|numeric',
                'nom' => 'required|string',
                'indicatif' => 'required|string',
                'contact' => 'required|string',
                'email' => 'required|string',
                'type_recepteur' => 'required|string',
                'nom_prenom_recepteur' => 'nullable|string',
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
            $user = $request->user();
            if (!$user) {
                return response()->json(['success' => false, 'message' => 'Utilisateur non trouvé.'], 404);
            }
            else
            {
                $point = PointLivraison::where('id', $request->point_livraison_id)->where('statut', true)->first();
                if ($point)
                {
                    $numero_commande =  $this->generateReference();
                    try {
                        $panier = new Panier();
                        $panier->produits = json_encode($request->input('produits'));
                        $panier->sous_total = $this->calculateTotal($request->input('produits'));
                        $panier->user_id = $user->id;
                        $panier->commercant_id = $commercant->id;
                        $panier->numero_panier = $numero_commande;
                        $panier->id_transaction = $request->id_transaction;
                        $panier->frais_fournisseur = $request->frais_fournisseur;
                        $panier->frais_livraison = $request->frais_livraison;
                        $panier->pays_livraison = $request->pays_livraison;
                        $panier->point_livraison_id = $request->point_livraison_id;
                        $panier->nom = $request->nom;
                        $panier->email = $request->email;
                        $panier->indicatif = $request->indicatif;
                        $panier->contact = $request->contact;
                        $panier->type_recepteur = $request->type_recepteur;
                        $panier->nom_prenom_recepteur = $request->nom_prenom_recepteur;
                        $panier->statut = true;
                        $panier->statut_livraison = 'en cours';
                        $panier->created_at = now();
                        $pdfData = [
                            'numero_panier' => $panier->numero_panier,
                            'produits' => $panier->produits,
                            'sous_total' => $panier->sous_total,
                            'nom' => $panier->nom,
                            'email' => $panier->email,
                            'contact' => $panier->indicatif . $panier->contact,
                            'frais_fournisseur' => $panier->frais_fournisseur,
                            'frais_trivia' => $panier->frais_livraison
                        ];
                        // Remplacez le chemin ci-dessous par le modèle PDF que vous avez créé ou utilisé pour la facture
                        $pdf = PDF::loadView('factures.facture', $pdfData);
                        // Choisissez l'emplacement où vous souhaitez enregistrer le fichier PDF généré
                        $pdfFilePath = public_path('factures/'. $panier->numero_panier . '.pdf');
                        $pdf->save($pdfFilePath);
                        $panier->lien_facture = 'factures/'. $panier->numero_panier . '.pdf';
                        $panier->save();
                        $this->sendCommandConfirmationEmail($user->email, $numero_commande, $pdfFilePath);
                        $panier->produits = json_decode($panier->produits);
                        return response()->json(['success' => true, 'response' => $panier], 201);
                    }catch (\Exception $exception)
                    {
                        return response()->json(['success' => false, 'message' => $exception->getMessage()], 400);
                    }

                }else
                {
                    return response()->json(['success' => false, 'message' => 'Point de livraison incatif']);
                }
            }
        }
        else
        {
            return response()->json(['success' => false, 'message' => 'Commerçant inactif dans la base'], 404);
        }

    }
    private function calculateTotal($produits)
    {
        $total = 0;
        foreach ($produits as $produit) {
            $total += $produit['prix'] * $produit['quantite'];
        }
        return $total;
    }

    public function userPaniers()
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Utilisateur non trouvé! Veuillez entrer le token'], 401);
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

    public function sendCommandConfirmationEmail($email, $code, $facture)
    {
        try {
            Mail::to($email)->send(new CommandeMail($code, $facture));
            return true; // Email sent successfully
        } catch (\Throwable $e) {
            return false; // Error occurred during email sending
        }
    }

    public function getAllPaniers()
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Utilisateur non trouvé! Veuillez entrer le token'], 401);
        }elseif (!$user->admin)
        {
            return response(['success' => false, 'message' => 'Forbidden'], 403);
        }
        else{
            $paniers = Panier::where('statut', true)->orderBy('DESC', 'created_at')->paginate(10);
            if ($paniers)
            {
                foreach ($paniers as $panier)
                {
                    $panier->produits = json_decode($paniers->produits);
                }
                return response()->json(['success' => true, 'response' => $paniers]);
            }else{
                return response()->json(['success' => false, 'message' => 'Aucune commande disponible'], 404);
            }
        }
    }

    public function getPaniersEnCours()
    {
        $paniers = Panier::where('statut', true)->where('statut_livraison', 'en cours')->orderBy('created_at', 'DESC')->paginate(10);
        if ($paniers)
        {
            foreach ($paniers as $panier)
            {
                $panier->produits = json_decode($paniers->produits);
            }
            return response()->json(['success' => true, 'response' => $paniers]);
        }else{
            return response()->json(['success' => false, 'message' => 'Aucune commande disponible'], 404);
        }
    }

    public function validerPanier(int $id)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Utilisateur non trouvé! Veuillez entrer le token'], 401);
        }elseif (!$user->admin)
        {
            return response(['success' => false, 'message' => 'Forbidden'], 403);
        }
        else {
            $panier = Panier::find($id);
            if ($panier) {
                $panier->statut_livraison = 'validé';
                $panier->save();
                return response()->json(['success' => true, 'message' => 'Panier validé']);
            } else {
                return response()->json(['success' => false, 'message' => 'Panier indisponible'], 404);
            }
        }
    }
}
