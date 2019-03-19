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
            if(strstr($t->resp,"group")){
                $t->resp_name=Group::find(str_replace("group","",$t->resp))->name;
            }
            else {
                if($t->resp==0) $t->resp_name = "Contratado";
                else $t->resp_name = Admin::find($t->resp)->name;
            }
        }
        return json_encode($tasks);
    }

    public function store(Request $request){
        if(Auth::user()->is_admin!=2)return json_encode(['error'=>true,'message'=>'Seu usuário não tem permissão para realizar esta ação!']);
        if(isset($request["id"])) $task = Task::find($request["id"]);
        else $task = new Task();
        $task->name = $request["name"];
        $task->description = $request["description"];
        $task->type = $request["type"];
        $task->resp = $request["resp"];
        $task->limit= $request["limit"];
        if($task->save())return json_encode(array('success'=>"true"));
        else return json_encode(array('error'=>"true"));
    }

    public function edit(Request $request){
        if(Auth::user()->is_admin!=2)return json_encode(['error'=>true,'message'=>'Seu usuário não tem permissão para realizar esta ação!']);
        $task = Task::findOrFail($request["id"]);
        if(strlen($task->resp)==7){
            try{$task->resp_name=Group::findOrFail($task->resp[5].$task->resp[6])->name;}
            catch(Exception $e){$task->resp_name=Group::findOrFail($task->resp[5])->name;}
        }else $task->resp_name = Admin::findOrFail($task->resp)->name;
        return json_encode($task);
    }

    public function destroy(Request $request){
        if(Auth::user()->is_admin!=2)return json_encode(['error'=>true,'message'=>'Seu usuário não tem permissão para realizar esta ação!']);
        $task = Task::findOrFail($request["id"]);
        $task->checklists()->detach();
        if($task->delete()) return json_encode(array('success'=>"true"));
        else return json_encode(array('error'=>"true"));
    }
}
