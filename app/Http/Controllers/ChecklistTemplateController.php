<?php

namespace App\Http\Controllers;

use App\ChecklistTemplate;
use Illuminate\Http\Request;
use App\Profile;

class ChecklistTemplateController extends Controller
{
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
        $profile = Profile::all();
        foreach($clists as $c){
            $c ->dependences= '';
        }
        $a = array('clists'=> $clists, 'profile'=> $profile);
        return json_encode($a);
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
        $clist->type = $request["type"];
        if($clist->save()) return json_encode(array('success'=>"true"));
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
        if($clist->delete()) return json_encode(array('success'=>"true"));
        else return json_encode(array('error'=>"true"));
    }
}
