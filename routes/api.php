<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/



//PRODUCTOS
Route::middleware('auth:sanctum')->get('/productos/{id?}', 'ApiAuth\ProductosController@index')->where("id","[0-9]+");
Route::middleware('auth:sanctum')->post('/productos', 'ApiAuth\ProductosController@save');
Route::middleware('auth:sanctum')->put('/productos/{id}', 'ApiAuth\ProductosController@edit')->where("id","[0-9]+");
Route::middleware('auth:sanctum')->delete('/productos/{id}', 'ApiAuth\ProductosController@delete')->where("id","[0-9]+");

//COMENTARIOS
Route::middleware('auth:sanctum')->get('/comentarios/{id?}', 'ApiAuth\ComentariosController@index')->where("id","[0-9]+");
Route::middleware('auth:sanctum')->get('/comentarios/usuario/{id}', 'ApiAuth\ComentariosController@persCom')->where("id","[0-9]+");
Route::middleware('auth:sanctum')->get('/comentarios/producto/{id}', 'ApiAuth\ComentariosController@prodCom')->where("id","[0-9]+");
Route::middleware('auth:sanctum')->post('/comentarios', 'ApiAuth\ComentariosController@save');
Route::middleware('auth:sanctum')->put('/comentarios/{id}', 'ApiAuth\ComentariosController@edit')->where("id","[0-9]+");
Route::middleware('auth:sanctum')->delete('/comentarios/{id}', 'ApiAuth\ComentariosController@delete')->where("id","[0-9]+");

//USER
Route::middleware('auth:sanctum')->get('/usuarios/{id?}', 'ApiAuth\AuthController@index')->where("id","[0-9]+");
Route::middleware('auth:sanctum')->put('/usuarios/{id}', 'ApiAuth\AuthController@edit')->where("id","[0-9]+");
Route::middleware('auth:sanctum')->delete('/usuarios/{id}', 'ApiAuth\AuthController@delete')->where("id","[0-9]+");

//SESION
Route::post('/registrate','ApiAuth\AuthController@registrate')->middleware('verificar.edad');
Route::post('/login','ApiAuth\AuthController@login');
Route::middleware('auth:sanctum')->delete('/logout', 'ApiAuth\AuthController@logOut');