<?php

namespace App\Listeners;

use App\Events\CheckUpdateEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

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
    public function handle(CheckUpdateEvent $event)
    {
        //
    }
}
