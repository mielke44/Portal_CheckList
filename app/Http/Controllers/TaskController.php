<?php

namespace App\Http\Controllers;

use App\Task;
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
            $t->dependence = array();
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
        if($task->save()) return json_encode(array('success'=>"true"));
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
        if($task->delete()) return json_encode(array('success'=>"true"));
        else return json_encode(array('error'=>"true"));
    }
}
