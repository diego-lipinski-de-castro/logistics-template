<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;

class ReportCompleted extends Notification
{
    use Queueable;

    public $filename;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(string $filename)
    {
        $this->filename = $filename;
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
        $url = route('developer.reports.download', [
            'filename' => $this->filename,
        ]);

        return (new SlackMessage)
                    ->from('Logística')
                    ->to('#logistica-notificacoes-relatorios')
                    ->content("Novo relatório exportado\n<{$url}|{$this->filename}>");
    }
}
