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
use App\TaskRequiere;
use App\LinkerChecklist;
use App\Group;

use App\Http\Controllers\ChecklistController;

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

    public static function store(Request $request){
        $receiver = array('admin'=>[],'emp'=>[]);
        if($request['check_id'] != "" ){
            $Check  = Check::findOrFail($request['check_id']);
            $task = Task::find($Check->task_id);
            $text = '';
            $name = '';
            $checklist=Checklist::findOrFail($Check->checklist_id);

            //Setando a array de destinatários
            if($Check['resp']==0){
                array_push($receiver['admin'],$checklist->gestor);
                array_push($receiver['emp'],$checklist->employee_id);
            }else if($Check['resp']==-1){
                foreach(Group::all() as $grp){
                    if(in_array($task->id,$grp->lists)){
                        $name=$group->name;
                        foreach(Admin::all() as $adm){
                            if($adm->group==$grp->id){
                                array_push($receiver['adm'],$adm->id);
                            }
                        }
                    }
                }
            }else{
                array_push ($receiver['admin'],$checklist->gestor,$Check['resp']);
                array_push($receiver['emp'],$checklist->employee_id);
            }
            
            //Alteração de estado da tarefa
            if($request['status']!=''){

                if($request['status']) $Check->status=1;
                else if(!$request['status'])$Check->status=0;

                $text = 'Alterou o estado da tarefa: '.$task->name;
                $name = Auth::user()->name;
                $type = 0;
                if ($Check->save()) {
                    ChecklistController::completeChecklist($checklist->id);
                    event(new CheckUpdateEvent($Check, $text, $name,$type,$receiver));
                    return json_encode(array('error' => false,
                        'message' => $Check->id."__status:".$Check->status));
                } else {
                    return json_encode(array('error' => true,
                        'message' => 'Ocorreu um erro, tente novamente!'));
                }

            }else if($request['resp']!=''){
            //Alteração de responsável da tarefa
                $Check['resp'] = $request['resp'];
                if($Check['resp']==0){
                    $resp = Employee::findOrFail($checklist->employee_id);
                    array_push($receiver['admin'],$checklist->gestor,$resp->gestor);
                    array_push($receiver['emp'],$checklist->employee_id);
                    $name=$resp->name;
                }else if($Check['resp']==-1){
                    foreach(Group::all() as $grp){
                        if(in_array($task->id,$grp->lists)){
                            $name=$group->name;
                            foreach(Admin::all() as $adm){
                                if($adm->group==$grp->id){
                                    array_push($receiver['adm'],$adm->id);
                                }
                            }
                        }
                    }
                }else{
                    array_push($receiver['admin'],$checklist->gestor,$request['resp']);
                    if(Employee::findOrFail($checklist->employee_id)->gestor!=$checklist->gestor)
                        array_push($receiver,Employee::findOrFail($checklist->employee_id)->gestor);
                    $name=Admin::findOrFail($request['resp'])->name;
                }
                $text = 'foi selecionado como responsável da tarefa: '.$task->name;
                $type = 2;

                if ($Check->save()) {

                    event(new CheckUpdateEvent($Check, $text,$name,$type, $receiver));

                    return json_encode(array('error' => false,
                        'message' => $Check->id."//status:".$Check->status));

                } else {

                    return json_encode(array('error' => true,
                        'message' => 'Ocorreu um erro, tente novamente!'));
                }
            }
        }
    }

    public static function createCheck($Checklist_id,Request $request){
        $user_id = Auth::user();
        $CLT = LinkerChecklist::where("checklist_id",$request['checklist_template_id'])->get();
        foreach($CLT as $ct){
            $receiver=array('admin'=>[],'emp'=>[]);
            $task =Task::findOrFail($ct->task_id);
            $text = 'foi selecionado como responsável da tarefa: '.$task->name;

            $check = new Check();
            $check->status = false;
            $check->task_id = $ct->task_id;
            $check->checklist_id = $Checklist_id;
            if($task->resp==0){
                $check->resp = 0;
                $name=Employee::findOrFail(Checklist::findOrFail($Checklist_id)['employee_id'])['name'];
                $receiver=array('emp'=>Employee::findOrFail(Checklist::findOrFail($Checklist_id)['employee_id'])->id);
            }else if($task->resp==-1){
                $check->resp = -1;
                foreach(Group::all() as $grp){
                    if(in_array($task->id,$grp->lists)){
                        $name=$grp->name;
                        array_push($receiver['emp'],Checklist::findOrFail($Checklist_id)['employee_id']);
                        foreach(Admin::all() as $adm){
                            if($adm->group==$grp->id){
                                array_push($receiver['admin'],$adm->id);
                            }
                        }
                    }
                }
            }else{
                $check->resp = $task->resp;
                $name=Admin::findOrFail($check->resp)['name'];
                $receiver=array('admin'=>Admin::findOrFail($check->resp)['id'],'emp'=>Checklist::findOrFail($Checklist_id)['employee_id']);
            } 
            
            $type=2;
            if($check->save()){
                event(new CheckUpdateEvent($check, $text, $name, $type, $receiver));
                if(Check::where("checklist_id",$Checklist_id)->where("task_id",$ct["task_id"])->count()==0){
                    createCheckDep($c->id,$user->id,$Checklist_id);
                }
            }
        }
        if($check->save()){
            return(json_encode(array('error'=> true,
                                    'message'=>'Ocorreu um erro, tente novamente!')));
        }
    }

    public static function createCheckDep($task_id,$user_id,$checklist_id){
        $dep = TaskRequiere::where('task_id',$task_id);
        foreach($dep as $d){
            $check = new Check();
            $check->resp = Task::findOrFail($dep->task_requiere_id)->resp;
            $check->status = false;
            $check->task_id = $d["task_requiere_id"];
            $check->checklist_id = $checklist_id;
            $check->save();
            if(Check::where("checklist_id",$checklist_id)->where("task_id",$d["task_requiere_id"])->count()==0){
                createCheckDep($c->id,$user->id,$request['id']);
            }
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
            $checklists= Checklist::where("employee_id",$emp->id)->get();
            $checks = array();
            foreach($checklists as $cl){
                $checks = array_merge($checks,Check::where("checklist_id",$cl->id)->where("resp",0)->get()->all());
            }
            $editable = false;
        }
        else {
            $checks = Check::where("resp",Auth::user()->id)->get();
            $editable = true;
        }
        foreach($checks as $c){
            $c->user = Employee::find(Checklist::find($c->checklist_id)->employee_id)->name;

        }
        return json_encode(array('checks'=>$checks,'editable'=>$editable));
    }

    public function YourChecklist(){
        return view("checklist-external");
    }
}
