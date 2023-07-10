<?php

use App\Http\Controllers\Api\User\AuthController;
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

Route::post('/profil-photo', 'App\Http\Controllers\Api\User\AuthController@addProfilePhoto')->middleware('auth:sanctum');
Route::post('/paniers', 'App\Http\Controllers\Api\Panier\PanierController@addPanier')->middleware('auth:sanctum');
Route::get('/paniers','App\Http\Controllers\Api\Panier\PanierController@userPaniers')->middleware('auth:sanctum');
