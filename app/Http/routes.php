<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('layout');
});

Route::resource('users', 'UsersController');
Route::resource('eventos', 'EventosController');
Route::resource('actividads', 'ActividadsController');
Route::get('actividads/by/{type}','ActividadsController@filter');
Route::resource('rutas', 'RutasController');
Route::resource('visitas', 'VisitasController');
Route::resource('destinos', 'DestinosController');