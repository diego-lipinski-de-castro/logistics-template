<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;

class ClosingReportCompleted extends Notification
{
    use Queueable;

    public $userId;
    public $filename;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(int $userId, string $filename)
    {
        $this->userId = $userId;
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
        $url = route('developer.users.reports.download', [
            'user' => $this->userId,
            'filename' => $this->filename,
        ]);

        return (new SlackMessage)
                    ->from('Logística')
                    ->to('#logistica-notificacoes-relatorios')
                    ->content("Novo relatório de fechamento exportado\n<{$url}|{$this->filename}>");
    }
}
