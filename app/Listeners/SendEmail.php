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
        if(count($event->getReceiver())==1)$data=array(
                                                        0=>Employee::findOrFail(Checklist::findOrFail($event->getCheck()->checklist_id))[0]->email);
        if(count($event->getReceiver())==2)$data=array(
                                                        0=>Admin::findOrFail($event->getReceiver()[0])['email'],
                                                        1=>Employee::findOrFail($event->getReceiver()[1])['email']);
        if(count($event->getReceiver())==3)$data = array(
                                                        0=>Admin::findOrFail($event->getReceiver()[0])['email'],
                                                        1=>Employee::findOrFail($event->getReceiver()[1])['email'],
                                                        2=>Admin::findOrFail($event->getReceiver()[2])['email']);

        
        
        $demo[] = array(
            'Header' => 'Você tem uma atualização no Portal CheckList!',
            'text'=> $event->getText(),
            'name' => $event->getName(),
            'sender' => 'T-Systems Portal Checklist',
            'link' => 'http://localhost:8000/',
        );

        
        Mail::send('mail',$demo,
        function($message) use ($data) {
            $message->from('Checklist.no-reply@webexchange.t-systems.com.br', 'Portal CheckList');
            //$message->to($data);
            $message->to('wilson.mielke@t-systems.com.br');
            $message->subject('Nova atualização no portal!');
            }
        );
    }

    public function handleChecklist(ChecklistUpdateEvent $event)
    {
        $data=array(
                    0=>Admin::findOrFail($event->getReceiver()[0])['email'],
                    1=>Employee::findOrFail($event->getReceiver()[1]['email']));

        $demo[] = array(
            'Header' => 'Você tem uma atualização no Portal CheckList!',
            'text'=> $event->getText(),
            'name' => $event->getName(),
            'sender' => 'T-Systems Portal Checklist',
            'link' => 'http://localhost:8000/',
        );
        
        Mail::send('mail',$demo,
        function($message) use ($data) {
            $message->from('Checklist.no-reply@webexchange.t-systems.com.br', 'Portal CheckList');
            //$message->to($data);
            $message->to('wilson.mielke@t-systems.com.br');
            $message->subject('Nova atualização no portal!');
            }
        );
    }

    public function handleEmployee(NewEmployeeEvent $event)
    {
        $data[]=array(0=>$event->getEmployee()['email']);
        if($event->getReason()=='new'){
            $demo[]=array(
                            'receiver' => $event->getEmployee()['name'],
                            'Header' => 'Bem vindo à T-Systems do Brasil LTDA!',
                            'text'=> "Adicionou você ao portal CheckList!",
                            'name' =>$event->getAdmin()->name,
                            'sender' => 'T-Systems LTDA Portal Checklist',
                            'link' => 'http://localhost:8000/employee/yourchecklist?token='.$event->getEmployee()->token);
        }else if($event->getReason()=='update'){
            $demo[] = array(
                            'receiver'=>$event->getAdmin()['name'],
                            'Header'=>'Você tem uma atualização no Portal Checklist!',
                            'text'=>"Adicionou você como gestor do empregado: ".$event->getEmployee()->name,
                            'name'=>Auth::user()->name,
                            'sender'=>'T-Systems LTDA Portal Checklist',
                            'link'=>'http://localhost:8000');
        }
        Mail::send('mail',$demo,
        function($message) use ($data) {
            $message->from('Checklist.no-reply@webexchange.t-systems.com.br', 'Portal CheckList');
            //$message->to($data);
            $message->to('wilson.mielke@t-systems.com.br');
            $message->subject('Nova atualização no portal!');
            }
        );


        //Mail::to('wilson.mielke@t-systems.com.br')->send(new Email($objDemo)); //TESTING
    }
}
