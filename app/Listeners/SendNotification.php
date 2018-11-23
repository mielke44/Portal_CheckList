<?php

namespace App\Listeners;

use App\Events\CheckUpdateEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Checklist;
use App\Notification;

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
    public function handle(CheckUpdateEvent $event)
    {
        $employee_id = Checklist::where("id",$event->getCheck()->checklist_id)->select('employee_id')->get();

        $notification = new Notification;
        $notification->text = $event->getText();
        $notification->name = $event->getName();
        $notification->admin_id = $event->getCheck()->resp;
        $notification->employee_id = $employee_id;
        $notification->type = '';

        if ($notification-> save()) {
            return json_encode(array('error' => false,
                'message' => $notification->id));
        } else {
            return json_encode(array('error' => true,
                'message' => 'Ocorreu um erro, tente novamente!'));
        }
    }
}
