<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;

class WarnError extends Notification
{
    use Queueable;

    public string $message;
    public $details;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(string $message, array $details = [])
    {
        $this->message = $message;
        $this->details = collect($details);
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['slack'];
    }

    /**
     * Get the Slack representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\SlackMessage
     */
    public function toSlack($notifiable)
    {
        $content = $this->details->map(function ($item, $key) {
            return "\n*{$key}*: {$item}";
        });

        return (new SlackMessage)
                    ->from('Logística')
                    ->to('#logistica-notificacoes-dev')
                    ->content("{$this->message}\n$content");
    }
}
