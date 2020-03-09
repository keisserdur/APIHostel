<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\RutaActividadController;

class RutasController extends Controller
{
    public function __construct() {
       //$this->middleware('authDomain')->only(['store', 'update', 'destroy']);
    }
    
    public function index(){
        $rutas = \App\Route::all();
        $rutasDetails = \App\RouteDetail::all();

        $return = array();

        foreach($rutas as $ruta){
            $language = array();
            $array = $this->getArrayRoute($ruta);
            foreach($rutasDetails as $detail){
                if($detail->route_id == $ruta->id)
                    array_push($language, [$detail->language => $this->getArrayDetail($detail) ] );
            }            
            $array['lan'] = $language;
            $return[$ruta->id] = $array;
        }
        return $return;
    }

    public function getArrayRoute($object){
        $array = array();
        if(!empty($object->id))
            $array['id'] = $object->id;
        if(!empty($object->distance))
            $array['distance'] = $object->distance;
        if(!empty($object->duration))
            $array['duration'] = $object->duration;
        if(!empty($object->created_at))
            $array['created_at'] = $object->created_at;
        
        return $array;
    }

    public function getArrayDetail($object){
        $array = array();
        if(!empty($object->name))
            $array['name'] = $object->name;
        if(!empty($object->description))
            $array['description'] = $object->description;
        
        return $array;
    }

    public function create(){
        //
    }

    public function store(Request $request){
        $array = $this->getRuteArray($request);

        if(!empty($array)){
            $id = \App\Route::insertGetId( $array );
            foreach($request->lang as $key => $value){
                $detail = array();
                $detail['route_id'] = $id;
                $detail['language'] = $key;
                $detail['name'] = $value['name'];
                $detail['description'] = $value['description'];
                $insert = \App\RouteDetail::insertGetId( $detail );
            }
            if(!empty($request->actividades['id']))
                RutaActividadController::actualizarRuta($request->actividades['id'], $request->orden, $id);
        }
        
        if($request->redirect == 'Not redirect')
            return $this->show($evento);
        return Redirect::to($request->redirect);
    }

    public function show($id){
        $ruta = \App\Route::find($id);
        
        $rutasDetails = \App\RouteDetail::all();

        $return = array();

        $language = array();
        $array = $this->getArrayRoute($ruta);
        foreach($rutasDetails as $detail){
            if($detail->route_id == $ruta->id)
                array_push($language, [$detail->language => $this->getArrayDetail($detail) ] );
        }            
        $array['lan'] = $language;
        $return[$ruta->id] = $array;
        
        return $return;
    }

    public function edit($id){
        //
    }

    public function update(Request $request, $id){

        $cambios = $this->getRuteArray($request);

        if(!empty($cambios)){
            $ruta = \App\Route::find($id)->update( $cambios );
            foreach($request->lang as $key => $value){
                $detail = array();
                $detail['route_id'] = $id;
                $detail['language'] = $key;
                $detail['name'] = $value['name'];
                if(!empty($value['description']))
                    $detail['description'] = $value['description'];

                $insert = \App\RouteDetail::where('route_id', $id)->where('language', $key)->update( $detail );
                if($insert == 0)
                    $insert = \App\RouteDetail::insertGetId( $detail );
                
            }
            if(!empty($request->actividades['id']))
                RutaActividadController::actualizarRuta($request->actividades['id'], $request->orden, $id);
        }
        
        if($request->redirect == 'Not redirect')
            return $this->show($id);
        return Redirect::to($request->redirect);
    }
    
    /**
     * Devuelve un array de una actividad obteniendo los datos de $request
     * 
    */
    public function getRuteArray($request){
        $array = array();
        
        if(!empty($request->distance))
            $array['distance'] = $request->distance;
        if(!empty($request->duration))
            $array['duration'] = $request->duration;
        
        return $array;
    }

    public function destroy(Request $request, $id){
        \App\Route::find($id)->delete();
        
        return Redirect::to($request->redirect);
    }
}
