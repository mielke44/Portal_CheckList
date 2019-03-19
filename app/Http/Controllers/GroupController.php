<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Group;
use App\Admin;
use App\Check;
use App\Task;
use App\User;
use Auth;

class GroupController extends Controller
{
    public function store(Request $r){
        if(Auth::user()->is_admin!=2)return json_encode(['error'=>true,'message'=>'Seu usuário não tem permissão para realizar esta ação!']);
        if($r['id']==''){
            $group = new Group;
        }else{
            $group = Group::findOrFail($r['id']);
        }
        $group->name = $r['name'];
        $group->email = $r['email'];
        if($group->save()){
            User::where('group',$group->id)->update(['group'=>0]);
            if(isset($r['team'])){
                foreach($r['team'] as $t){
                    $user = Admin::findOrFail($t);
                    $user->group = $group->id;
                    $user->save();
                }
            }

            return json_encode(array('error' => false, 'message'=>'success!'));
        }else{
            return json_encode(array('error'=>true, 'message'=> 'Ocorreu um erro, tente novemente!'));
        }
    }

    public function list(){
        return json_encode(Group::all());
    }

    public function destroy(Request $r){
        if(Auth::user()->is_admin!=2)return json_encode(['error'=>true,'message'=>'Seu usuário não tem permissão para realizar esta ação!']);
        $Admin = Admin::where("group",$r['id'])->get();
        $task = Task::where("resp",'group'.$r['id'])->get();
        $Check = Check::where("resp",'group'.$r['id'])->get();
        $group = Group::findOrFail($r['id']);
        if($group->delete()){
            foreach($Admin as $a){
                $a->group=0;
                $a->save();
            }
            foreach($task as $t){
                $t->resp=null;
                $t->save();
            }
            foreach($Check as $c){
                $c->resp=null;
                $c->save();
            }
            return json_encode(array('error'=> false, 'message'=> 'success'));
        }else return json_encode(array('error'=> true,'message'=> 'Ocorreu um erro!'));
    }
}
