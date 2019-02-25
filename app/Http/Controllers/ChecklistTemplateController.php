<?php

namespace App\Http\Controllers;

use App\ChecklistTemplate;
use Illuminate\Http\Request;
use App\Profile;
use App\Task;
class ChecklistTemplateController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function index(){
        return view("checklist");
    }

    public function list(){
        $clists = ChecklistTemplate::all();
        foreach($clists as $c){
            $dep = array();
            $prof = array();
            foreach($clists->profiles() as $pl)
                array_push($prof,$pl->profile_id);
            foreach($clists->tasks() as $cl)
                array_push($dep,$cl->task_id);
            $c->dependences = $dep;
            $c->profile = $prof;
        }
        return json_encode($clists);
    }

    public function store(Request $request){
        if(isset($request["id"])) $clist = ChecklistTemplate::find($request["id"]);
        else $clist = new ChecklistTemplate();
        $clist->name = $request["name"];
        if($clist->save()){
            $clist->profiles()->detach();
            $clist->tasks()->detach();
            foreach($request['profile_id'] as $pid){
                $clist->profiles()->attach($pid,['profile_id'=>$pid,'checklist_id'=>$clist->id]);
            }
            if(isset($request['tasks']))ChecklistTemplateController::taskDepAttach($clist,$request['task']);
            return json_encode(array('success'=>"true"));
        }
        else return json_encode(array('error'=>"true"));
    }

    public static function taskDepAttach($clist,$task_array){
            //$task = [
            //    {task_id,[{task_id,[dep]}]},
            //    {task_id,[dep]}
            //   ]
        foreach($task_array as $task){
            if($task['dep']->count()==0)$clist->tasks()->attach($task['task_id'],['task_id'=>$task['task_id'],'checklist_id'=>$clist->id]);
            else{
                foreach($task['dep'] as $dep)
                    $clist->tasks()->attach($task['task_id'],['task_id'=>$task['task_id'],'checklist_id'=>$clist->id,'task_id_below'=>$dep['task_id']]);
            ChecklistTemplateController::taskDepAttach($clist,$task['dep']);
            }
        }
    }

    public function edit(Request $request){
        $clist = ChecklistTemplate::findOrFail($request["id"]);
        $profileLinker = $clist->profiles();
        $clinker = $clist->tasks();
        $profile_id=[];
        $dep = array();
        foreach($profileLinker as $p){
            array_push($profile_id,$p->profile_id);
        }
        foreach($clinker as $cl){
            $taskdep = Task::find($cl->task_id);
            $dep[]=$taskdep->id;
        }
        $clist->dependences = $dep;
        $clist->profile_id = $profile_id;
        return $clist;
    }

    public function destroy(Request $request){
        $clist = ChecklistTemplate::findOrFail($request["id"]);
        $clist->tasks()->detach();
        $clist->profiles()->detach();
        if($clist->delete()){
            return json_encode(array('success'=>"true"));
        } 
        else return json_encode(array('error'=>"true"));
    }
}
