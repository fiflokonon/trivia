<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function notifications()
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Utilisateur non trouvé! Veuillez entrer le token'], 401);
        } else {
            if ($user->notifications->isNotEmpty())
            {
                $perPage = 10; // Nombre d'éléments par page
                $page = request('page', 1); // Numéro de page (par défaut 1)
                $notifications = $user->notifications()->paginate($perPage, ['*'], 'page', $page);
                return response()->json(['success' => true, 'response' => $notifications], 200);
            }else {
                return response()->json(['success' => false, 'message' => 'Pas de notification disponible'], 404);
            }
        }
    }

    /*public function viewedNotifs()
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Utilisateur non trouvé! Veuillez entrer le token'], 401);
        } else {
            $user->viewed_notifications();
            $user->save();
            $perPage = 10; // Nombre d'éléments par page
            $page = request('page', 1); // Numéro de page (par défaut 1)
            $notifications = $user->notifications()->paginate($perPage, ['*'], 'page', $page);
            return response()->json(['success' => true, 'response' => $notifications , 'message' => 'Notifications vues']);
        }
    }*/

    public function viewedNotifs()
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Utilisateur non trouvé! Veuillez entrer le token'], 401);
        } else {
            $notifs = $user->notifications;
            foreach ($notifs as $notif) {
                $notif->viewed();
            }
            $perPage = 10; // Nombre d'éléments par page
            $page = request('page', 1); // Numéro de page (par défaut 1)
            $notifications = $user->notifications()->paginate($perPage, ['*'], 'page', $page);
            return response()->json(['success' => true, 'response' => $notifications , 'message' => 'Notifications vues']);
        }
    }
}
