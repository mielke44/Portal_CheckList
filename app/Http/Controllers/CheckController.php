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
use App\Group;
use DateTime;

use App\Http\Controllers\ChecklistController;
use App\ChecklistTemplate;
use Carbon\Carbon;


class CheckController extends Controller
{

    public function __construct(Request $r){
        //
    }

    public static function edit(Request $request){
        $receiver = array('admin'=>[],'emp'=>[]);
        if(isset($request['check_id'])){
            $Check  = Check::findOrFail($request['check_id']);
            $task = Task::find($Check->task_id);
            $checklist=Checklist::findOrFail($Check->checklist_id);
            $checklist_template=ChecklistTemplate::find($checklist->checklist_template_id);
            $text = '';
            $name = '';

            //Setando a array de destinatários
            if(strlen($Check['resp'])==6){
                $name=Group::findOrFail($Check['resp'][5])->name;
                foreach(Admin::where('group',$Check['resp'][5])->get() as $adm){
                    array_push($receiver['admin'],$adm->id);
                }
            }else if(strlen($Check['resp'])==7){
                $name=Group::findOrFail($Check['resp'][5].$Check['resp'][6])->name;
                foreach(Admin::where('group',$Check['resp'][5].$Check['resp'][6])->get() as $adm){
                    array_push($receiver['admin'],$adm->id);
                }
            }else{
                if($checklist->gestor!=$Check['resp'])array_push($receiver['admin'],$checklist->gestor,$Check['resp']);
                else array_push($receiver['admin'],$Check['resp']);
                array_push($receiver['emp'],$checklist->employee_id);
            }

            //Alteração de estado da tarefa
            if(isset($request['status'])){
                if($request['status']){
                    $tempstat=0;
                    $Check->status=1;
                    $superior_check = Check::where('task_id',$checklist_template->tasks()->where('task_id_below',$task->id)->get()[0]->task_id)->get();//check que depende da check sendo alterada
                    foreach($checklist_template->tasks()->where('task_id',$checklist_template->tasks()->where('task_id_below',$task->id)->get()[0]->task_id)->get() as $check_id_below){ //todas as relações que tem a check de cima em comum
                        if(Check::where('task_id',$check_id_below->task_id_below)[0]->status)$tempstat++;
                    }
                    if($checklist_template->tasks()->where('task_id',$superior_check->id)->count()==$tempstat){
                        $superior_check->status=0;
                        $superior_check->save();
                        if(strlen($superior_check->resp)==6){
                            $group = Group::findOrFail($request['resp'][5]);
                            $temparray=array('admin'=>[]);
                            foreach(Admin::where('group',$request['resp'][5])->get() as $adm){
                                array_push($temparray['admin'],$adm->id);
                            }
                            event(new CheckUpdateEvent($cd,"Está liberada pra ser concluída!","",$temparray));
                        }else if(strlen($superior_check->resp)==7){
                            $group = Group::findOrFail($request['resp'][5].$request['resp'][6]);
                            $temparray=array('admin'=>[]);
                            foreach(Admin::where('group',$request['resp'][5].$request['resp'][6])->get() as $adm){
                                array_push($temparray['admin'],$adm->id);
                            }
                            event(new CheckUpdateEvent($cd,"Está liberada pra ser concluída!","",$temparray));
                        }else event(new CheckUpdateEvent($cd,"Está liberada pra ser concluída!","",array('admin'=>$superior_check->resp)));
                    }
                }else if(!$request['status']){
                $Check->status=0;
                if($checklist_template->tasks()->where('task_id_below',$task->id)->get()->count()>1){
                    $task = Check::where('task_id',$checklist_template->tasks()->where('task_id_below',$task->id)->get()->task_id)->get()[0];
                    $task->status=-2;
                    $task->save();
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
            if(strlen($request['resp'])==6){
                $group = Group::findOrFail($request['resp'][5]);
                $name=$group->name;
                foreach(Admin::where('group',$request['resp'][5])->get() as $adm){
                    array_push($receiver['admin'],$adm->id);
                }
            }else if(strlen($request['resp'])==7){
                $group = Group::findOrFail($request['resp'][5].$request['resp'][6]);
                $name=$group->name;
                foreach(Admin::where('group',$request['resp'][5].$request['resp'][6])->get() as $adm){
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

    public static function createCheck($template_id,$Checklist_id){
        $user_id = Auth::user();
        
        foreach(ChecklistTemplate::find($template_id)->tasks()->get() as $ct){
            $receiver = array('admin'=>[],'emp'=>[]);

            $task =Task::findOrFail($ct["id"]);
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

            if(isset($ct->task_id_below)) $check->status=-2;
            else $check->status = 0;

            $check->task_id = $ct['id'];
            $check->checklist_id = $Checklist_id;
            $check->resp = $task->resp;
            if(strlen($task->resp)==7){
                    $name=Group::findOrFail($check->resp[5].$check->resp[6])->name;
                    foreach(Admin::where('group',$task->resp[5].$task->resp[6])->get() as $adm)array_push($receiver['admin'],$adm->id);
            }else if(strlen($task->resp)==6){
                    $name=Group::findOrFail($check->resp[5])->name;
                    foreach(Admin::where('group',$task->resp[5])->get() as $adm)array_push($receiver['admin'],$adm->id);
            }else{
                $check->resp = $task->resp;
                $name=Admin::findOrFail($check->resp)['name'];
                array_push($receiver['admin'],Admin::findOrFail($check->resp)['id']);
            }
            $type=2;
            try{
                $check->save();
                if(isset(DB::table('linker_checklist')->where('check_id',$check->id)->get()['check_id_below'])){
                    $check->status= -2;
                    $check->save();
                }
            }
            catch(Exception $e){
                return json_encode(['error'=>false,'message'=>$e->toString()]);
            }
        }
    }

    public function list(Request $r){
        $checks = Check::where("checklist_id",$r['checklist_id'])->get();
        return json_encode($checks);
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
        date_default_timezone_set('America/Sao_Paulo');
        foreach(Check::where('status',0)->get() as $c){
            $receiver=array('admin'=>[],'emp'=>[Checklist::findOrFail($c->checklist_id)['employee_id']]);
            if(strlen($c->resp)==6){
                foreach(Admin::where('group',$c->resp[5])->get() as $adm){
                    array_push($receiver['admin'],$adm->id);
                }
            }else if(strlen($c->resp)==7){
                foreach(Admin::where('group',$c->resp[5].$c->resp[6])->get() as $adm){
                    array_push($receiver['admin'],$adm->id);
                }
            }else{
                array_push($receiver['admin'],Admin::findOrFail($c->resp)['id']);
            }

            $t = Task::findOrFail($c->task_id);
            $now = new DateTime(date('m/d/Y h:i:s a', time()));
            $created_at = new DateTime($c->created_at);
            $check_limit = new DateTime($c->limit);

            if($now>=$check_limit) event(new CheckUpdateEvent($c, "Expirou o tempo de execução!", $t->name, -1, $receiver));
            else event(new CheckUpdateEvent($c, "Expira em ".intdiv($t->limit,2).' dias', $t->name, 5, $receiver));
        }
    }
}
