<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

class SendSmsController extends Controller
{
    public function sendSMS()
    {
        $basic = new \Vonage\Client\Credentials\Basic("95196a67", "TSxpESrN0xsfLsyr");
        $client = new \Vonage\Client($basic);

        $response = $client->sms()->send(
            new \Vonage\SMS\Message\SMS("923075108200", 'BRAND_NAME', 'A text message sent using the Nexmo SMS API')
        );

        $message = $response->current();

        if ($message->getStatus() == 0) {
            echo "The message was sent successfully\n";
        } else {
            echo "The message failed with status: " . $message->getStatus() . "\n";
        }
    }

}
