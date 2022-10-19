<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Log;

class LogScheduledBackgroundTaskFinished
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
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        // Log::debug(print_r($event, true));
    }
}
