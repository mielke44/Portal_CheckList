<?php

namespace App\Http\Controllers;

use App\ChecklistTemplate;
use Illuminate\Http\Request;
use App\Profile;
use App\Task;
use App\LinkerChecklist;
class ChecklistTemplateController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("checklist");
    }

    /**
     * Sends a JSON list with all instances.
     *
     * @return \Illuminate\Http\Response
     */
    public function list()
    {
        $clists = ChecklistTemplate::all();
        foreach($clists as $c){
            $dep = array();
            $c->profile = Profile::findOrFail($c->profile_id);
            $clinker = LinkerChecklist::where("checklist_id",$c->id)->get();
            foreach($clinker as $cl){
                $taskdep = Task::find($cl->task_id);
                $dep[]=array('task_id'=>$cl->task_id,"name"=>$taskdep->name, "desc"=>$taskdep->description);
            }
            $c->dependences = $dep;
        }
        return json_encode($clists);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($request["id"] != "") $clist = ChecklistTemplate::find($request["id"]);
        else $clist = new ChecklistTemplate();
        $clist->name = $request["name"];
        $clist->profile_id = $request["profile_id"];

        if($clist->save()){
            if($request->dependences != "")foreach($request->dependences as $d){
                $clinker = new LinkerChecklist();
                $clinker->checklist_id = $clist->id;
                $clinker->task_id = $d;
                $clinker->save();
            }
            return json_encode(array('success'=>"true"));
        }
        else return json_encode(array('error'=>"true"));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ChecklistTemplate  $checklistTemplate
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $clist = ChecklistTemplate::findOrFail($request["id"]);
        $dep = array();
        $clinker = LinkerCheckList::where("checklist_id",$clist->id)->get();
        foreach($clinker as $cl){
            $taskdep = Task::find($cl->task_id);
            $dep[]=$taskdep->id;
        }
        $clist->dependences = $dep;
        return $clist;
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ChecklistTemplate  $checklistTemplate
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $clist = ChecklistTemplate::findOrFail($request["id"]);
        LinkerChecklist::where("checklist_id",$clist->id)->delete();
        if($clist->delete()) return json_encode(array('success'=>"true"));
        else return json_encode(array('error'=>"true"));
    }
}
