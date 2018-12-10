<?php

namespace App\Http\Controllers;

use App\Profile;
use App\Employee;
use Illuminate\Http\Request;
use App\ChecklistTemplate;
use App\ProfileLinker;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        return view("profile");
    }

    public function list()
    {
        $profile = Profile::all();
        foreach($profile as $p){
            $linker= ProfileLinker::where("profile_id",$p->id)->get();
            if(count($linker)==0)$p->clist=0;
            foreach($linker as $l){
                $p->clist = ChecklistTemplate::where("id",$l->checklist_id)->select("name")->get();
                //$profile[i]->clist[i]->name
            }
        }
        //print_r(count($profile[0]->clist));
        return json_encode($profile);
    }

    public function store(Request $request)
    {
        if($request["id"] != "") $profile = Profile::find($request["id"]);
        else $profile = new Profile();
        $profile->name = $request["name"];
        if($profile->save()) return json_encode(array('success'=>"true"));
        else return json_encode(array('error'=>"true"));
    }

    public function edit(Request $request)
    {
        $profile = Profile::findOrFail($request["id"]);
        return $profile;
    }

    public function destroy(Request $request)
    {
        $profile= Profile::findOrFail($request["id"]);
        $emp = Employee::where('profile_id',$profile->id);
        $plinker = ProfileLinker::where('profile_id',$profile->id);
        if($profile->delete()){
            foreach($emp as $e)$e->profile_id = null;
            foreach($plinker as $p)$p->delete();
            return json_encode(array('success'=>"true"));
        }
        else return json_encode(array('error'=>"true"));
    }
}
