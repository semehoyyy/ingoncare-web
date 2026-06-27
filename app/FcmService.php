<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class FcmService
{
    public static function send(string $fcmToken, string $title, string $body, array $data = []): void
    {
        try {
            $factory = (new Factory)->withServiceAccount(storage_path('app/firebase-service-account.json'));
            $messaging = $factory->createMessaging();

            $message = CloudMessage::withTarget('token', $fcmToken)
                ->withNotification(Notification::create($title, $body))
                ->withData($data);

            $messaging->send($message);
        } catch (\Exception $e) {
            // Silent — jangan sampai gagal kirim notif merusak flow utama
            \Log::warning('FCM send failed: ' . $e->getMessage());
        }
    }

    public static function sendToUser(\App\Models\User $user, string $title, string $body, array $data = []): void
    {
        if ($user->fcm_token) {
            self::send($user->fcm_token, $title, $body, $data);
        }
    }
}