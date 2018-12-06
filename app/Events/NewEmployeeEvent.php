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
use App\User;

class NewEmployeeEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $Employee;
    public $Admin;
    public $reason;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Employee $e, User $a,$reason)
    {
        $this->Employee = $e;
        $this->Admin = $a;
        $this->Reason = $reason;
    }

    public function getEmployee(){
        return $this->Employee;
    }

    public function getAdmin(){
        return $this->Admin;
    }

    public function getReason(){
        return $this->Reason;
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
