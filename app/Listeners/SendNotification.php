<?php

namespace App\Listeners;

use App\Events\CheckUpdateEvent;
use App\Events\ChecklistUpdateEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Checklist;
use App\Notification;
use App\Flag;

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
        for($i = 0; $i<3;$i++){
            $flag = new Flag();
            $flag->type = 'notification';
            $flag->receiver = $event->getReceiver()[$i];
            $flag->save();
        }
        $notification = new Notification;
        $notification->text = $event->getText();
        $notification->name = $event->getName();
        $notification->admin_id = $event->getCheck()->resp;
        $notification->employee_id = Checklist::where("id",$event->getCheck()->checklist_id)->select('employee_id')->get()[0]['employee_id'];
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
        /**
     * Handle the event.
     *
     * @param  CheckUpdateEvent  $event
     * @return void
     */
    public function ChecklistUpdate(ChecklistUpdateEvent $event)
    {
        for($i = 0; $i<2;$i++){
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
}
