<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\Discussion;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use PharIo\Version\Exception;

class DiscussionController extends Controller
{
    public function initDiscussion(Request $request)
    {
        if ($request->user())
        {
            $user = $request->user();
            $validator = Validator::make($request->all(), [
                'sujet' => 'required|string|max:255',
                'message' => 'required|string|max:255'
            ]);
            if ($validator->fails()) {
                return response()->json(['success' => false, 'message' => $validator->errors()], 400);
            }else{
                try {
                    $discussion = Discussion::create([
                        'sujet' => $request->sujet,
                        'statut' => true,
                        'client_id' => $user->id
                    ]);
                    Message::create([
                        'sender_id' => $user->id,
                        'discussion_id' => $discussion->id,
                        'message' => $request->message,
                        'vu_client' => true,
                        'statut' => true
                    ]);
                    return response()->json(['success' => true, 'message' => 'Message envoyé avec succès']);
                }catch (Exception $exception){
                    return response()->json(['success' => false, 'message' => $exception->getMessage()],400);
                }
            }
        }else{
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }
    }

    public function answerMessage(int $id, Request $request)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Utilisateur non trouvé! Veuillez entrer le token'], 401);
        }elseif (!$user->admin)
        {
            return response(['success' => false, 'message' => 'Forbidden'], 403);
        }
        else{
            $validator = Validator::make($request->all(), [
                'message' => 'required|string'
            ]);
            if ($validator->fails()){
                return response()->json(['success' => false, 'message' => $validator->errors()], 400);
            }
            $discussion = Discussion::find($id);
            if ($discussion)
            {
                try {
                    Message::create([
                        'discussion_id' => $discussion->id,
                        'sender_id' => $user->id,
                        'message' => $request->message,
                        'statut' => true,
                        'vu_admin' => true
                    ]);
                    return response()->json(['success' => true, 'message' => 'Réponse envoyée avec succès']);
                }catch (Exception $exception){
                    return response()->json(['success' => false, 'message' => $exception->getMessage()], 400);
                }
            }else{
                return response()->json(['success' => false, 'message' => 'Discussion non trouvée'], 404);
            }
        }
    }

    public function getAllDiscussions()
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Utilisateur non trouvé! Veuillez entrer le token'], 401);
        }elseif (!$user->admin)
        {
            return response(['success' => false, 'message' => 'Forbidden'], 403);
        }else{
            $discussions = Discussion::with('last_message.sender')->where('statut', true)->paginate(10);
            if ($discussions->isNotEmpty()){
                return response()->json(['success' => true, 'response' => $discussions]);
            }
            else{
                return response()->json(['success' => false, 'message' => 'Pas de message'], 404);
            }
        }
    }

    public function discussions()
    {
        $user = auth()->user();
        if (!$user){
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }else{
            $discussions = Discussion::with('last_message.sender')->where('statut', true)->where('client_id', $user->id)->paginate(10);
            if ($discussions->isNotEmpty())
                return response()->json(['success' => true, 'response' => $discussions]);
            else
                return response()->json(['success' => false, 'message' => 'Pas de message'], 404);
        }
    }

    /*public function messages(int $id)
    {
        $discussion = Discussion::with('messages.sender')->where('id', $id)->get();
        if ($discussion){
            $discussion->messages = $discussion->messages()->get();
            return response()->json(['success' => true, 'response' => $discussion]);
        }
        else{
            return response()->json(['success' => false, 'message' => 'Pas de message dans cette discussion'], 404);
        }
    }*/
    public function messages(int $id)
    {
        $discussion = Discussion::with(['messages.sender'])->find($id);

        if ($discussion) {
            return response()->json(['success' => true, 'response' => $discussion]);
        } else {
            return response()->json(['success' => false, 'message' => 'Pas de discussion trouvée'], 404);
        }
    }


    public function adminLu(int $id)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Utilisateur non trouvé! Veuillez entrer le token'], 401);
        }elseif (!$user->admin)
        {
            return response(['success' => false, 'message' => 'Forbidden'], 403);
        }else{
            $message = Message::find($id);
            if (!$message->sender->admin) {
                $message->vu_admin = true;
                $message->save();
                return response()->json(['success' => true, 'message' => 'Message lu']);
            } else {
                return response()->json(['success' => false, 'message' => 'Forbidden'], 403);
            }
        }
    }

    public function clientLu(int $id)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Utilisateur non trouvé! Veuillez entrer le token'], 401);
        }
        else
        {
            $message = Message::find($id);
            if ($message){
                $message->vu_client = true;
                $message->save();
                return response()->json(['success' => true, 'message' => 'Message lu']);
            }else{
             return response()->json(['success' => false, 'message' => 'Message non trouvé'], 404);
            }
        }
    }
}
