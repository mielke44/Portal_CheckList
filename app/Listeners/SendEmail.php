<?php

namespace App\Listeners;

use App\Events\CheckUpdateEvent;
use App\Events\ChecklistUpdateEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;
use App\Mail\Email;
use App\Employee;
use App\Checklist;
use App\Admin;

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
        
        $gestor=Admin::findOrFail($event->getReceiver()[0]);
        $employee = Employee::findOrFail($event->getReceiver()[1]);
        if(count($event->getReceiver())==3)$data = array(
                                                        0=>$gestor['name'],
                                                        1=>$gestor['email'],
                                                        2=>$employee['name'],
                                                        3=>$employee['email'],
                                                        4=>Admin::findOrFail($event->getReceiver()[2])['name'],
                                                        5=>Admin::findOrFail($event->getReceiver()[2])['email']);
        else $data=array(0=>$gestor['name'],1=>$gestor['email'],2=>$employee['name'],3=>$employee['email']);
        
        $objDemo = new \stdClass();
        $objDemo->text= $event->getText();
        $objDemo->name = $event->getName();
        $objDemo->sender = 'T-Systems Portal Checklist';
        for($i=0;$i<count($data);$i+=2){
        $objDemo->receiver=$data[$i];
        //Mail::to($data[$i+1])->send(new Email($objDemo));
        }
        //Mail::subject('teste')->to('wilson.mielke@t-systems.com.br')->send(new Email($objDemo)); //TESTING
    }

    public function handleChecklist(ChecklistUpdateEvent $event)
    {
        $gestor=Admin::findOrFail($event->getReceiver()[0]);
        $employee = Employee::findOrFail($event->getReceiver()[1]);
        $data=array(0=>$gestor['name'],1=>$gestor['email'],2=>$employee['name'],3=>$employee['email']);

        $objDemo = new \stdClass();
        $objDemo->text= $event->getText();
        $objDemo->name = $event->getName();
        $objDemo->sender = 'T-Systems LTDA Portal Checklist';
        
        for($i=0;$i<count($data);$i+=2){
            $objDemo->receiver=$data[$i];
            //Mail::to($data[$i+1])->send(new Email($objDemo));
        }

        //Mail::to('wilson.mielke@t-systems.com.br')->send(new Email($objDemo));
    }
}
