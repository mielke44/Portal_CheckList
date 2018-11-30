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

class CommentController extends Controller
{
    public function store(Request $r){
        $Check = Check::findOrFail($r['check_id']);
        $task = Task::find($Check->task_id);
        $receiver = array ( 'gestor'=> Checklist::where('id',$Check->checklist_id)->select('gestor')->get(),
        'employee'=> Checklist::where('id',$Check->checklist_id)->select('employee_id')->get(),
        'resp'=> $request['resp']);

        if($r['comment_id']==''){
            $comment = new Comment();
            $text = 'Adicionou uma comentário na tarefa: '.$task->name;
            $name = Auth::user()->name;
            $status = 'add';
            $type = 1;
        }else {
            $comment = Comment::findOrFail($r['comment_id']); 
            $status = 'edit';
            $comment->comment = $r['form']['commentedit'];
            $text = 'Alterou uma comentário na tarefa: '.$task->name;
            $name = Auth::user()->name;
            $type = 1;
            if($comment->save()){
                event(new CheckUpdateEvent($Check, $text,$name, $type, $receiver));
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
        $comment->writer_name = Auth::user()->name;
        $comment->comment = $r['form']['comment'];

        if($comment->save()){
            event(new CheckUpdateEvent($Check, $text,$name, $type));
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
            return json_encode(array('comment' => Comment::where('check_id',$r['check_id'])->get()));
    }
    public function destroy(Request $request)
    {
        $comment = Comment::findOrFail($request["id"]);
        if($comment->delete()) return json_encode(array('success'=>"true"));
        else return json_encode(array('error'=>"true"));
    }
}
