<?php

namespace App\Notifications;

use App\Models\Delivery;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;

class WarnZeroDrivers extends Notification
{
    use Queueable;

    public $delivery;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Delivery $delivery)
    {
        $this->delivery = $delivery;
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
        $url = route('developer.deliveries.show', $this->delivery->id);

        $city = $this->delivery->user->city->name;

        $user = $this->delivery->user->name;

        $dispatchedAt = $this->delivery->formatted_created_at;

        return (new SlackMessage)
                    ->from('Logística')
                    ->to('#logistica-notificacoes-entregas')
                    ->content("Nenhum entregador para realizar entrega.\nEntrega pendente em *{$city}*\nEstabelecimento: *{$user}*\nHora do disparo: *{$dispatchedAt}*\n<{$url}|#{$this->delivery->cid}>");
    }
}
