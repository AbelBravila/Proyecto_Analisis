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
        $firebaseConfig = [
            'type' => 'service_account',
            'project_id' => env('FIREBASE_PROJECT_ID'),
            'private_key_id' => env('FIREBASE_PRIVATE_KEY_ID', ''), 
            'private_key' => str_replace('\\n', "\n", env('FIREBASE_PRIVATE_KEY')),
            'client_email' => env('FIREBASE_CLIENT_EMAIL'),
            'client_id' => env('FIREBASE_CLIENT_ID', ''),
            'auth_uri' => 'https://accounts.google.com/o/oauth2/auth',
            'token_uri' => 'https://oauth2.googleapis.com/token',
            'auth_provider_x509_cert_url' => 'https://www.googleapis.com/oauth2/v1/certs',
            'client_x509_cert_url' => env('FIREBASE_CLIENT_CERT_URL', ''),
        ];

        $factory = (new Factory)->withServiceAccount($firebaseConfig);
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
