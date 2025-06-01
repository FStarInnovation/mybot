<?php

namespace App\Services;

use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription as WebPushSubscription;
use App\Models\PushSubscription as PushSubscriptionModel;
use Illuminate\Support\Facades\Log;

class WebPushService
{
    protected WebPush $webPush;
    protected array $defaultOptions = [];

    public function __construct()
    {
        $vapid = config('webpush.vapid');
        $this->defaultOptions = config('webpush.default', []);
        
        $this->webPush = new WebPush([
            'VAPID' => [
                'subject' => $vapid['subject'],
                'publicKey' => $vapid['public_key'],
                'privateKey' => $vapid['private_key'],
            ]
        ]);
    }

    /**
     * Send a notification to a specific subscription
     */
    public function sendNotification(
        PushSubscriptionModel $subscription,
        string $title,
        string $message,
        string $url = '/',
        array $options = []
    ): array {
        $payload = json_encode([
            'title' => $title,
            'body' => $message,
            'url' => $url,
            'icon' => $options['icon'] ?? '/images/icon-192x192.png',
            'badge' => $options['badge'] ?? '/images/badge-72x72.png',
        ]);

        $pushSubscription = new WebPushSubscription(
            $subscription->endpoint,
            $subscription->public_key,
            $subscription->auth_token
        );

        $this->webPush->queueNotification($pushSubscription, $payload, [
            'TTL' => $options['ttl'] ?? $this->defaultOptions['TTL'] ?? 2419200,
            'urgency' => $options['urgency'] ?? $this->defaultOptions['urgency'] ?? 'normal',
            'topic' => $options['topic'] ?? $this->defaultOptions['topic'] ?? 'farmabot',
        ]);

        return $this->flush();
    }

    /**
     * Send notification to all active subscriptions
     */
    public function broadcast(
        string $title,
        string $message,
        string $url = '/',
        array $options = []
    ): array {
        $subscriptions = PushSubscriptionModel::all();
        $results = [];

        foreach ($subscriptions as $subscription) {
            $results[] = $this->sendNotification($subscription, $title, $message, $url, $options);
        }

        return $results;
    }

    /**
     * Alias for broadcasting notifications
     */
    public function broadcastNotification(string $title, string $body): void
    {
        $notification = [
            'title' => $title,
            'body' => $body,
            'icon' => '/favicon.ico',
            'vibrate' => [100, 50, 100],
        ];

        $subscriptions = PushSubscriptionModel::all();
        
        foreach ($subscriptions as $subscription) {
            $webPushSubscription = new WebPushSubscription(
                $subscription->endpoint,
                $subscription->public_key,
                $subscription->auth_token
            );
            
            $this->webPush->queueNotification(
                $webPushSubscription,
                json_encode($notification)
            );
        }

        $this->webPush->flush();
    }

    /**
     * Process the notification queue
     */
    protected function flush(): array
    {
        $results = [];
        
        foreach ($this->webPush->flush() as $report) {
            $endpoint = $report->getRequest()->getUri()->__toString();
            
            if ($report->isSuccess()) {
                Log::info("Push notification sent to {$endpoint}");
            } else {
                Log::error("Push notification failed to {$endpoint}", [
                    'statusCode' => $report->getResponse()?->getStatusCode(),
                    'reason' => $report->getReason(),
                ]);
                
                // Remove invalid subscriptions
                if ($report->isSubscriptionExpired()) {
                    PushSubscriptionModel::where('endpoint', $endpoint)->delete();
                    Log::info("Expired subscription removed: {$endpoint}");
                }
            }
            
            $results[] = [
                'endpoint' => $endpoint,
                'success' => $report->isSuccess(),
                'reason' => $report->getReason(),
            ];
        }
        
        return $results;
    }
}
