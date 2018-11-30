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
        return print_r(':D');
        /*
        $objDemo = new \stdClass();
        $objDemo->text= $event->getText();
        $objDemo->name = $event->getName();
        $employee = Employee::where("id",Checklist::where("id",$event->getCheck()->checklist_id)->select('employee_id')->get()[0]['employee_id'])->get()[0];
        $objDemo->sender = 'T-Systems LTDA Portal Checklist';
        $objDemo->receiver = $employee['name'];
        */
        //Mail::to($employee->mail)->send(new Email($objDemo)); USE THIS
        //Mail::to($employee->mail)->send(new Email($objDemo)); USE THIS
        //Mail::to('wilson.mielke@t-systems.com.br')->send(new Email($objDemo));
    }
    public function handleChecklist(ChecklistUpdateEvent $event)
    {
        return print_r(':D');
        /*
        $objDemo = new \stdClass();
        $objDemo->text= $event->getText();
        $objDemo->name = $event->getName();
        $employee = Employee::where("id",Checklist::where("id",$event->getCheck()->checklist_id)->select('employee_id')->get()[0]['employee_id'])->get()[0];
        $objDemo->sender = 'T-Systems LTDA Portal Checklist';
        $objDemo->receiver = $employee['name'];
        */
        //Mail::to($employee->mail)->send(new Email($objDemo)); USE THIS
        //Mail::to($employee->mail)->send(new Email($objDemo)); USE THIS
        //Mail::to('wilson.mielke@t-systems.com.br')->send(new Email($objDemo));
    }
}
