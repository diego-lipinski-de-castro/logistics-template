<?php

namespace App\Jobs;

use App\Models\Delivery;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DispatchDelivery implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public $tries = 1;
    public $timeout = 30;
    public $failOnTimeout = true;

    private $delivery;
    private $force = false;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(int $deliveryId, bool $force = false)
    {
        $this->delivery = Delivery::find($deliveryId);
        $this->force = $force;
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

        if (! $this->force && $this->delivery->status !== 'WAITING') {
            return;
        }

        $this->delivery->update([
            'status' => 'PENDING',
            'pending_at' => now(),
        ]);

        if ($this->delivery->driver()->exists()) {
            NotifyDriver::dispatch($this->delivery->id, $this->delivery->driver->id);
        } else {
            FindDriver::dispatch($this->delivery->id);
        }
    }
}
