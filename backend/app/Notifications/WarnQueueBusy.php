<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;

class WarnQueueBusy extends Notification
{
    use Queueable;

    public $connection;
    public $queue;
    public $size;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($connection, $queue, $size)
    {
        $this->connection = $connection;
        $this->queue = $queue;
        $this->size = $size;
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
        return (new SlackMessage)
                    ->from('Logística')
                    ->to('#logistica-notificacoes-dev')
                    ->content("Aviso de filas cheias!\nConexão: {$this->connection}\nFila: {$this->queue}\nTamanho: {$this->size}");
    }
}
