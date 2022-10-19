<?php

namespace App\Providers;

use App\Listeners\LogScheduledBackgroundTaskFinished;
use App\Listeners\LogScheduledTaskFailed;
use App\Listeners\LogScheduledTaskFinished;
use App\Listeners\LogScheduledTaskSkipped;
use App\Listeners\LogScheduledTaskStarting;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Console\Events\ScheduledBackgroundTaskFinished;
use Illuminate\Console\Events\ScheduledTaskFailed;
use Illuminate\Console\Events\ScheduledTaskFinished;
use Illuminate\Console\Events\ScheduledTaskSkipped;
use Illuminate\Console\Events\ScheduledTaskStarting;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        // Registered::class => [
        //     SendEmailVerificationNotification::class,
        // ],

        ScheduledTaskStarting::class => [
            LogScheduledTaskStarting::class,
        ],
    
        ScheduledTaskFinished::class => [
            LogScheduledTaskFinished::class,
        ],
    
        ScheduledBackgroundTaskFinished::class => [
            LogScheduledBackgroundTaskFinished::class,
        ],
    
        ScheduledTaskSkipped::class => [
            LogScheduledTaskSkipped::class,
        ],
    
        ScheduledTaskFailed::class => [
            LogScheduledTaskFailed::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
