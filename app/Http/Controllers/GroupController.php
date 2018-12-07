<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Group;

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
            return json_encode(array('error' => false, 'message'=>'success!'));
        }else{
            return json_encode(array('error'=>true, 'message'=> 'Ocorreu um erro, tente novemente!'));
        }
    }
    public function list(){
        return json_encode(Group::all());
    }

    public function destroy($id){
        if(Group::findOrFail($id)->delete()){
            return json_encode(array('error'=> false, 'message'=> 'success'));
        }else return json_encode(array('error'=> true,'message'=> 'Ocorreu um erro!'));
    }
}
