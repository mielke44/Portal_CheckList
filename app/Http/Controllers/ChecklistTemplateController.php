<?php

namespace App\Http\Controllers;

use App\ChecklistTemplate;
use Illuminate\Http\Request;
use App\Profile;
use App\Task;
use App\LinkerChecklist;
use App\ProfileLinker;
use App\TaskRequiere;
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
        //print_r(ProfileLinker::where("checklist_id",1)->get());
        //ProfileLinker::where("checklist_id",$clists[0]->id));
        foreach($clists as $c){
            $dep = array();
            $prof = array();
            $plinker = ProfileLinker::where("checklist_id",$c->id)->get();
            foreach($plinker as $pl){
                $profile = Profile::findOrFail($pl->profile_id);
                array_push($prof,array('id'=>$profile->id,'name'=>$profile->name));
            }
            $clinker = LinkerChecklist::where("checklist_id",$c->id)->get();
            foreach($clinker as $cl){
                $taskdep = Task::find($cl->task_id);
                $dep[]=array('task_id'=>$cl['task_id'],"name"=>$taskdep['name'], "desc"=>$taskdep['description']);
            }
            $c->dependences = $dep;
            $c->profile = $prof;
        }
        return json_encode($clists);
    }

    public function store(Request $request){
        if($request["id"] != "") $clist = ChecklistTemplate::find($request["id"]);
        else $clist = new ChecklistTemplate();
        $clist->name = $request["name"];

        if($clist->save()){
            $clinker = LinkerChecklist::where("checklist_id",$clist->id)->delete();
            $proflinker = ProfileLinker::where('checklist_id',$clist->id)->delete();
            foreach($request['profile_id'] as $pid){
                $linker = new ProfileLinker();
                $linker->profile_id = $pid;
                $linker->checklist_id = $clist->id;
                $linker->save();
            }
            if($request->dependences != "")foreach($request->dependences as $d){
                //$d = task id;
                if(TaskRequiere::where("task_id",$d)->count()>0)foreach(TaskRequiere::where("task_id",$d)->get() as $dep){
                    if(!in_array($dep->task_requiere_id,$request->dependences)){
                        $clinker = new LinkerChecklist();
                        $clinker->checklist_id = $clist->id;
                        $clinker->task_id = $dep->task_requiere_id;
                        $clinker->save();
                    }
                }
                $clinker = new LinkerChecklist();
                $clinker->checklist_id = $clist->id;
                $clinker->task_id = $d;
                $clinker->save();
            }
            return json_encode(array('success'=>"true"));
        }
        else return json_encode(array('error'=>"true"));
    }

    public function edit(Request $request){
        $clist = ChecklistTemplate::findOrFail($request["id"]);
        $profileLinker = ProfileLinker::where('checklist_id',$request["id"])->select('profile_id')->get();
        $clinker = LinkerCheckList::where("checklist_id",$clist->id)->get();
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
        LinkerChecklist::where("checklist_id",$clist->id)->delete();
        ProfileLinker::where("checklist_id",$clist->id)->delete();
        if($clist->delete()){
            return json_encode(array('success'=>"true"));
        } 
        else return json_encode(array('error'=>"true"));
    }
}
