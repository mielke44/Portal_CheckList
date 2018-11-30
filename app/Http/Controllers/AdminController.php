<?php

namespace App\Http\Controllers;

use App\Admin;
use App\User;
use App\Site;
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
        if($request['form']["id"] != "") $admin = Admin::find($request['form']["id"]);
        else $admin = new Admin();
        $admin->name = $request['form']['name'];
        $admin->email = $request['form']['email'];
        $admin->site = $request['form']['site'];
        $admin->token = bcrypt($request['form']['id'].$request['form']['name']);

        if($request['is_admin']){
            if($request['form']['password']!='')$admin->password=bcrypt($request['form']['password']);
            $admin->is_admin = '1';
        }
        if(!$request['is_admin'])$admin->password = bcrypt($request['form']['id'].$request['form']['name']);
        
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
        $admin_list = User::where('is_admin',1)->get();
        $resp_list = User::where('is_admin',0)->get();
        $default = array('id'=>0,'name'=>'Contratado');
        $f = array('admin_list'=>$admin_list,'resp_list'=>$resp_list,'user'=>$user, 'default'=>$default);
        return json_encode($f);
    }

}
