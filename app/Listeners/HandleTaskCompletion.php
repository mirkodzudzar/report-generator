<?php

namespace App\Listeners;

use App\Events\AllTasksCompleted;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Notifications\CongratulationNotification;

class HandleTaskCompletion implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(AllTasksCompleted $event): void
    {
        $event->user->notify(new CongratulationNotification());
    }
}
