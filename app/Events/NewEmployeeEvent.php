<?php

namespace App\Events;

use App\Employee;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class NewEmployeeEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $Employee;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Employee $e, $name)
    {
        $this->Employee = $e;
        $this->name = $name;
    }

    public function getEmployee(){
        return $this->Employee;
    }

    public function getName(){
        return $this->name;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
