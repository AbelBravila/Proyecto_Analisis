<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use App\Models\TokenDispositivo;

class FirebaseService
{
    protected $messaging;

    public function __construct()
    {
        $factory = (new Factory)->withServiceAccount(
            storage_path('app\firebase\posmovil-37d5e-firebase-adminsdk-fbsvc-595e34fa9b.json')
        );

        $this->messaging = $factory->createMessaging();
    }

    public function sendNotificationToDevice(string $deviceToken, string $title, string $body)
    {
        $notification = Notification::create($title, $body);

        $message = CloudMessage::withTarget('token', $deviceToken)
            ->withNotification($notification);

        return $this->messaging->send($message);
    }

    public function sendPushToMultipleDevices(array $tokens, string $title, string $body)
    {
        $notification = Notification::create($title, $body);
        $message = CloudMessage::new()->withNotification($notification);

        $report = $this->messaging->sendMulticast($message, $tokens);

        foreach ($report->failures()->getItems() as $failure) {
            $invalidToken = $failure->target()->value();
            TokenDispositivo::where('TokenDispositivo', $invalidToken)
                ->update(['Estado' => 0]);
        }

        return $report;
    }
}
