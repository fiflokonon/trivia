<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function generateUniqueRef($length)
    {
        $bytes = random_bytes($length);
        return strtoupper(bin2hex($bytes));
    }

    public function sendPushNotificationToTopic($topic, $title, $body, $data = [])
    {
        $serverKey = env('FIREBASE_TOKEN'); // Votre clé de serveur Firebase
        $headers = [
            'Authorization: key=' . $serverKey,
            'Content-Type: application/json',
        ];

        $data = [
            'to' => '/topics/' . $topic,
            'notification' => [
                'title' => $title,
                'body' => $body,
            ],
            'data' => $data,
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        $result = curl_exec($ch);
        curl_close($ch);

        // Vérifier la réponse du serveur FCM
        if ($result === false) {
            return response()->json(['success' => false, 'message' => 'Erreur lors de l\'envoi de la notification push.']);
        }

        $response = json_decode($result);
        if ($response && $response->success) {
            return response()->json(['success' => true, 'message' => 'Notification push envoyée avec succès.']);
        } else {
            return response()->json(['success' => false, 'message' => 'Échec de l\'envoi de la notification push.']);
        }
    }



}
