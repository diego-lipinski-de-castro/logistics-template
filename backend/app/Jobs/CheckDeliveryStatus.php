<?php

namespace App\Jobs;

use App\Models\Delivery;
use App\Notifications\WarnDelivery;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Notification;

class CheckDeliveryStatus implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public $tries = 1;
    public $timeout = 60;
    public $failOnTimeout = true;

    private $delivery;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(int $deliveryId)
    {
        $this->delivery = Delivery::find($deliveryId);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (blank($this->delivery)) {
            return;
        }

        if ($this->delivery->status !== 'PENDING') {
            return;
        }

        Notification::route('slack', config('services.slack.deliveries'))->notify(new WarnDelivery($this->delivery));
    }
}
