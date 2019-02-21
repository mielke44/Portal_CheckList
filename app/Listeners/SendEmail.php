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
use Auth;

class SendEmail
{
    public function __construct()
    {
        //
    }
    public function handleCheck(CheckUpdateEvent $event)
    {
        $data=array();
        foreach($event->getReceiver()['admin'] as $a){
            array_push($data,Admin::findOrFail($a)->email);
        }
        if(count($event->getReceiver()['emp'])>0)array_push($data,Employee::findOrFail($event->getReceiver()['emp'][0])->email);
        $demo = array(
            'Receiver' =>'',
            'Header' => 'Você tem uma atualização no Portal CheckList!',
            'text'=> $event->getText(),
            'name' => $event->getName(),
            'sender' => 'T-Systems Portal Checklist',
            'link' => 'http://apps.t-systems.com.br/portal_checklist',
        );
        foreach($data as $d){
            foreach($event->getReceiver()['admin'] as $a)$demo['Receiver']=Admin::findOrFail($a)->name;
            
            Mail::to($d)->send(new Email($demo));
        }
    }

    public function handleChecklist(ChecklistUpdateEvent $event)
    {
        $data=array(
                    0=>Admin::findOrFail($event->getReceiver()['admin'][0])['email'],
                    1=>Employee::findOrFail($event->getReceiver()['emp'][0])['email']);

        $demo = array(
            'Receiver' =>'',
            'Header' => 'Você tem uma atualização no Portal CheckList!',
            'text'=> $event->getText(),
            'name' => $event->getName(),
            'sender' => 'T-Systems Portal Checklist',
            'link' => 'http://apps.t-systems.com.br/portal_checklist',
        );
        foreach($data as $d){
            foreach($event->getReceiver()['admin'] as $a)$demo['Receiver']=Admin::findOrFail($a)->name;
            Mail::to($d)->send(new Email($demo));
        }
    }

    public function handleEmployee(NewEmployeeEvent $event)
    {
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
