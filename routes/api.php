<?php

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

/*Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::get('/user', 'App\Http\Controllers\Api\User\AuthController@getMe')->middleware('auth:api');
Route::post('/login', 'App\Http\Controllers\Api\User\AuthController@login');
Route::post('/register', 'App\Http\Controllers\Api\User\AuthController@register');
Route::post('/forget-password', 'App\Http\Controllers\Api\User\ResetPasswordController@getEmail');
Route::post('/reset-password', 'App\Http\Controllers\Api\User\ResetPasswordController@validateKey');

Route::post('/users/{id}/paniers', 'App\Http\Controllers\Api\Panier\PanierController@addPanier');
Route::get('/users/{id}/paniers', 'App\Http\Controllers\Api\Panier\PanierController@userPaniers');
