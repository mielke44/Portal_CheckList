<?php

namespace App\Listeners;

use App\Events\CheckUpdateEvent;
use App\Events\ChecklistUpdateEvent;
use App\Events\NewEmployeeEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Checklist;
use App\Notification;
use App\Flag;
use App\Admin;

class SendNotification
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
    public function CheckUpdate(CheckUpdateEvent $event)
    {           
        for($i = 0; $i<count($event->getReceiver());$i++){
            $flag = new Flag();
            $flag->type = 'notification';
            $flag->receiver = $event->getReceiver()[$i];
            $flag->save();
        }
        $notification = new Notification;
        $notification->text = $event->getText();
        $notification->name = $event->getName();
        $notification->admin_id = $event->getCheck()->resp;
        $notification->employee_id = Checklist::findOrFail($event->getCheck()->checklist_id)->employee_id;
        $notification->type = $event->getType();
        $notification->check_id = $event->getCheck()->id;
        $notification->status = 'pending';

        //type -> (0:CheckUpdateStatus ; 1:CommentUpdate ; 2:ResponsibleUpdate; 3:ChecklistUpdate)

        if ($notification-> save()) {
            return json_encode(array('error' => false,
                'message' => $notification->id));
        } else {
            return json_encode(array('error' => true,
                'message' => 'Ocorreu um erro, tente novamente!'));
        }
    }

    public function ChecklistUpdate(ChecklistUpdateEvent $event)
    {
        for($i = 0; $i<count($event->getReceiver());$i++){
            $flag = new Flag();
            $flag->type = 'notification';
            $flag->receiver = $event->getReceiver()[$i];
            $flag->save();
        }

        $notification = new Notification;
        $notification->text = $event->getText();
        $notification->name = $event->getName();
        $notification->admin_id = $event->getChecklist()->gestor;
        $notification->employee_id = $event->getChecklist()->employee_id;
        $notification->type = 3;
        $notification->check_id = 0;
        $notification->status = 'pending';

        //type -> (0:CheckUpdateStatus ; 1:CommentUpdate ; 2:ResponsibleUpdate; 3:ChecklistUpdate)

        if ($notification-> save()) {
            return json_encode(array('error' => false,
                'message' => $notification->id));
        } else {
            return json_encode(array('error' => true,
                'message' => 'Ocorreu um erro, tente novamente!'));
        }
    }

    public function EmployeeUpdate(NewEmployeeEvent $event)
    {
        $Employee= $event->getEmployee();
        $Gestor=$event->getAdmin();
        $flag = new Flag();
        $flag->type = 'notification';
        $flag->receiver = $Employee->gestor;
        $flag->save();
        


        $notification = new Notification;
        $notification->text = 'Você foi selecionado como gestor do empregado '.$Employee->name;
        $notification->name = Admin::findOrFail($Employee->gestor)->name;
        $notification->admin_id = Admin::findOrFail($Employee->gestor)->id;
        $notification->employee_id = $Employee->id;
        $notification->type = 2;
        $notification->check_id = 0;
        $notification->status = 'pending';

        //type -> (0:CheckUpdateStatus ; 1:CommentUpdate ; 2:ResponsibleUpdate; 3:ChecklistUpdate)

        if ($notification-> save()) {
            return json_encode(array('error' => false,
                'message' => $notification->id));
        } else {
            return json_encode(array('error' => true,
                'message' => 'Ocorreu um erro, tente novamente!'));
        }
    }
}
