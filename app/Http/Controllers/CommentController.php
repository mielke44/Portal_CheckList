<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Comment;
use App\Admin;
use App\Employee;
use App\Event;
use App\Task;
use App\Check;
use App\Events\CheckUpdateEvent;
use App\Checklist;
use App\Group;

class CommentController extends Controller
{
    public function store(Request $r){
        $receiver = ['admin'=>[],'emp'=>[]];
        $Check = Check::findOrFail($r['check_id']);
        $task = Task::find($Check->task_id);
        $emp = Employee::findOrFail(Checklist::findOrFail($Check->checklist_id)->employee_id);
        if(!isset($r['comment_id']) || $r['comment_id'] == 0){
            $comment = new Comment();
            $status = 'add';
            if(strlen($Check->resp)>6)
            foreach(Admin::where('group',$Check->resp[5])->get() as $adm){
                array_push($receiver['admin'],$adm->id);
            }
            else if(strlen($Check->resp)>7)
            foreach(Admin::where('group',$Check->resp[5].$Check->resp[6])->get() as $adm){
                array_push($receiver['admin'],$adm->id);
            }
            else array_push($receiver['admin'],$Check->resp);
            event( new CheckUpdateEvent($Check,Auth::user(),1,$receiver));
        }else {
            $comment = Comment::findOrFail($r['comment_id']);
            $status = 'edit';
            $comment->comment = $r['comment'];
        }
        $comment->check_id = $r['check_id'];
        $comment->writer = Auth::user()->id;
        $comment->comment = $r['comment'];

        if($comment->save()){
            return json_encode(array(
                                    'st' => $status,
                                    'error' => false,
                                    'message' => 'Comentário criado com sucesso!'));
        }else{
            return json_encode(array('error' =>true,
                                    'message' => 'Ocorreu um erro, tente novamente ->',$comment->id));
        }
    }

    public function list(Request $r){
        $comments = Comment::where('check_id',$r["check_id"])->get();
        $auth_id = Auth::user()->id;
        foreach($comments as $c){
            $c->writer_name = Admin::find($c->writer)->name;
            $c->editable = $auth_id==$c->writer?true:false;
        }
        return json_encode($comments);
    }

    public function destroy(Request $request){
        $comment = Comment::findOrFail($request["id"]);
        if($comment->delete()) return json_encode(array('success'=>"true"));
        else return json_encode(array('error'=>"true"));
    }
}
