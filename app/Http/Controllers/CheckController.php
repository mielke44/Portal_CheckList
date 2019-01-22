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
use Carbon\Carbon;

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
            if($Check['resp']==='0'){
                array_push($receiver['admin'],$checklist->gestor);
                array_push($receiver['emp'],$checklist->employee_id);
            }else if(strlen($Check['resp'])>5){
                $name=Group::findOrFail($Check['resp'][5])->name;
                foreach(Admin::where('group',$Check['resp'][5])->get() as $adm){
                    array_push($receiver['admin'],$adm->id);
                }
            }else{
                if($checklist->gestor!=$Check['resp'])array_push($receiver['admin'],$checklist->gestor,$Check['resp']);
                else array_push($receiver['admin'],$Check['resp']);
                array_push($receiver['emp'],$checklist->employee_id);
            }
            
            //Alteração de estado da tarefa
            //task_requiere_id -> outra tarefa depende desta
            //task_id -> tafera que depende da task_requiere_id
            if($request['status']!=''){
                if($request['status']){
                    $Check->status=1;
                    $tempstat=0;
                    if(TaskRequiere::where('task_requiere_id',$Check->task_id)->count()!=0){
                        foreach(TaskRequiere::where('task_requiere_id',$Check->task_id)->get() as $t){
                            //$t = linker da tarefa sendo atualizada
                            foreach(Check::where('task_id',$t->task_id)->where('checklist_id',$Check->checklist_id)->get() as $cd){
                                //$cd = tarefa instanciada que depende de $t
                                foreach(TaskRequiere::where('task_id',$t->task_id)->get() as $tr){
                                    //$tr = linkers de Tarefas abaixo de $cd (inclui $t)
                                    foreach(Check::where('task_id',$tr->task_requiere_id)->where('checklist_id',$Check->checklist_id)->get() as $cdp);
                                        //$cdp = instâncias de tarefas abaixo de $cd (inclui a instância de $t)
                                        if($cdp->id==$Check->id)$tempstat+=1;
                                        if($cdp->status==1)$tempstat+=1;
                                    }
                                if(TaskRequiere::where('task_id',$t->task_id)->count()==$tempstat){
                                    $cd->status=0;
                                    $cd->save();
                                    if($cd->resp==0)event(new CheckUpdateEvent($cd,"Está liberada pra ser concluída!","",array('employee'=>$cd->resp)));
                                    else if(strlen($cd->resp)!=1){
                                        $group = Group::findOrFail($request['resp'][5]);
                                        $temparray=array('admin'=>[]);
                                        foreach(Admin::where('group',$request['resp'][5])->get() as $adm){
                                            array_push($temparray['admin'],$adm->id);
                                        }
                                        event(new CheckUpdateEvent($cd,"Está liberada pra ser concluída!","",$temparray));
                                    }else event(new CheckUpdateEvent($cd,"Está liberada pra ser concluída!","",array('admin'=>$cd->resp)));
                                }
                            }
                        }
                    }
                }else if(!$request['status']){
                    $Check->status=0;
                    if(TaskRequiere::where('task_requiere_id',$Check->task_id)->count()!=0){
                        foreach(TaskRequiere::where('task_requiere_id',$Check->task_id)->get() as $t){
                            foreach(Check::where('task_id',$t->task_id)->where('checklist_id',$Check->checklist_id)->get() as $cd){
                                $cd->status=-2;
                                $cd->save();
                            }
                        }
                    }
                }
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
                if($request['resp']==='0'){
                    $resp = Employee::findOrFail($checklist->employee_id);
                    array_push($receiver['admin'],$checklist->gestor,$resp->gestor);
                    array_push($receiver['emp'],$checklist->employee_id);
                    $name=$resp->name;
                }else if(strlen($request['resp'])>1){
                    $group = Group::findOrFail($request['resp'][5]);
                    $name=$group->name;
                    foreach(Admin::where('group',$request['resp'][5])->get() as $adm){
                        array_push($receiver['admin'],$adm->id);
                    }
                }else{
                    array_push($receiver['admin'],$checklist->gestor,$request['resp']);
                    if(Employee::findOrFail($checklist->employee_id)->gestor!=$checklist->gestor)
                        array_push($receiver,Employee::findOrFail($checklist->employee_id)->gestor);
                    $name=Admin::findOrFail($request['resp'])->name;
                }
                $Check['resp'] = $request['resp'];
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
            $receiver=array('admin'=>[],'emp'=>[Employee::findOrFail(Checklist::findOrFail($Checklist_id)['employee_id'])->id]);
            $task =Task::findOrFail($ct->task_id);
            $text = 'foi selecionado como responsável da tarefa: '.$task->name;

            $check = new Check();
            if($task->limit>0){
                $Today = intval((explode("-",explode(" ",Carbon::now())[0])[2]))+intval(($task->limit));
                $month = intval((explode("-",explode(" ",Carbon::now())[0])[1]));
                $year = intval((explode("-",explode(" ",Carbon::now())[0])[0]));
                $time = explode(" ",Carbon::now())[1];
                if($Today>31){
                    $month += 1;
                    if($month>12){
                        $year += 1;
                        $month = 1;
                    }
                    if(in_array($month,[1,4,7,8,10,12]))$Today -= 31;
                    if(in_array($month,[3,5,6,9,11]))$Today -= 30;
                    if($month==2)if(intdiv($year,4)==0 && intdiv($year,100)!=0)$Today -= 29;else $Today -= 28;
                }else if($month>12){
                    $year += 1;
                    $month = 1;
                }
                $date = strval($Today.'-'.$month.'-'.$year.' as '.$time);
                $check->limit = $date;
            }else $check->limit='Não há uma data para expirar!';

            if(TaskRequiere::where('task_id',$task->id)->count()!=0) $check->status=-2;
            else $check->status = 0;

            $check->task_id = $ct->task_id;
            $check->checklist_id = $Checklist_id;
            if($task->resp==='0'){
                $check->resp = 0;
                $name=Employee::findOrFail(Checklist::findOrFail($Checklist_id)['employee_id'])['name'];
            }else if(strlen($task->resp)>5){
                $check->resp = $task->resp;
                $name=Group::findOrFail($check->resp[5])->name;
                foreach(Admin::where('group',$task->resp[5])->get() as $adm)array_push($receiver['admin'],$adm->id);
            }else{
                $check->resp = $task->resp;
                $name=Admin::findOrFail($check->resp)['name'];
                array_push($receiver['admin'],Admin::findOrFail($check->resp)['id']);
            }
            $type=2;
            if($check->save()){
                event(new CheckUpdateEvent($check, $text, $name, $type, $receiver));
                if(Check::where("checklist_id",$Checklist_id)->where("task_id",$ct["task_id"])->count()==0){
                    createCheckDep($c->id,$user->id,$Checklist_id);
                }
            }
        }
        return(json_encode(array('error'=> true,
                                    'message'=>'Ocorreu um erro, tente novamente!')));
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
            $editable = true;
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

    public static function monitorExpireDate(){
        $date=Carbon::now()->toArray();
        $Today= array('day'=>$date['day'],'month'=>$date['month'],'year'=>$date['year']);
        foreach(Check::where('status',0)->get() as $c){
            $receiver=array('admin'=>[],'emp'=>[Checklist::findOrFail($c->checklist_id)['employee_id']]);
            if($c->resp==='0'){
            }else if(strlen($c->resp)>5){
                foreach(Admin::where('group',$c->resp[5])->get() as $adm){
                    array_push($receiver['admin'],$adm->id);
                }
            }else{
                array_push($receiver['admin'],Admin::findOrFail($c->resp)['id']);
            }
            $t = Task::findOrFail($c->task_id);
            $Create=array(
                'day'=>explode("-",explode(" ",$c->created_at)[0])[2],
                'month'=>explode("-",explode(" ",$c->created_at)[0])[1],
                'year'=>explode("-",explode(" ",$c->created_at)[0])[0]);
            if(strlen($c->limit)<31){
                $Expire=array(
                    'day'=>explode("-",explode(" ",$c->limit)[0])[0],
                    'month'=>explode("-",explode(" ",$c->limit)[0])[1],
                    'year'=>explode("-",explode(" ",$c->limit)[0])[2]);
                if($Today['day']==$Expire['day']){
                    if($Today['month']==$Expire['month']){
                        $c->status=-1;
                        $c->save();
                        event(new CheckUpdateEvent($c, "Expirou o tempo de execução!", $t->name, -1, $receiver));
                    }
                }else{
                    if(intval($Create['day'])<intval($Expire['day'])){
                        $D =intval($Create['day'])+intdiv($t->limit,2);
                        if($Today['day']==$D){
                            if($Today['month']==$Expire['month']){
                                event(new CheckUpdateEvent($c, "Expira em ".intdiv($t->limit,2).' dias', $t->name, 5, $receiver));
                            }
                        }
                    }else if(intval($Create['day'])>intval($Expire['day'])){
                        $D = intval($Create['day'])+intdiv(31,2);
                        if(in_array($Today['month'],[1,4,7,8,10,12]))$D -= 31;
                        if(in_array($Today['month'],[3,5,6,9,11]))$D -= 30;
                        if($Today['month']==2)if(intdiv($Today['year'],4)==0 && intdiv($Today['year'],100)!=0)$D -= 29;else $D -= 28;
                        if($Today['day']==$D){
                            if($Today['month']==$Expire['month']){
                                event(new CheckUpdateEvent($c, "Expira em ".intdiv($t->limit,2).' dias', $t->name, 5, $receiver));
                            }
                        }
                    }
                }
            }
        }
    }
}
