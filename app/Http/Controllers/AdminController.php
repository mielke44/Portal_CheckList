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
        if(isset($request['form']["id"])) $admin = Admin::find($request['form']["id"]);
        else $admin = new Admin();
        if(isset($request['form']['group'])){
            if(isset($admin->group)){
                $admin->group=0;
                $admin->save();
                return json_encode(array('error'=>false, 'message'=>'sucesso!'));
            }else{
                foreach($request['form']['id'] as $id){
                    $admin = Admin::findOrFail($id);
                    $admin->group=$request['form']['group'];
                    $admin->save();
                }
                return json_encode(array('error'=>false, 'message'=>'sucesso!'));
            }
        }
        if($request['form']['is_admin']){
            if(isset($request['form']['password']))$admin->password=bcrypt($request['form']['password']);
            $admin->is_admin=1;
        }else if(!$request['form']['is_admin']){
            $admin->is_admin=0;
            $admin->password = bcrypt($request['form']['id'].$request['form']['name']);
        }
        $admin->name = $request['form']['name'];
        $admin->email = $request['form']['email'];
        $admin->site = $request['form']['site'];
        $admin->token = bcrypt($request['form']['id'].$request['form']['name']);
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
        $user = Auth::user();
        $admin_list = User::where('is_admin',1)->get();
        $resp_list = User::where('is_admin',0)->get();
        $default = array('id'=>0,'name'=>'Contratado');
        $f = array('admin_list'=>$admin_list,'resp_list'=>$resp_list,'user'=>$user, 'default'=>$default);
        return json_encode($f);
    }

}
