<?php

namespace App\Http\Controllers;

use App\Profile;
use App\Employee;
use Illuminate\Http\Request;
use App\ChecklistTemplate;
use App\Http\Controllers\ChecklistTemplateController;

class ProfileController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function index(){
        return view("profile");
    }

    public function list(){
        return json_encode(Profile::all());
    }

    public function getCheckLists(Request $r){
        $checklists = Profile::find($r['id'])->checklists()->get();
        foreach($checklists as $clist){
            $clist->tasks = ChecklistTemplateController::listTasks($clist);
        }
        return json_encode($checklists);
    }

    public function store(Request $request){
        if($request["id"] != "") $profile = Profile::find($request["id"]);
        else $profile = new Profile();
        $profile->name = $request["name"];
        if($profile->save()) return json_encode(array('success'=>"true"));
        else return json_encode(array('error'=>"true"));
    }

    public function edit(Request $request){
        $profile = Profile::findOrFail($request["id"]);
        return $profile;
    }

    public function destroy(Request $request){
        $profile= Profile::findOrFail($request["id"]);
        $emp = Employee::where('profile_id',$profile->id);
        foreach($profile->checklists()->get() as $clinker){
            $clinker->tasks()->detach();
            $clinker->profiles()->detach();
            $clinker->delete();

        }
        if($profile->delete()){
            foreach($emp as $e)$e->profile_id = null;
            return json_encode(['error'=>false]);
        }
        else return json_encode(array('error'=>true));
    }
}
