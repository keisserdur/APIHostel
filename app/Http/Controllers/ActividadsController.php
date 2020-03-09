<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;

class ActividadsController extends Controller
{
    public function __construct() {
       //$this->middleware('authDomain')->only(['store', 'update', 'destroy']);
    }

    public function index(){
        $activities = \App\Activity::all();
        $activitiesDetails = \App\ActivityDetail::all();
        $typesDetails = \App\TypeDetail::all();

        $return = array();

        foreach($activities as $activity){
            $language = array();
            $type = array();
            $array = $this->getArrayActivity($activity);
            foreach($activitiesDetails as $detail){
                if($detail->activity_id == $activity->id)
                    array_push($language, [$detail->language => $this->getArrayDetail($detail) ] );
            }
            
            foreach($typesDetails as $detail){
                if($detail->type_id == $activity->type_id)
                    array_push($type, [$detail->language => $this->getArrayDetail($detail) ] );
            }
            
            $array['lan'] = $language;
            $array['type'] = $type;
            $return[$activity->id] = $array;
        }
        return $return;
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
    
    /**
     * Devuelve la lista de actividades filtradas por tipo
     */
    public function filter($type){
        $id_type = \App\TypeDetail::where('name', $type)->get();
        $id_type = $id_type[0]->type_id;
        $actividads = \App\Activity::where('type_id', $id_type)->get();
        $activitiesDetails = \App\ActivityDetail::all();
        $typesDetails = \App\TypeDetail::where('type_id', $id_type)->get();

        $return = array();

        foreach($actividads as $activity){
            $language = array();
            $type = array();
            $array = $this->getArrayActivity($activity);
            foreach($activitiesDetails as $detail){
                if($detail->activity_id == $activity->id)
                    array_push($language, [$detail->language => $this->getArrayDetail($detail) ] );
            }
            
            foreach($typesDetails as $detail){
                if($detail->type_id == $activity->type_id)
                    array_push($type, [$detail->language => $this->getArrayDetail($detail) ] );
            }
            
            $array['lan'] = $language;
            $array['type'] = $type;
            $return[$activity->id] = $array;
        }
        return $return;
    }
        
    /**
     * Devuelve la lista de actividades visitadas de un tipo en concreto y de un usuario en concreto
    */
    public function filterUser($type, $idUser){
        $activities = \App\Activity::where('type_id', $type)->get();
        $vistas = \App\Visitas::where('user_id', $idUser)->get();
        $activitiesDetails = \App\ActivityDetail::all();
        $typesDetails = \App\TypeDetail::all();

        $return = array();

        foreach($activities as $activity){
            $language = array();
            $type = array();
            $array = $this->getArrayActivity($activity);

            $array['visited'] = false;
            foreach($vistas as $vista){                
                if($vista->activity_id == $array['id'] && $vista->visited == 1)
                    $array['visited'] = true;
            }

            foreach($activitiesDetails as $detail){
                if($detail->activity_id == $activity->id)
                    array_push($language, [$detail->language => $this->getArrayDetail($detail) ] );
            }
            
            foreach($typesDetails as $detail){
                if($detail->type_id == $activity->type_id)
                    array_push($type, [$detail->language => $this->getArrayDetail($detail) ] );
            }
            
            $array['lan'] = $language;
            $array['type'] = $type;
            $return[$activity->id] = $array;
        }
        return $return;

        
        return $actividads;
    }
    
    /**
     * A partir de un id de actividad y un id de usuario modifica la BD para poner dicha actividad como visitada
     */
    public function visited($idActividad, $idUser){

        $visita = \App\Visitas::where('user_id', $idUser)->where('activity_id', $idActividad)->get();

        if(count($visita)==0){
            $campos = array();
            $campos['user_id'] = $idUser;
            $campos['activity_id'] = $idActividad;
            $campos['visited'] = 1;
            
            $visita = \App\Visitas::create($campos);
            return $visita;
        }

        $visita = $visita[0];
        if($visita->visited == 1){
            $visita->visited = 0;
            $visita = \App\Visitas::find($visita->id)->update( $this->getArray($visita) );
        }else if($visita->visited == 0){
            $visita->visited = 1;
            $visita = \App\Visitas::find($visita->id)->update( $this->getArray($visita) );
        }
        return $visita = \App\Visitas::where('user_id', $idUser)->where('activity_id', $idActividad)->get()[0];
    }
    
    /**
     * Devuelce un array a partir de una visita
     */
    private function getArray($object){
        $array = array();
        $array['user_id'] = $object->user_id;
        $array['activity_id'] = $object->activity_id;
        $array['visited'] = $object->visited;

        return $array;
    }

    public function create(){
    }

    public function store(Request $request){
        
        $array = $this->getActivityArrayToStore($request);
        
        if(!empty($array)){
            $idActividad = \App\Activity::insertGetId( $array );
            foreach($request->lang as $key => $value){
                $detail = array();
                $detail['activity_id'] = $idActividad;
                $detail['language'] = $key;
                $detail['name'] = $value['name'];
                $detail['description'] = $value['description'];
                $detail['tag'] = $value['tag'];
                $insert = \App\ActivityDetail::insertGetId( $detail );
            }
        }

        if($request->redirect == 'Not redirect')
            return $this->show($idActividad);
        return Redirect::to($request->redirect);
    }

    public function show($id){
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
        
        $array['lan'] = $language;
        $array['type'] = $type;   
             
        return $array;
    }

    public function edit($id){
        //
    }

    public function update(Request $request, $id){
        $cambios = $this->getActivityArrayToStore($request);

        if(!empty($cambios)){
            $actividad = \App\Activity::where('id', $id)->update( $cambios );

            foreach($request->lang as $key => $value){
                $detail = array();
                $detail['activity_id'] = $id;
                $detail['language'] = $key;
                $detail['name'] = $value['name'];
                $detail['description'] = $value['description'];
                $detail['tag'] = $value['tag'];
                
                $insert = \App\ActivityDetail::where('activity_id', $id)->where('language', $key)->update( $detail );
                if($insert == 0)
                    $insert = \App\ActivityDetail::insertGetId( $detail );
            }
        }
                
        if($request->redirect == 'Not redirect')
            return $this->show($id);;
        return Redirect::to($request->redirect);
    }
    
    /**
     * Devuelve un array de una actividad obteniendo los datos de $request
     * 
    */
    public function getActivityArrayToStore($request){
        $array = array();
        $array['metro'] = 0;
        
        if(!empty($request->type)){
            $type = \App\TypeDetail::where('name', $request->type)->get();
            $array['type_id'] = $type[0]->type_id;
        }
        if(!empty($request->address))
            $array['address'] = $request->address;
        if(!empty($request->cp))
            $array['cp'] = $request->cp;
        if(!empty($request->transport))
            $array['transport'] = $request->transport;
        if(!empty($request->metro))
            $array['metro'] = $request->metro;
        if(!empty($request->created_at))
            $array['created_at'] = $request->created_at;
        if(!empty($request->image))
            $array['image'] = $this->saveImg($request->name);
        
        return $array;
    }
    
    /**
     * Funcion que guarda una imagen en el servidor, y devuelve la url para acceder a la img
     */
    function saveImg($name){
        
        $name = strtolower(str_replace (' ' , '-'  , $name ));
        $target_dir = "resources/img/";
        
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    
        $target_file = $target_dir . $name . '.' . $imageFileType;
        $name = $name . '.' . $imageFileType;
        $uploadOk = 1;
        
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if($check !== false) {
            echo "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
        } else {
            return '';
            $uploadOk = 0;
        }
        
        // Check if file already exists
        if (file_exists($target_file)) {
            $i=0;
            $uploadOk = 1;
            do {
                $target_file = str_replace ('_' . ($i-1) . '.' . $imageFileType, '.'.$imageFileType  , $target_file );
                $name = str_replace ( '_' . ($i-1) . '.' . $imageFileType, '.'.$imageFileType  , $name );
                
                $target_file = str_replace ('.' . $imageFileType, '_' . $i . '.'.$imageFileType  , $target_file );
                $name = str_replace ('.' . $imageFileType, '_' . $i . '.'.$imageFileType  , $name );
                $i++;
                if($i > 999){
                    $uploadOk = 0;
                    return '';
                    break;
                }
            } while (file_exists($target_file));
            
        }
        // Check file size
        if ($_FILES["image"]["size"] > 500000) {
            return '';
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }
        // Allow certain file formats
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif" ) {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            return '';
            $uploadOk = 0;
        }
        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
            // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                return 'https://hostel-granada.es/app-hostel/img/uploads/'.$name;
                echo "The file ". basename( $_FILES["image"]["name"]). " has been uploaded.";
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
        return '';
    }

    public function destroy(Request $request, $id){
        \App\Activity::find($id)->delete();
        
        return Redirect::to($request->redirect);
    }
}
