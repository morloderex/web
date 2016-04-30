<?php

namespace App\Providers;

use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'auth.login' => [
            'App\Listeners\UserEventListener@signedIn',
        ],
        'auth.logout' => [
            'App\Listeners\UserEventListener@signedOut',
        ],
        'App\Events\User\SignedUp' => [
            'App\Listeners\UserEventListener@signedUp',
        ],
        'App\Events\User\Destroyed'  =>  [
            'App\Listeners\UserEventListener@destroyed'
        ],
        'App\Events\Role\ChangedIPNotification'  =>  [
            'App\Listeners\UserEventListener@ipChanged'
        ],
        'App\Events\Role\Granted'  =>  [
            'App\Listeners\UserEventListener@promoted'
        ],
        'App\Events\Role\Revoked'  =>  [
            'App\Listeners\UserEventListener@demoted'
        ],
    ];

    /**
     * Register any other events for your application.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher  $events
     * @return void
     */
    public function boot(DispatcherContract $events)
    {
        parent::boot($events);

        //
    }
}
