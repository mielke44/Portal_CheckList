<?php

namespace App\Http\Controllers;

use App\Check;
use Illuminate\Http\Request;
use App\Event;
use Auth;
use App\Task;
use App\Events\CheckUpdateEvent;
use App\Admin;
use App\Employee;
use App\Notification;
use App\Checklist;
use App\User;
class CheckController extends Controller
{

    public function __construct(Request $r){
            if(isset($r->token)){
                $user = User::where('token',$r->token)->first();
                if($user==null){
                    $emp = Employee::where('token',$r->token)->first();
                    if($emp!=null){
                        $user = new User();
                        $user->id = 0;
                        $user->password= bcrypt("secret");
                        $user->site=$emp->site;
                        $user->is_admin=-1;
                        $user->name = $emp->name;
                        $user->email = $emp->email;
                        $user->token = $emp->token;
                        $user->save();
                        Auth::login($user,true);

                    }
                }
                else Auth::login($user,true);
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


            if($Check['resp']==0){
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

                $Check['resp'] = $request['resp'];
                if($Check['resp']!=0){
                    $resp = Admin::find($request['resp']);
                    $receiver = array ('0'=> $checklist->gestor,
                    '1'=> $checklist->employee_id,
                    '2'=> $request['resp']);

                }else{
                    $resp = Employee::findOrFail($checklist->employee_id);
                    $receiver = array ('0'=> $checklist->gestor,
                        '1'=> $checklist->employee_id);
                }

                $text = 'foi selecionado como responsável da tarefa: '.$task->name;
                $type = 2;

                if ($Check->save()) {

                    event(new CheckUpdateEvent($Check, $text,$resp->name,$type, $receiver));

                    return json_encode(array('error' => false,
                        'message' => $Check->id."//status:".$Check->status));

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
        if(Auth::user()->is_admin==-1){
            $emp = Employee::where('token',Auth::user()->token)->first();
            $checklists = Checklist::where("employee_id",$emp->id)->get();
            $checks = array();
            foreach($checklists as $cl){
                array_push($checks,Check::where("checklist_id",$cl->id)->where("resp",0));
            }
        }
        else $checks = Check::where("resp",Auth::user()->id)->get();
        return json_encode($checks);
    }
    public function YourChecklist(){
        return view("checklist-external");
    }
}
