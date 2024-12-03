<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;

class PushNotificationService
{
    protected $messaging;

    public function __construct()
    {
        $firebase = (new Factory)->withServiceAccount(base_path('config/firebase_config.json'));
        $this->messaging = $firebase->createMessaging();
    }

    /**
     * Send a push notification
     */
    public function sendNotification(string $title, string $body, array $data = [])
    {
        $message = CloudMessage::withTarget('token', env('FIREBASE_TOKEN'))
            ->withNotification(compact('title', 'body'))
            ->withData($data);

        $this->messaging->send($message);
    }
}
