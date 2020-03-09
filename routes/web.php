<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('getTime', function(){
    $url_data = 'http://api.tiempo.com/index.php?api_lang=es&localidad=2158&affiliate_id=55ubkjpevt25&v=3.0';
    $json = file_get_contents($url_data);
    return $json;
 });

Route::get('getTime2', function(){
    $url_data =  'http://api.tiempo.com/index.php?api_lang=es&localidad=2158&affiliate_id=rlu8q653cqnf&v=2.0&h=1';
    $xml = simplexml_load_file($url_data);
    return str_replace('@','',json_encode($xml));
 });

Route::get('/', function () {
    if (Auth::check()){
        return array('data' => "SI", 'url' => '/');
    }else{
        return array('data' => "NO", 'url' => '/');
    }
    
    return '';
    //return view('layout');
});

Route::get('/layout', function(){
    if (Auth::check()){
        return array('data' => "SI", 'url' => '/layout');
    }else{
        return array('data' => "NO", 'url' => '/layout');
    }
		//return view('/');
 });

Route::get('/img/uploads/{filename}', function($filename){
    $path = resource_path() . '/img/' . $filename;

    if(!File::exists($path)) {
        return response()->json(['message' => 'Image not found.'], 404);
    }

    $file = File::get($path);
    $type = File::mimeType($path);

    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);

    return $response;
});

Route::get('/img/flags/{filename}', function($filename){
    $path = resource_path() . '/flags/' . $filename;

    if(!File::exists($path)) {
        return response()->json(['message' => 'Image not found.'], 404);
    }

    $file = File::get($path);
    $type = File::mimeType($path);

    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);

    return $response;
});

Route::post('register', 'Auth\RegisterController@store');
Route::resource('users', 'UsersController');

Route::resource('eventos', 'EventosController');
Route::put('eventos/edit/{id}', 'EventosController@update');
Route::get('eventos/by/{month}/{monthEnd}', 'EventosController@filter');

Route::resource('actividads', 'ActividadsController');
Route::put('actividads/edit/{id}', 'ActividadsController@update');
Route::get('actividads/by/{type}', 'ActividadsController@filter');
Route::get('actividads/by/{type}/to/{idUser}', 'ActividadsController@filterUser');
Route::put('actividads/{idActividad}/visited/by/{idUser}', 'ActividadsController@visited');

Route::resource('rutas', 'RutasController');
Route::put('rutas/edit/{id}', 'RutasController@update');

Route::resource('visitas', 'VisitasController');

Route::resource('rutasmisteriosas', 'RutaActividadController');
Route::get('rutasmisteriosas/by/{ruta}', 'RutaActividadController@filter');
Route::get('rutasmisteriosas/by/{idRuta}/actividades', 'RutaActividadController@actividades');
Route::get('rutasmisteriosas/{idRuta}/by/{idUser}', 'RutaActividadController@visited');

Route::get('login', 'Auth\LoginController@showLoginForm');
Route::post('login', 'Auth\LoginController@login');
Route::get('logout', 'Auth\LoginController@logout');
