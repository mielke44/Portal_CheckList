<?php

namespace App\Http\Controllers;

use App\Checklist;
use App\Employee;
use Illuminate\Http\Request;
use App\Check;
use App\Events\ChecklistUpdateEvent;
use Auth;
use App\ChecklistTemplate;
use App\Task;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\CheckController;
use App\Comment;
use App\Group;
use Carbon\Carbon;

class ChecklistController extends Controller
{
    public function list(Request $r){
        $checklists = Checklist::where("employee_id",$r['id'])->select("checklist_template_id","id")->get();
        return json_encode($checklists);
    }
    
    public function store(Request $request){
        date_default_timezone_set('America/Sao_Paulo');
        $checklist = new Checklist();
        $checklist->gestor = Auth::user()->id;
        $checklist->employee_id = $request['employee_id'];
        $checklist->checklist_template_id = $request['checklist_template_id'];
        $ctemplate = ChecklistTemplate::findOrFail($checklist->checklist_template_id)['name']." ".explode(' ',Carbon::now()->toArray()['formatted']);
        if($checklist->save()){
            $emp = Employee::findOrFail($checklist->employee_id);
            $text = 'Teve uma nova lista de tarefas adicionada: '.$ctemplate.' com '.$ctemplate->withCount('tasks').' tarefas!';
            $name = $emp->name;
            if($emp->gestor==$checklist->gestor)$receiver = array('admin'=>[$checklist->gestor],'emp'=>[$emp->id]);
            else$receiver = array('admin'=>[$checklist->gestor,$emp->gestor],'emp'=>[$emp->id]);
            CheckController::createCheck($checklist['id'],$request);
            event(new ChecklistUpdateEvent($checklist, $text, $receiver ,$name,3));
        }
        return json_encode(array('success'=>"true"));
    }

    public function edit(Checklist $checklist){
        $Check = Checklist::find($id);
        if(Checklist::find($id)->delete()){
            Check::where('checklist_id',$id)->delete();
            return json_encode(array('error'=>false,
                                    'message'=> 'lista de tarefas concluída!'));
        }
    }

    public static function completeCheckList($id){
        $checklist = Checklist::findOrFail($id);
        $checks = Check::where('checklist_id',$checklist->id)->get();
        $i = 0;
        foreach($checks as $c){
            if($c['status'])$i++;
            else return 'false';
        }
        if($i = $checks->count()){
            $text = 'Esta lista de tarefas está completa!';
            $emp = Employee::findOrFail($checklist->employee_id);
            if($emp->gestor==$checklist->gestor)$receiver = array('admin'=>[$checklist->gestor],'emp'=>[$emp->id]);
            else$receiver = array('admin'=>[$checklist->gestor,$emp->gestor],'emp'=>[$emp->id]);
            $name = ChecklistTemplate::findOrFail($checklist->checklist_template_id)->name;
            event(new ChecklistUpdateEvent($checklist, $text, $receiver ,$name,4));
            return 'true';
        }
    }

    public function destroy(Request $r){
        $checklist = Checklist::findOrFail($r->checklist_id);
        $Checks = Check::where('checklist_id',$checklist->id)->get();
        
        if($checklist->delete()){
            foreach($Checks as $c){
                foreach(Comment::where("check_id",$c->id)->get() as $comment){
                    $comment->delete();
                }
                $c->delete();
            }
            return(json_encode(array('error'=> false,
                                    'message'=>$checklist->id)));
        }else{
            return(json_encode(array('error'=> true,
                                    'message'=>$checklist->id)));
        }
    }
}
