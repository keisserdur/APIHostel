<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class EventosController extends Controller
{
    public function __construct() {
       //$this->middleware('authDomain')->only(['store', 'update', 'destroy']);
    }
    
    public function index(){
        $eventos = \App\Event::orderBy('start_date')->get();
        $eventosDetails = \App\EventDetail::all();

        $return = array();

        foreach($eventos as $evento){
            $language = array();
            $array = $this->getArrayEvent($evento);
            foreach($eventosDetails as $detail){
                if($detail->event_id == $evento->id)
                    array_push($language, [$detail->language => $this->getArrayDetail($detail) ] );
            }            
            $array['lan'] = $language;
            $return[$evento->id] = $array;
        }
        return $return;
    }

    public function getArrayEvent($object){
        $array = array();
        if(!empty($object->id))
            $array['id'] = $object->id;
        if(!empty($object->price))
            $array['price'] = $object->price;
        if(!empty($object->start_date))
            $array['start_date'] = $object->start_date;
        if(!empty($object->end_date))
            $array['end_date'] = $object->end_date;
        if(!empty($object->start_hour))
            $array['start_hour'] = $object->start_hour;
        if(!empty($object->end_hour))
            $array['end_hour'] = $object->end_hour;
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
        
        return $array;
    }
    
    public function filter($month, $monthEnd){
        $eventos = \App\Event::where('start_date', '>=', $month)->where('end_date', '<=', $monthEnd)->orderBy('start_date')->get();
        
        $eventosDetails = \App\EventDetail::all();

        $return = array();

        foreach($eventos as $evento){
            $language = array();
            $array = $this->getArrayEvent($evento);
            foreach($eventosDetails as $detail){
                if($detail->event_id == $evento->id)
                    array_push($language, [$detail->language => $this->getArrayDetail($detail) ] );
            }            
            $array['lan'] = $language;
            $return[$evento->id] = $array;
        }
        return $return;
    }

    public function create(){
    }

    public function store(Request $request){
        $array = $this->getEventoArrayToStore($request);

        if(!empty($array)){
            $evento = \App\Event::insertGetId( $array );
            foreach($request->lang as $key => $value){
                $detail = array();
                $detail['event_id'] = $evento;
                $detail['language'] = $key;
                $detail['name'] = $value['name'];
                $detail['description'] = $value['description'];
                $insert = \App\EventDetail::insertGetId( $detail );
            }
        }
        
        if($request->redirect == 'Not redirect')
            return $this->show($evento);
        return Redirect::to($request->redirect);
    }

    public function show($id){
        $evento = \App\Event::find($id);

        $eventosDetails = \App\EventDetail::where('event_id', $id)->get();

        $language = array();
        $array = $this->getArrayEvent($evento);

        foreach($eventosDetails as $detail){
            if($detail->event_id == $evento->id)
                array_push($language, [$detail->language => $this->getArrayDetail($detail) ] );
        }       
             
        $array['lan'] = $language;
        return $array;
    }

    public function edit($id){
        //
    }

    public function update(Request $request, $id){
        $cambios = $this->getEventoArrayToStore($request);

        if(!empty($cambios)){
            $actividad = \App\Event::where('id', $id)->update( $cambios );
            
            foreach($request->lang as $key => $value){
                $detail = array();
                $detail['event_id'] = $id;
                $detail['language'] = $key;
                $detail['name'] = $value['name'];
                $detail['description'] = $value['description'];
                
                $insert = \App\EventDetail::where('event_id', $id)->where('language', $key)->update( $detail );
                if($insert == 0)
                    $insert = \App\EventDetail::insertGetId( $detail );
            }
        }
        
        if($request->redirect == 'Not redirect')
            return $this->show($id);
        return Redirect::to($request->redirect);    
    }
    
    public function getEventoArrayToStore($request){
        $array = array();
        
        if(!empty($request->price))
            $array['price'] = $request->price;
        if(!empty($request->start_date))
            $array['start_date'] = $request->start_date;
        if(!empty($request->end_date))
            $array['end_date'] = $request->end_date;
        if(!empty($request->start_hour))
            $array['start_hour'] = $request->start_hour;
        if(!empty($request->end_hour))
            $array['end_hour'] = $request->end_hour;
        if(!empty($request->created_at))
            $array['created_at'] = $request->created_at;
        if(!empty($request->img))
            $array['img'] = $this->saveImg($request->img);
        
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
        \App\Event::find($id)->delete();
        
        return Redirect::to($request->redirect);
    }
}
