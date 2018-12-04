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
use App\User;
class CheckController extends Controller
{

    public function __construct(Request $r){
        if(isset($r->token)){
            $user = User::where('token',$r->token)->first();
            Auth::login($user);
        }
        $this->middleware('auth');
    }

    public function store(Request $request)
    {
        if($request['check_id'] != "" ){
            $Check  = Check::findOrFail($request['check_id']);
            $task = Task::find($Check->task_id);
            $text = '';
            $name = '';
            $checklist=Checklist::findOrFail($Check->checklist_id);


            if($Check['resp']==$checklist->employee_id){
                $receiver = array ('0'=> $checklist->gestor,
                    '1'=> $checklist->employee_id);
            }else{
                $receiver = array ('0'=> $checklist->gestor,
                    '1'=> $checklist->employee_id,
                    '2'=> $Check['resp']);
            }

            if($request['status']!=''){

                ChecklistController::completeChecklist($checklist->id);

                if($request['status']) $Check->status=1;
                else if(!$request['status'])$Check->status=0;

                $text = 'Alterou o estado da tarefa: '.$task->name;
                $name = Auth::user()->name;
                $type = 0;

                if ($Check->save()) {
                    event(new CheckUpdateEvent($Check, $text, $name,$type,$receiver));
                    return json_encode(array('error' => false,
                        'message' => $Check->id."__status:".$Check->status));
                } else {
                    return json_encode(array('error' => true,
                        'message' => 'Ocorreu um erro, tente novamente!'));
                }

            }else if($request['resp']!=''){

                $Check->resp = $request['resp'];
                $admin = Admin::find($request['resp']);
                $text = 'foi selecionado como responsável da tarefa: '.$admin->name;
                $type = 2;

                if($Check['resp']==$checklist->employee_id){
                    $receiver = array ('0'=> $checklist->gestor,
                        '1'=> $checklist->employee_id);
                }else{
                    $receiver = array ('0'=> $checklist->gestor,
                        '1'=> $checklist->employee_id,
                        '2'=> $request['resp']);
                }

                if ($Check->save()) {

                    event(new CheckUpdateEvent($Check, $text,$admin->name,$type, $receiver));

                    return json_encode(array('error' => false,
                        'message' => $Check->id."__status:".$Check->status));

                } else {

                    return json_encode(array('error' => true,
                        'message' => 'Ocorreu um erro, tente novamente!'));
                }
            }



        }else{
            return(json_encode(array('error'=> true,
                                    'message'=>'Ocorreu um erro, tente novamente!')));
        }
    }

    public function list(Request $r){
        $notification = Notification::findOrFail($r['not_id']);
        $notification->status = 'seen';
        $notification->save();
        $check = Check::findOrFail($notification->check_id);
        return json_encode($check);
    }

    public function listYourChecks(){
        $checks = Check::where("resp",Auth::user()->id)->get();
        return json_encode($checks);
    }
    public function YourChecklist(){
        return view("checklist-external");
    }
}
