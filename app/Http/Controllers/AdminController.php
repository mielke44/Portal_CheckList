<?php

namespace App\Http\Controllers;

use App\Admin;
use App\User;
use Illuminate\Http\Request;
use Auth;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $prof_view ="false";
        return view('admin',compact('prof_view'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //print_r($request->all());
        //return;
        if($request["id"] != "") $admin = Admin::find($request["id"]);
        else $admin = new Admin();
        $admin -> name = $request['name'];
        $admin -> email = $request['email'];
        $admin -> password = bcrypt($request['password']);
        $admin -> site = $request['site'];
        $admin -> is_admin = '1';
        if ($admin -> save()) {
            return json_encode(array('error' => false,
                'message' => $admin -> id));
        } else {
            return json_encode(array('error' => true,
                'message' => 'Ocorreu um erro, tente novamente!'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $admin = Admin::findOrFail($request["id"]);
        return $admin;
    }

    public function profile(){
        $prof_view ="true";
        return view('admin',compact('prof_view'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $admin = Admin::findOrFail($request["id"]);
        if($admin->delete()) return json_encode(array('success'=>"true"));
        else return json_encode(array('error'=>"true"));
    }

    public function list(){
        $user = Auth::user();
        $list = Admin::all();
        $f = array('list'=>$list,'user'=>$user);
        return json_encode($f);
    }
}
