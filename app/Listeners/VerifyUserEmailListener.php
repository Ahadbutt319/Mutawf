<?php

namespace App\Listeners;

use App\Mail\EmailVerificationMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class VerifyUserEmailListener
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
    public function handle(object $event): void
    {
        $user = $event->user;
        $code = $event->code;

        // Send the email to the user with the verification code
        Mail::to($user->email)->send(new EmailVerificationMail($user->name, $code));
    }
}
