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
    public function __construct(Check $check, $text, $name)
    {
        $this->check = $check;
        $this->text = $text;
        $this->name = $name;
    }

    public function getCheck(){
        return $this->check;
    }

    public function getName(){
        return $this->name;
    }
    public function getText(){
        return $this->text;
    }
}
