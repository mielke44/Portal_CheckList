<?php

namespace App\Http\Controllers;

use App\Profile;
use Illuminate\Http\Request;
use App\ChecklistTemplate;

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

    /**
     * Sends a JSON with all instances.
     *
     * @return \Illuminate\Http\Response
     */
    public function list()
    {
        $profile = Profile::all();
        foreach($profile as $p){
            $p->clist = ChecklistTemplate::where("profile_id",$p->id)->select("name")->get();
        }
        return json_encode($profile);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($request["id"] != "") $profile = Profile::find($request["id"]);
        else $profile = new Profile();
        $profile->name = $request["name"];
        if($profile->save()) return json_encode(array('success'=>"true"));
        else return json_encode(array('error'=>"true"));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $profile = Profile::findOrFail($request["id"]);
        return $profile;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $profile= Profile::findOrFail($request["id"]);
        $emp = Employee::where('profile_id',$profile->id);
        $clist = ChecklistTemplate::where('profile_id',$profile->id);
        if($profile->delete()){
            foreach($emp as $e)$e->profile_id = null;
            foreach($clist as $c)$c->profile_id = null;
            return json_encode(array('success'=>"true"));
        }
        else return json_encode(array('error'=>"true"));
    }
}
