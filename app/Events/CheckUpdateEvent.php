<?php

namespace App\Events;
use App\Check;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class CheckUpdateEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $check;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Check $check, $name, $type, $receiver)
    {
        $this->check = $check;
        $this->name = $name;
        $this->type = $type;
        $this->receiver = $receiver;
    } 

    public function getCheck(){
        return $this->check;
    }
    public function getName(){
        return $this->name;
    }
    public function getType(){
        return $this->type;
    }
    public function getReceiver(){
        return $this->receiver;
    }
}
