<?php

namespace App\Providers;

use App\Listeners\SendSmsEventListener;
use App\Events\SendSmsEvent;
use App\Events\VerifyUserEmail;
use App\Listeners\VerifyUserEmailListener;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [

        SendSmsEvent::class => [
            SendSmsEventListener::class,
        ],
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        VerifyUserEmail::class => [
            VerifyUserEmailListener::class,
        ]
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
