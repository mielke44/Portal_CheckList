<?php

namespace App\Http\Controllers;

use App\Check;
use Illuminate\Http\Request;
use App\Event;
use Auth;
use App\Task;
use App\Events\CheckUpdateEvent;
use App\Admin;
use App\Notification;
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
            $task = Task::find($Check->task_id);
            $text = '';
            $name = '';

            if($request['change_type']=='status'){

                    if($request['status']==0 || $request['status']==false)$Check->status = "true";
                    if($request['status']==1 || $request['status']==true)$Check->status = "false";
                    $text = 'Alterou o estado da tarefa: '.$task->name;
                    $name = Auth::user()->name;
                    $type = 0;

            }else if($request['change_type']=='resp'){

                    $Check->resp = $request['form']['resp']['id'];
                    $text = 'foi selecionado como responsável da tarefa: '.$task->name;
                    $name = $request['form']['resp']['name'];
                    $type = 2;
            }

            if ($Check->save()) {
                event(new CheckUpdateEvent($Check, $text, $name, $type));
                return json_encode(array('error' => false,
                    'message' => $Check->id."__status:".$Check->status));
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

    public function list(Request $r){
        $notification = Notification::findOrFail($r['not_id']);
        $check = Check::findOrFail($notification->check_id);
        return route('employee',$check);
    }
}
