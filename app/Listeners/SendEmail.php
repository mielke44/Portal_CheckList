<?php

namespace App\Listeners;

use App\Events\CheckUpdateEvent;
use App\Events\ChecklistUpdateEvent;
use App\Events\NewEmployeeEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;
use App\Mail\Email;
use App\Employee;
use App\Checklist;
use App\Admin;
use App\Group;
use Auth;

class SendEmail
{
    public function __construct()
    {
        //
    }

    public function handleCheck(CheckUpdateEvent $event)
    {
        $task= $event->getCheck()->getTemplate();
        $msgs=[
            5=>'A tarefa vai expirar em '.intdiv($task->limit,2).' dias!',
            2=>'Foi selecionado como responsável da tarefa '.$event->getCheck()->getTemplate()->name,
            1=>'Escreveu um comentário na tarefa '.$event->getCheck()->getTemplate()->name,
            0=>'Alterou o estado da tarefa '.$event->getCheck()->getTemplate()->name,
            -1=>'Expirou o tempo de execução!'];

        $data=array();
        if(strlen($event->getCheck()->resp)==7)array_push($data,Group::find($event->getCheck()->resp[5].$event->getCheck()->resp[6])->email);
        else if(strlen($event->getCheck()->resp)==6)array_push($data,Group::find($event->getCheck()->resp[5])->email);
        else foreach($event->getReceiver()['admin'] as $a)array_push($data,Admin::findOrFail($a)->email);
        
        $demo = array(
            'Receiver' =>'',
            'Header' => 'Você tem uma atualização no Portal CheckList!',
            'text'=> $msgs[$event->getType()],
            'name' => $event->getName(),
            'sender' => 'T-Systems Portal Checklist',
            'link' => 'http://apps.t-systems.com.br/portal_checklist',
        );
        foreach($data as $d){
            if(strlen($event->getCheck()->resp)==1)foreach($event->getReceiver()['admin'] as $a)$demo['Receiver']=Admin::findOrFail($a)->name;
            else if(strlen($event->getCheck()->resp)==7) $demo['Receiver']=Group::find($event->getCheck()->resp[5].$event->getCheck()->resp[6])->name;
            else $demo['Receiver']=Group::find($event->getCheck()->resp[5])->name;
            print_r($demo);
            //Mail::to($d)->send(new Email($demo));
        }
    }

    public function handleChecklist(ChecklistUpdateEvent $event){
        $ctemplate=$event->getChecklist()->getTemplate();
        $msgs=[
            3=> 'teve a lista de tarefas '.$ctemplate['name'].' criada com '.$ctemplate->tasks()->count().' tarefas',
            4=> ' Lista de tarefas '.$ctemplate->name. 'foi concluída'];

        $data=array(
                    0=>Admin::findOrFail($event->getReceiver()['admin'][0])['email']);

        $demo = array(
            'Receiver' =>'',
            'Header' => 'Você tem uma atualização no Portal CheckList!',
            'text'=> $msgs[$event->getType()],
            'name' => $event->getName(),
            'sender' => 'T-Systems Portal Checklist',
            'link' => 'http://apps.t-systems.com.br/portal_checklist',
        );
        foreach($data as $d){
            foreach($event->getReceiver()['admin'] as $a)$demo['Receiver']=Admin::findOrFail($a)->name;
            print_r($demo);
            //Mail::to($d)->send(new Email($demo));
        }
    }

    public function handleEmployee(NewEmployeeEvent $event){
        $data=array(0=>$event->getEmployee()['email']);
        if($event->getReason()=='new'){
            $demo=array(
                            'Receiver' => $event->getEmployee()['name'],
                            'Header' => 'Bem vindo à T-Systems do Brasil LTDA!',
                            'text'=> "Adicionou você ao portal CheckList!",
                            'name' =>$event->getAdmin()->name,
                            'sender' => 'T-Systems LTDA Portal Checklist',
                            'link' => 'http://apps.t-systems.com.br/portal_checklist/employee/yourchecklist?token='.$event->getEmployee()->token);
        }else if($event->getReason()=='update'){
            $demo = array(
                            'Receiver'=>$event->getAdmin()['name'],
                            'Header'=>'Você tem uma atualização no Portal Checklist!',
                            'text'=>"Adicionou você como gestor do empregado: ".$event->getEmployee()->name,
                            'name'=>Auth::user()->name,
                            'sender'=>'T-Systems LTDA Portal Checklist',
                            'link'=>'http://apps.t-systems.com.br/portal_checklist');
        }
        foreach($data as $d){
            Mail::to($d)->send(new Email($demo));
        }
    }
}
