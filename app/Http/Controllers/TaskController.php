<?php

namespace App\Http\Controllers;

use App\Task;
use App\TaskRequiere;
use Illuminate\Http\Request;
use App\Admin;
use App\Group;
use App\LinkerChecklist;

class TaskController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function index(){
        return view("task");
    }

    public function list(){
        $tasks = Task::all();

        foreach($tasks as  $t){
            if($t->resp==='0')$t->resp_name='Contratado';
            else if(strlen($t->resp)>5){
                $t->resp_name=Group::findOrFail($t->resp[5])->name;
            }else $t->resp_name = Admin::findOrFail($t->resp)->name;
            $dep = array();
            $trs = TaskRequiere::where("task_id",$t->id)->get();
            $trs2 = TaskRequiere::where("task_requiere_id",$t->id)->get();
            foreach($trs as $tr){
                $tt = Task::find($tr->task_requiere_id);
                $dep[]= array('task_id'=>$tr->task_requiere_id,"name"=>$tt->name);
            }
            $t->dependence = $dep;
            $dep = array();
            foreach($trs2 as $tr){
                $tt = Task::find($tr->task_id);
                $dep[]= array('task_id'=>$tr->task_id,"name"=>$tt->name);
            }
            $t->dependence2 = $dep;

        }
        return json_encode($tasks);
    }

    //task_requiere_id -> outra tarefa depende desta
    //task_id -> tafera que depende da task_requiere_id
    public static function tree(){
        $tasks = Task::all();
        $tree = array();
        foreach($tasks as $t){
            if(TaskRequiere::where("task_requiere_id",$t->id)->count()==0){
                $aux = array();
                $aux["id"] = $t->id;
                $aux["name"] = $t->name;
                $aux["tree"] = "";
                $aux["children"] = array();
                $deps = TaskRequiere::where("task_id",$t->id)->get();
                foreach($deps as $d){
                    array_push($aux["children"],TaskController::treeChildren($d->task_requiere_id,$d->task_id.";"));
                }
                array_push($tree,$aux);
            }
        }
        return json_encode($tree);
    }

    private static function treeChildren($id,$tasksInTree){
        $aux = array();
        $task = Task::find($id);
        $deps =  TaskRequiere::where("task_id",$id)->get();
        $aux["id"] = $id;
        $aux["name"] = $task->name;
        $aux["tree"] = $tasksInTree;
        $aux["children"] = array();
        foreach($deps as $d){
            array_push($aux["children"],TaskController::treeChildren($d->task_requiere_id,$tasksInTree.$id.";"));
        }
        return $aux;
    }

    public function store(Request $request){
        if($request["id"] != "") $task = Task::find($request["id"]);
        else $task = new Task();
        $task->name = $request["name"];
        $task->description = $request["description"];
        $task->type = $request["type"];
        $task->resp = $request["resp"];
        $task->limit= $request["limit"];

        if($task->save()) {
            TaskRequiere::where("task_requiere_id",$task->id)->delete();
            if($request->dependences2 != "")foreach($request->dependences2 as $d){
                $tr = new TaskRequiere();
                $tr->task_id = $d;
                $tr->task_requiere_id = $task->id;
                $tr->save();
            }
            return json_encode(array('success'=>"true"));
        }
        else return json_encode(array('error'=>"true"));
    }

    public function edit(Request $request){
        $task = Task::findOrFail($request["id"]);
        $dep = array();
        $trs = TaskRequiere::where("task_id",$task->id)->get();
        $trs2 = TaskRequiere::where("task_requiere_id",$task->id)->get();
        if(strlen($request['resp'])>1){
            $task->resp='group'.Group::findOrFail($request['resp'][5])->id;
        }
        foreach($trs as $tr){
            $dep[]=$tr->task_requiere_id;
        }
        $task->dependences = $dep;
        $dep=array();
        foreach($trs2 as $tr){
            $dep[]= $tr->task_id;
        }
        $task->dependences2 = $dep;
        return $task;
    }

    public function destroy(Request $request){
        $task = Task::findOrFail($request["id"]);
        TaskRequiere::where("task_id",$task->id)->delete();
        TaskRequiere::where("task_requiere_id",$task->id)->delete();
        LinkerChecklist::where("task_id",$task->id)->delete();
        if($task->delete()) return json_encode(array('success'=>"true"));
        else return json_encode(array('error'=>"true"));
    }
}
