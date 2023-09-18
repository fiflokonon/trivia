<?php

use App\Http\Controllers\Api\Panier\PanierController;
use App\Http\Controllers\Api\Panier\ParametreController;
use App\Http\Controllers\Api\Panier\PointLivraisonController;
use App\Http\Controllers\Api\User\AuthController;
use App\Http\Controllers\Api\User\DiscussionController;
use App\Http\Controllers\Api\User\EditUserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::get('/me', [AuthController::class, 'getMe'])->middleware('auth:sanctum');
Route::post('/login', 'App\Http\Controllers\Api\User\AuthController@login');
Route::post('/inscription', 'App\Http\Controllers\Api\User\AuthController@inscription');
Route::post('/forget-password', 'App\Http\Controllers\Api\User\ResetPasswordController@getEmail');
Route::post('/reset-password', 'App\Http\Controllers\Api\User\ResetPasswordController@validateKey');
Route::post('/validate-account', 'App\Http\Controllers\Api\User\AuthController@validateCode');
Route::get('/commercants', 'App\Http\Controllers\Api\Panier\CommercantController@listeCommercants');
Route::get('/parametres', 'App\Http\Controllers\Api\Panier\ParametreController@listeParametres');
Route::get('/slides', 'App\Http\Controllers\Api\Slide\SlideController@slides');
Route::get('/all-parametres', [ParametreController::class, 'allParametres']);

Route::post('/profil-photo', [AuthController::class, 'addProfilePhoto'])->middleware('auth:sanctum');
Route::post('/edit-profil', [EditUserController::class, 'editProfile'])->middleware('auth:sanctum');
Route::post('/commercants/{id}/paniers', [PanierController::class, 'addPanier'])->middleware('auth:sanctum');
Route::get('/paniers',[PanierController::class, 'userPaniers'])->middleware('auth:sanctum');
Route::get('/points', [PointLivraisonController::class, 'pointActifs'])->middleware('auth:sanctum');
Route::get('/messages', [DiscussionController::class, 'discussions'])->middleware('auth:sanctum');
Route::post('/messages', [DiscussionController::class, 'initDiscussion'])->middleware('auth:sanctum');


Route::get('/admin/paniers', [PanierController::class, 'getAllPaniers']);
Route::patch('/admin/paniers/{id}/valide', [PanierController::class, 'validerPanier']);
Route::get('/admin/messages', [DiscussionController::class, 'getAllDiscussions']);

