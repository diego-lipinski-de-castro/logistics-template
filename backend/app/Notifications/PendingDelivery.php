<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

// use NotificationChannels\OneSignal\OneSignalChannel;
// use NotificationChannels\OneSignal\OneSignalMessage;

class PendingDelivery extends Notification
{
    use Queueable;

    public $deliveryId;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(int $deliveryId)
    {
        $this->deliveryId = $deliveryId;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [
            // OneSignalChannel::class
        ];
    }

    // public function toOneSignal($notifiable): OneSignalMessage
    // {
    //     return OneSignalMessage::create()
    //         ->setSubject("Nova entrega disponível!")
    //         ->setBody("Nova entrega disponível!");
    // }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'delivery_id' => $this->deliveryId,
        ];
    }
}
