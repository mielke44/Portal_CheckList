<?php

namespace App\Http\Controllers;

use App\Checklist;
use App\Employee;
use Illuminate\Http\Request;
use App\LinkerChecklist;
use App\Check;
use App\Events\ChecklistUpdateEvent;
use Auth;
use App\ChecklistTemplate;
use App\Task;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\CheckController;
use App\Comment;
use App\Group;

class ChecklistController extends Controller
{
    public function index(Request $r){
        $checklists = Checklist::where("employee_id",$r->id)->select("checklist_template_id","id")->get();
        foreach($checklists as $c){
            $c->checks = Check::where("checklist_id",$c->id)->get();
            $c->tree = $this->tree($c->id);
        }

        return json_encode($checklists);
    }

    public function store(Request $request){
        $checklist = new Checklist();
        $checklist->gestor = Auth::user()->id;
        $checklist->employee_id = $request['employee_id'];
        $checklist->checklist_template_id = $request['checklist_template_id'];
        $ctemplate = ChecklistTemplate::findOrFail($checklist->checklist_template_id)['name'];
        $CLT = LinkerChecklist::where("checklist_id",$request['checklist_template_id'])->get();
        
        if($checklist->save()){
            $emp = Employee::findOrFail($checklist->employee_id);
            $text = 'Uma nova lista de tarefas foi criada: '.$ctemplate.'; Com '.count($CLT).' tarefas!';
            $name = $emp->name;
            if($emp->gestor==$checklist->gestor)$receiver = array('admin'=>[$checklist->gestor],'emp'=>[$emp->id]);
            else$receiver = array('admin'=>[$checklist->gestor,$emp->gestor],'emp'=>[$emp->id]);
            event(new ChecklistUpdateEvent($checklist, $text, $receiver ,$name,3));
        }
        CheckController::createCheck($checklist['id'],$request);
        return json_encode(array('success'=>"true"));
    }

    public function tree($id){
        $checks = Check::where("checklist_id",$id)->get();
        $tree = json_decode(TaskController::tree());
        return $this->treeChild($tree,$checks);
    }

    public function treeChild($tree,$checks){
        $aux = array();
        foreach($tree as $t){
            foreach($checks as $c){
                if($c->task_id == $t->id){
                    $t->children = $this->treeChild($t->children,$checks);
                    $t->check_id = $c->id;
                    $t->status = $c->status;
                    $aux[] = $t;
                }
            }
        }
        return $aux;
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
        //dd(Check::where('checklist_id',Checklist::findOrFail($id)->id)->get()[0]['status']);
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
