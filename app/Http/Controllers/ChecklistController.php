<?php

namespace App\Http\Controllers;

use App\Checklist;
use App\Employee;
use Illuminate\Http\Request;
use App\LinkerChecklist;
use App\TaskRequiere;
use Auth;

class ChecklistController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $emp = Employee::findOrFail($id);
        return view('checklist-employee',compact('emp'));
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
        $CLT = LinkerChecklist::where("checklist_id",$request['id']);
        $user_id = Auth::user();
        foreach($CLT as $ct){
            $task = new Check();
            $task->resp = $request["resp"];
            $task->status = false;
            $task->comment = "";
            $task->task_id = $taskTemp->id;
            $task->checklist_id = $checklist->id;
            $task->save();
            if(Check::where("checklist_id",$checklist_id,"task_id",$ct["task_id"])->count()==0){
                createCheckDep($c->id,$user->id,$checklist->id);
            }
        }
        
    }

    public function createCheckDep($task_id,$user_id,$checklist_id){
        $dep = TaskRequiere::where('task_id',$task_id);
        
        foreach($dep as $d){
            $task = new Check();
            $task->resp = $user_id;
            $task->status = false;
            $task->comment = "";
            $task->task_id = $d["task_requiere_id"];
            $task->checklist_id = $checklist_id;
            $task->save();
            if(Check::where("checklist_id",$checklist_id,"task_id",$d["task_requiere_id"])->count()==0){
                createCheckDep($c->id,$user->id,$request['id']);
            }
            
        }
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
