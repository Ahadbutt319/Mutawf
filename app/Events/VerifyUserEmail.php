<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;

class VerifyUserEmail
{
    use SerializesModels;

    /**
     * The authenticated user.
     *
     * @var \Illuminate\Contracts\Auth\Authenticatable
     */
    public $user;
    public $code;

    /**
     * Create a new event instance.
     */
    public function __construct($user, $code)
    {
        $this->user = $user;
        $this->code = $code;
    }
}