<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\CheckUpdateEvent' => [
            'App\Listeners\SendNotification@CheckUpdate',
            'App\Listeners\SendEmail@handleCheck',
        ],
        'App\Events\ChecklistUpdateEvent' => [
            'App\Listeners\SendNotification@ChecklistUpdate',
            'App\Listeners\SendEmail@handleChecklist',
        ],
        'App\Events\NewEmployeeEvent' =>[
            'App\Listeners\SendEmail@handleEmployee'
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
