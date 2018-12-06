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
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  CheckUpdateEvent  $event
     * @return void
     */

    public function handleCheck(CheckUpdateEvent $event)
    {
        $resp = Employee::findOrFail(Checklist::findOrFail($event->getCheck()->checklist_id))[0];
        if(count($event->getReceiver())==1){
            
            $data=array(0=>$resp->name,1=>$resp->email);
        
        }else{
            $gestor=Admin::findOrFail($event->getReceiver()[0]);
            $employee = Employee::findOrFail($event->getReceiver()[1]);
        }
        if(count($event->getReceiver())==3)$data = array(
                                                        0=>$gestor['name'],
                                                        1=>$gestor['email'],
                                                        2=>$employee['name'],
                                                        3=>$employee['email'],
                                                        4=>Admin::findOrFail($event->getReceiver()[2])['name'],
                                                        5=>Admin::findOrFail($event->getReceiver()[2])['email']);
        if(count($event->getReceiver())==2)$data=array(0=>$gestor['name'],1=>$gestor['email'],2=>$employee['name'],3=>$employee['email']);
        
        
        $objDemo = new \stdClass();
        $objDemo->Header = 'Você tem uma atualização no Portal CheckList!';
        $objDemo->text= $event->getText();
        $objDemo->name = $event->getName();
        $objDemo->sender = 'T-Systems Portal Checklist';
        $objDemo->link = 'http://localhost:8000/';

        for($i=0;$i<count($data);$i+=2){
        $objDemo->receiver=$data[$i];
        //Mail::to($data[$i+1])->send(new Email($objDemo));
        }
        //Mail::to('wilson.mielke@t-systems.com.br')->send(new Email($objDemo)); //TESTING
    }

    public function handleChecklist(ChecklistUpdateEvent $event)
    {
        $gestor=Admin::findOrFail($event->getReceiver()[0]);
        $employee = Employee::findOrFail($event->getReceiver()[1]);
        $data=array(0=>$gestor['name'],1=>$gestor['email'],2=>$employee['name'],3=>$employee['email']);

        $objDemo = new \stdClass();
        $objDemo->Header = 'Você tem uma atualização no Portal CheckList!';
        $objDemo->text= $event->getText();
        $objDemo->name = $event->getName();
        $objDemo->sender = 'T-Systems LTDA Portal Checklist';
        $objDemo->link = 'http://localhost:8000/';
        
        for($i=0;$i<count($data);$i+=2){
            $objDemo->receiver=$data[$i];
            //Mail::to($data[$i+1])->send(new Email($objDemo));
        }

        //Mail::to('wilson.mielke@t-systems.com.br')->send(new Email($objDemo));
    }

    public function handleEmployee(NewEmployeeEvent $event)
    {
        $employee = $event->getEmployee();
        $admin = $event->getAdmin();
        if($event->getReason()=='new'){
            $objDemo = new \stdClass();
            $objDemo->receiver = $employee['name'];
            $objDemo->Header = 'Bem vindo à T-Systems do Brasil LTDA!';
            $objDemo->text= "Adicionou você ao portal CheckList!";
            $objDemo->name = $event->getAdmin()->name;
            $objDemo->sender = 'T-Systems LTDA Portal Checklist';
            $objDemo->link = 'http://localhost:8000/employee/yourchecklist?token='.$employee->token;

            //Mail::to($employee['email'])->send(new Email($objDemo)); //USE THIS
        }else if($event->getReason()=='update'){
            $objDemo = new \stdClass();
            $objDemo->receiver = $admin['name'];
            $objDemo->Header = 'Você tem uma atualização no Portal Checklist!';
            $objDemo->text= " Adicionou você como gestor do empregado: ".$employee->name;
            $objDemo->name = Auth::user()->name;
            $objDemo->sender = 'T-Systems LTDA Portal Checklist';
            $objDemo->link = 'http://localhost:8000';
    
            //Mail::to($employee['email'])->send(new Email($objDemo)); //USE THIS
        }

        //Mail::to('wilson.mielke@t-systems.com.br')->send(new Email($objDemo)); //TESTING
    }
}
