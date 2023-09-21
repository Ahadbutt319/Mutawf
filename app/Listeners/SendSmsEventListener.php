<?php

namespace App\Listeners;

use App\Services\SendSmsService;
use App\Events\SendSmsEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendSmsEventListener
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
    public function handle(object $event)
{
    try {

        $user = $event->user;
        $code = $event->code;
        SendSmsService::sendSMS($code);
    } catch (\Exception $e) {
    }
}
}
