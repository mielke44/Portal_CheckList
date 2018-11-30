<?php

namespace App\Http\Controllers;

use App\Checklist;
use App\Employee;
use Illuminate\Http\Request;
use App\LinkerChecklist;
use App\TaskRequiere;
use App\Check;
use Auth;
use App\ChecklistTemplate;
use App\Task;
use App\Http\Controllers\TaskController;

class ChecklistController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $r)
    {
        $checklists = Checklist::where("employee_id",$r->id)->select("checklist_template_id","id")->get();
        foreach($checklists as $c){
            $c->checks = Check::where("checklist_id",$c->id)->get();
            $c->tree = $this->tree($c->id);
        }

        return json_encode($checklists);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $checklist = new Checklist();
        $checklist->employee_id = $request['employee_id'];
        $checklist->checklist_template_id = $request['checklist_template_id'];
        $checklist->save();
        $CLT = LinkerChecklist::where("checklist_id",$request['checklist_template_id'])->get();
        $user_id = Auth::user();
        foreach($CLT as $ct){
            $check = new Check();
            $check->resp = $checklist->employee_id;
            $check->status = false;
            $check->task_id = $ct->task_id;
            $check->checklist_id = $checklist->id;
            $check->save();
            if(Check::where("checklist_id",$checklist->id)->where("task_id",$ct["task_id"])->count()==0){
                createCheckDep($c->id,$user->id,$checklist->id);
            }
        }
        return json_encode(array('success'=>"true"));
    }

    public function createCheckDep($task_id,$user_id,$checklist_id){
        $dep = TaskRequiere::where('task_id',$task_id);

        foreach($dep as $d){
            $task = new Check();
            $task->resp = $user_id;
            $task->status = false;
            $task->task_id = $d["task_requiere_id"];
            $task->checklist_id = $checklist_id;
            $task->save();
            if(Check::where("checklist_id",$checklist_id)->where("task_id",$d["task_requiere_id"])->count()==0){
                createCheckDep($c->id,$user->id,$request['id']);
            }

        }
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Checklist  $checklist
     * @return \Illuminate\Http\Response
     */
    public function edit(Checklist $checklist)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Checklist  $checklist
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Checklist $checklist)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Checklist  $checklist
     * @return \Illuminate\Http\Response
     */
    public function destroy(Checklist $checklist)
    {
        //
    }
}
