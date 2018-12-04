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

class CommentController extends Controller
{
    public function store(Request $r){
        $Check = Check::findOrFail($r['check_id']);
        $task = Task::find($Check->task_id);
        $receiver = array ('0'=> Checklist::findOrFail($Check->checklist_id)->gestor,
        '1'=> Checklist::findOrFail($Check->checklist_id)->employee_id,
        '2'=> $Check['resp']);

        if($r['comment_id']==''){
            $comment = new Comment();
            $text = 'Adicionou uma comentário na tarefa: '.$task->name;
            $name = Auth::user()->name;
            $status = 'add';
            $type = 1;
        }else {
            $comment = Comment::findOrFail($r['comment_id']);
            $status = 'edit';
            $comment->comment = $r['comment'];
            $text = 'Alterou uma comentário na tarefa: '.$task->name;
            $name = Auth::user()->name;
            $type = 1;
            if($comment->save()){
                event(new CheckUpdateEvent($Check, $text,$name, $type,$receiver));
                return json_encode(array(
                                        'st' => $status,
                                        'error' => false,
                                        'message' => 'Comentário editado com sucesso!'));
            }else{
                return json_encode(array('error' =>true,
                                        'message' => 'Ocorreu um erro, tente novamente ->',$comment->id));
            }
        }
        $comment->check_id = $r['check_id'];
        $comment->writer = Auth::user()->id;
        $comment->comment = $r['comment'];

        if($comment->save()){
            event(new CheckUpdateEvent($Check, $text,$name, $type,$receiver));
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


    public function destroy(Request $request)
    {
        $comment = Comment::findOrFail($request["id"]);
        if($comment->delete()) return json_encode(array('success'=>"true"));
        else return json_encode(array('error'=>"true"));
    }
}
