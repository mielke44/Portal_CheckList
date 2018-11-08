<?php

namespace App\Http\Controllers;

use App\Task;
use App\TaskRequiere;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
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
            foreach($trs as $tr){
                $tt = Task::find($tr->task_requiere_id);
                $dep[]= array('task_id'=>$tr->task_requiere_id,"name"=>$tt->name);
            }
            $t->dependence = $dep;

        }
        return json_encode($tasks);
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
            if($request->dependences != "")foreach($request->dependences as $d){
                $tr = new TaskRequiere();
                $tr->task_id = $task->id;
                $tr->task_requiere_id = $d;
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
        foreach($trs as $tr){
            $tt = Task::find($tr->task_requiere_id);
            $dep[]=$tt->id;
        }
        $task->dependences = $dep;
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
