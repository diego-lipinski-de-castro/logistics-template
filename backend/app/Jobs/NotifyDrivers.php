<?php

namespace App\Jobs;

use App\Events\PendingDelivery;
use App\Events\RemoveDelivery;
use App\Models\Delivery;
use App\Models\Driver;
use App\Models\Shot;
use App\Notifications\WarnZeroDrivers;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Notification;

class NotifyDrivers implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public $tries = 1;
    public $timeout = 60;
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

        if (! $this->force && $this->delivery->status !== 'PENDING') {
            return;
        }

        if ($this->delivery->driver()->exists()) {
            return;
        }

        $cities = $this->delivery->user->cities_ids;

        $drivers = Driver::query()
            ->ofCity($cities)
            ->ofStatus('ONLINE')
            ->get();

        if ($drivers->isEmpty()) {
            Notification::route('slack', config('services.slack.deliveries'))->notify(new WarnZeroDrivers($this->delivery));
            
            return;
        }

        event(new RemoveDelivery($this->delivery->id, $cities));
        event(new PendingDelivery($this->delivery->id, $cities));

        $shots = collect([]);

        $now = now();

        $drivers->each(function ($driver) use ($now, $shots) {
            $shots->push([
                'delivery_id' => $this->delivery->id,
                'driver_id' => $driver->id,
                'action' => 'SENT',
                'created_at' => $now,
            ]);
        });

        Shot::insert($shots->toArray());
    }
}
