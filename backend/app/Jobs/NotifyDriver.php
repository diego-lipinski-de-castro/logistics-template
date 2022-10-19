<?php

namespace App\Jobs;

use App\Events\PendingDeliverySingle;
use App\Events\RemoveDelivery;
use App\Models\Delivery;
use App\Models\Driver;
use App\Models\Shot;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NotifyDriver implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public $tries = 1;
    public $timeout = 30;
    public $failOnTimeout = true;

    private $delivery;
    private $driver;

    private $force = false;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(int $deliveryId, int $driverId, bool $force = false)
    {
        $this->delivery = Delivery::find($deliveryId);
        $this->driver = Driver::find($driverId);

        $this->force = $force;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (blank($this->delivery) || blank($this->driver)) {
            return;
        }

        if (! $this->force && $this->delivery->status !== 'PENDING') {
            return;
        }

        if (! $this->delivery->driver()->exists() || $this->delivery->driver->id === $this->driver->id) {
            event(new RemoveDelivery($this->delivery->id, $this->delivery->user->cities_ids));

            event(new PendingDeliverySingle($this->delivery->id, $this->driver->pub_id));

            Shot::create([
                'delivery_id' => $this->delivery->id,
                'driver_id' => $this->driver->id,
                'action' => 'SENT',
                'created_at' => now(),
            ]);
        }
    }
}
