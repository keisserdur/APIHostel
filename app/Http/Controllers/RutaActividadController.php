<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\ActividadsController;

class RutaActividadController extends Controller
{
    public function index(){
        $destinos = \App\RutaActividad::orderBy('rute_id', 'asc')->orderBy('orden', 'asc')->get();
        return $destinos;
    }
    
    public function filter($ruta){
        $destino = \App\RutaActividad::where("rute_id", $ruta)->orderBy('orden', 'asc')->get();
        return $destino;
    }
    
    public function actividades($idRuta){
        $lista = $this->filter($idRuta);
        
        $ids = array();
        foreach($lista as $key => $value){            
            $destinos[$key] = $this->getSingleActivity($value->activity_id, $value->orden);
        }
        return $destinos;
    }

    public function getSingleActivity($id, $orden){
        $activity = \App\Activity::find($id);

        $eventosDetails = \App\ActivityDetail::where('activity_id', $id)->get();
        $typesDetails = \App\TypeDetail::all();

        $language = array();
        $type = array();
        $array = $this->getArrayActivity($activity);

        foreach($eventosDetails as $detail){
            if($detail->activity_id == $activity->id)
                array_push($language, [$detail->language => $this->getArrayDetail($detail) ] );
        }    
        foreach($typesDetails as $detail){
            if($detail->type_id == $activity->type_id)
                array_push($type, [$detail->language => $this->getArrayDetail($detail) ] );
        }
        
        $array['orden'] = $orden;
        $array['lan'] = $language;
        $array['type'] = $type;
        $array['visited'] = false;
             
        return $array;
    }

    public function getArrayActivity($object){
        $array = array();
        if(!empty($object->id))
            $array['id'] = $object->id;
        if(!empty($object->address))
            $array['address'] = $object->address;
        if(!empty($object->cp))
            $array['cp'] = $object->cp;
        if(!empty($object->metro))
            $array['metro'] = $object->metro;
        if(!empty($object->transport))
            $array['transport'] = $object->transport;
        if(!empty($object->type_id))
            $array['type_id'] = $object->type_id;
        if(!empty($object->created_at))
            $array['created_at'] = $object->created_at;
        if(!empty($object->img))
            $array['img'] = $object->img;
        
        return $array;
    }

    public function getArrayDetail($object){
        $array = array();
        if(!empty($object->name))
            $array['name'] = $object->name;
        if(!empty($object->description))
            $array['description'] = $object->description;
        if(!empty($object->tag))
            $array['tag'] = $object->tag;
        
        return $array;
    }
    
    public function visited($idRuta, $idUser){
        $actividads =  $this->actividades($idRuta);
        
        $vistas = \App\Visitas::where('user_id', $idUser)->get();
        
        $return = array();
        foreach ($actividads as $actividad){
            $actividad['visited'] = false;
            foreach($vistas as $vista){
                if($vista->activity_id == $actividad['id'] && $vista->visited == 1)
                    $actividad['visited'] = true;
            }
            array_push($return, $actividad);
        }
        
        return $return;
    }
    
    public function create(){
        //
    }

    public function store(Request $request){
        //
    }

    public function show($id){
        //
    }

    public function edit($id){
        //
    }

    public function update(Request $request, $id){
        return '';
    }
    
    public static function actualizarRuta($request, $orden, $id){
        $nuevosDestinos = array();
        
        foreach($request as $idActividad){
            $destino = array();
            $destino['rute_id'] = $id;
            $destino['activity_id'] = $idActividad;
            $destino['orden'] = $orden[$idActividad][0];
            array_push($nuevosDestinos, $destino);
        }
        
        \App\RutaActividad::where('rute_id', $id)->delete();
        \App\RutaActividad::insert( $nuevosDestinos );
        
        return '';
    }


    public function destroy($id){
        //
    }
}
