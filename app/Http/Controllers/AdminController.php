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
        if($request['form']["id"] != "") $admin = Admin::find($request['form']["id"]);
        else $admin = new Admin();
        if($request['form']['group']!=''){
            if($request['form']['s']==1){
                $admin->group=0;
                $admin->save();
                return json_encode(array('error'=>false, 'message'=>'sucesso!'));
            }else{
                foreach($request['form']['id'] as $id){
                    $admin = Admin::findOrFail($id);
                    if($request['form']['s']==2)$admin->group=$request['form']['group'];
                    $admin->save();
                }
                return json_encode(array('error'=>false, 'message'=>'sucesso!'));
            }
        }
        $admin->name = $request['form']['name'];
        $admin->email = $request['form']['email'];
        $admin->site = $request['form']['site'];
        $admin->token = bcrypt($request['form']['id'].$request['form']['name']);
        if($request['form']['is_admin']=='true'){
            if($request['form']['password']!='')$admin->password=bcrypt($request['form']['password']);
            $admin->is_admin = '1';
        }
        if($request['form']['is_admin']=='false'){
            $admin->is_admin=0;
            $admin->password = bcrypt($request['form']['id'].$request['form']['name']);
        }
        if($request['form']['group']!='')$admin->group = $request['form']['group'];

        if ($admin -> save()) {
            return json_encode(array('error' => false,
                'message' => $admin -> id));
        } else {
            return json_encode(array('error' => true,
                'message' => 'Ocorreu um erro, tente novamente!'));
        }
    }

    public function edit(Request $request){
        $admin = Admin::findOrFail($request["id"]);
        return $admin;
    }

    public function profile(){
        $prof_view ="true";
        return view('admin',compact('prof_view'));
    }

    public function destroy(Request $request){
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
