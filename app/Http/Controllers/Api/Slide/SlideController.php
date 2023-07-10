<?php

namespace App\Http\Controllers\Api\Slide;

use App\Http\Controllers\Controller;
use App\Models\Publicite;
use Illuminate\Http\Request;

class SlideController extends Controller
{
    public function slides()
    {
        $slides = Publicite::where('statut', true)->get();
        if ($slides->isNotEmpty())
        {
            return response()->json(['success' => true, 'reponse' => $slides]);
        }
        else
        {
            return response()->json(['success' => false, 'message' => 'Pas de publicité disponible'], 404);
        }
    }
}
