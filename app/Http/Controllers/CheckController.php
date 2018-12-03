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
use App\Checklist;

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
            if($request['status']!=''){
                    if($request['status']) $Check->status=1;
                    else if(!$request['status'])$Check->status=0;
                    $text = 'Alterou o estado da tarefa: '.$task->name;
                    $name = Auth::user()->name;
                    $type = 0;



                    if ($Check->save()) {
                        event(new CheckUpdateEvent($Check, $text, $receiver,$name, $type));
                        return json_encode(array('error' => false,
                            'message' => $Check->id."__status:".$Check->status));
                    } else {
                        return json_encode(array('error' => true,
                            'message' => 'Ocorreu um erro, tente novamente!'));
                    }

            }else if($request['resp']!=''){

                    $Check->resp = $request['form']['resp']['id'];
                    $text = 'foi selecionado como responsável da tarefa: '.$task->name;
                    $name = $request['form']['resp']['name'];
                    $type = 2;
                    
                    if ($Check->save()) {

                        event(new CheckUpdateEvent($Check, $text, $receiver,$name,$type));
                        return json_encode(array('error' => false,
                            'message' => $Check->id."__status:".$Check->status));

                    } else {

                        return json_encode(array('error' => true,
                            'message' => 'Ocorreu um erro, tente novamente!'));
                    }
            }



        }else{
            return;
        }
    }

    public function list(Request $r){
        $notification = Notification::findOrFail($r['not_id']);
        $notification->status = 'seen';
        $notification->save();
        $check = Check::findOrFail($notification->check_id);
        return json_encode($check);
    }
}
