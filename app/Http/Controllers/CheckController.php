<?php

namespace App\Http\Controllers;

use App\Check;
use Illuminate\Http\Request;
use App\Event;
use Auth;
use App\Task;
use App\Events\CheckUpdateEvent;
use App\Admin;
class CheckController extends Controller
{
    public function list(){

    }

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
            $task = Task::find($Check->task_id);
            $text = '';
            $name = '';

            if($request['change_type']=='status'){

                    if($request['status']==0)$Check->status = 1;
                    else $Check->status = 0;
                    $text = 'Alterou o estado da tarefa: '.$task->name;
                    $name = Auth::user()->name;

            }else if($request['change_type']=='resp'){

                    $Check->resp = $request['form']['resp']['id'];
                    $text = 'foi selecionado como responsável da tarefa: '.$task->name;
                    $name = $request['form']['resp']['name'];

            }

            if ($Check->save()) {
                event(new CheckUpdateEvent($Check, $text, $name));
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
            $Check->save();
            
        }
    }
}
