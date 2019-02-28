<?php

namespace App\Http\Controllers;

use App\Admin;
use App\User;
use App\Site;
use Illuminate\Http\Request;
use Auth;
use App\Group;

class AdminController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function index(){
        $prof_view ="false";
        return view('admin',compact('prof_view'));
    }

    public function store(Request $request){
        if(isset($request["id"])) $admin = Admin::find($request["id"]);
        else $admin = new Admin();
        $admin->is_admin = $request['is_admin'];
        if($admin->is_admin == 1){
            if(isset($request['password']))$admin->password=bcrypt($request['password']);
        }
        else{
            if($admin->password == '') $admin->password = bcrypt($request['id'].$request['name']);
        }
        $admin->group = $request['group'];
        $admin->name = $request['name'];
        $admin->email = $request['email'];
        $admin->site = $request['site'];
        $admin->token = bcrypt($request['id'].$request['name']);
        try{
            $admin -> save();
            return json_encode(['error'=>false,'message' => $admin->id]);
        }
        catch(Exception $e){
            return json_encode(['error' => true,'message' => 'Ocorreu um erro, tente novamente!','StackTrace'=>$e->toString()]);
        }
    }

    public function edit(Request $request){
        return Admin::findOrFail($request["id"]);
    }

    public function profile(){
        $prof_view ="true";
        return view('admin',compact('prof_view'));
    }

    public function destroy(Request $request){
        $admin = Admin::findOrFail($request["id"]);
        try{
            $admin->delete();
            return json_encode(array('success'=>"true"));
        }
        catch(Exception $e){
            return json_encode(array('error'=>"true","message"=>"ocorreu um erro!","StackTrace"=>$e->toString()));
        }
    }

    public function list(){
        return json_encode(User::all());
    }

}
