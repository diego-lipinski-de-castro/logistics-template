<?php

namespace App\Jobs\Reports\Closing;

use App\Notifications\ClosingReportCompleted;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Notification;

class NotifyClosingReportCompleted implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    private $userId;
    private $filename;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(int $userId, string $filename)
    {
        $this->userId = $userId;
        $this->filename = $filename;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Notification::route('slack', config('services.slack.reports'))->notify(new ClosingReportCompleted($this->userId, $this->filename));
    }
}
