<?php

namespace App\Http\Controllers;

use App\ChecklistTemplate;
use Illuminate\Http\Request;
use App\Profile;
use App\Task;
use Illuminate\Support\Facades\DB;
class ChecklistTemplateController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function index(){
        return view("checklist");
    }

    public static function listTasks($clist){
        $dep = array();
        foreach($clist->tasks()->get() as $tl)array_push($dep,$tl->id);
        return json_encode($dep);
    }

    public function store(Request $request){
        if(Auth::user()->is_admin!=2)return json_encode(['error'=>true,'message'=>'Seu usuário não tem permissão para realizar esta ação!']);
        $pid = $request['profile_id'];
        if(isset($request['id'])) $clist = ChecklistTemplate::find($request["id"]);
        else $clist = new ChecklistTemplate();
        $clist->tasks()->detach();
        $clist->name=$request['name'];
        $clist->profile_id = $pid;
        try{
            $clist->save();
            if(isset($request['tasks']))ChecklistTemplateController::taskDepAttach($clist,$request['tasks'],null);
            return json_encode(['error'=>false,'message'=>'Profile: '.$pid.'-- Template: '.$clist->id]);
        }catch(Exception $e){
            return json_encode(['error'=>true,'message'=>'Ocorreu um erro!','StackTrace'=>$e->toString()]);
        }
    }

    public static function taskDepAttach($clist,$task_array,$task_dep){
            //$task = [
            //    {task_id, children = [task_id,children = []]},
            //    {task_id,children = [dep]}
            //   ]
        foreach($task_array as $task){
            $clist->tasks()->attach($task['task_id'],['task_id'=>$task['task_id'],'checklist_template_id'=>$clist->id,'task_id_below'=>$task_dep]);
            if(isset($task['children']))
            ChecklistTemplateController::taskDepAttach($clist,$task['children'],$task['task_id']);
        }
    }

    public function list(Request $r){

        if(isset($r['id']))$clist = ChecklistTemplate::where("profile_id",$r['id'])->get();
        else $clist = ChecklistTemplate::all();
        return json_encode($clist);
        //return json_encode(ChecklistTemplateController::returnFormDep($clist,null));
    }

    public function tree(Request $r){
        $clist = ChecklistTemplate::find($r["id"]);
        return json_encode(ChecklistTemplateController::returnFormDep($clist,null));
    }


    public static function returnFormDep($clist,$task_id){
        $tasks=[];
        $linkers_root = DB::table('linker_checklist')->where('checklist_template_id',$clist->id)->where('task_id_below',$task_id)->get();
        foreach($linkers_root as $linker){
            $a1 = ['text' =>Task::find($linker->task_id)['name'],'task_id'=>$linker->task_id,'children'=>[]];
            $a1['children']=ChecklistTemplateController::returnFormDep($clist,$linker->task_id);
            //if(count($a1['children'])==0)unset($a1['children']);
            array_push($tasks,$a1);
        }
        return $tasks;
    }

    public function edit(Request $request){
        $clist = ChecklistTemplate::findOrFail($request["id"]);
        $clinker = $clist->tasks();
        $profile_id=[];
        $dep = array();
        foreach($clinker as $cl){
            $taskdep = Task::find($cl->task_id);
            $dep[]=$taskdep->id;
        }
        $clist->dependences = $dep;
        return $clist;
    }

    public function destroy(Request $request){
        if(Auth::user()->is_admin!=2)return json_encode(['error'=>true,'message'=>'Seu usuário não tem permissão para realizar esta ação!']);
        $clist = ChecklistTemplate::findOrFail($request["id"]);
        $clist->tasks()->detach();
        if($clist->delete()){
            return json_encode(array('success'=>"true"));
        }
        else return json_encode(array('error'=>"true"));
    }

    public function getCheckLists(Request $r){
        $checklists = ChecklistTemplate::where('profile_id',$r['id'])->get();
        foreach($checklists as $clist){
            $clist->tasks = ChecklistTemplateController::listTasks($clist);
        }
        return json_encode($checklists);
    }
}
