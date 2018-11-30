<?php

namespace App\Http\Controllers;

use App\Task;
use App\TaskRequiere;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view("task");
    }

    public function list()
    {
        $tasks = Task::all();

        foreach($tasks as  $t){
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($request["id"] != "") $task = Task::find($request["id"]);
        else $task = new Task();
        $task->name = $request["name"];
        $task->description = $request["description"];
        $task->type = $request["type"];

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

    /**
     * Display the specified resource.
     *
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $task = Task::findOrFail($request["id"]);
        $dep = array();
        $trs = TaskRequiere::where("task_id",$task->id)->get();
        $trs2 = TaskRequiere::where("task_requiere_id",$task->id)->get();
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


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $task = Task::findOrFail($request["id"]);
        TaskRequiere::where("task_id",$task->id)->delete();
        TaskRequiere::where("task_requiere_id",$task->id)->delete();
        if($task->delete()) return json_encode(array('success'=>"true"));
        else return json_encode(array('error'=>"true"));
    }
}
