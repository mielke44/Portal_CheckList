<?php

namespace App\Events;
use App\Checklist;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ChecklistUpdateEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $checklist;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Checklist $checklist,$text,$receiver,$name,$type)
    {
        $this->checklist = $checklist;
        $this->text = $text;
        $this->receiver = $receiver;
        $this->name = $name;
        $this->type=$type;
    } 

    public function getType(){
        return $this->type;
    }
    public function getChecklist(){
        return $this->checklist;
    }
    public function getText(){
        return $this->text;
    }
    public function getReceiver(){
        return $this->receiver;
    }
    public function getName(){
        return $this->name;
    }
}
