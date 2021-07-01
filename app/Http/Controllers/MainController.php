<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class MainController extends Controller
{
    //
    //make obj for reqhandler so I can call the methods in it 
    private  $jsons = "";

    public function index(Request $request){
        
        $jsons = $this->differ($request); 
        $t = new Transaction($jsons['result']); 
        $t->log = $jsons; 
      
        if($jsons['type'] == "xml"){
            $result = $t->isValid_XML(); 
        }else{
            $result = $t->isValid();
        }
        return $result; 
    }

    private function differ($request){ //The function to diffrentiate the type of the request

        try{                              //XML
            $doc = simplexml_load_string($request->getContent());
            $res = json_encode($doc); // became json
            $r = json_decode($res, true);  //became  obj 
            $type = "xml"; 
        }
        catch(\Exception $e){
            if(is_array($request)){      //array
                $r = json_encode($request); // became json

                $type = "json"; 
            }else{     
                                        //json
                $r = $request; //obj
                
                $type = "json";
            }
        }        
         return ['type'=>$type, 'result'=>$r]; 
    }

}



