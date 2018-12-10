<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Group;
use App\Admin;

class GroupController extends Controller
{
    public function store(Request $r){
        if($r['form']['id']==''){
            $group = new Group;
        }else{
            $group = Group::findOrFail($r['form']['id']);
        }
        $group->name = $r['form']['name'];
        if($group->save()){
            foreach($r['form']['team'] as $t){
                $user = Admin::findOrFail($t);
                $user->group = $group->id;
                $user->save();
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
        $Admin = Admin::where("group",$r['id'])->get();
        $group = Group::findOrFail($r['id']);
        if($group->delete()){
            foreach($Admin as $a)$a->group=null;
            return json_encode(array('error'=> false, 'message'=> 'success'));
        }else return json_encode(array('error'=> true,'message'=> 'Ocorreu um erro!'));
    }
}
