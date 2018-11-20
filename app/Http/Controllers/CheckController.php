<?php

namespace App\Http\Controllers;

use App\Check;
use Illuminate\Http\Request;

class CheckController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($request['check_id'] != "" ){
            $Check  = Check::findOrFail($request['check_id']);
            
            if($request['status']==0) $Check->status = 1;
            else $Check->status = 0;
            if ($Check -> save()) {
                return json_encode(array('error' => false,
                    'message' => $Check->id));
            } else {
                return json_encode(array('error' => true,
                    'message' => 'Ocorreu um erro, tente novamente!'));
            }
        }else{
        $Check = Check::where("checklist_id",$request['id'],"task_id",$request['id'])->get();
            $Check->resp = $request["resp"];
            $Check->status = false;
            $Check->comment = "";
            $Check->save();
        }
    }
}
