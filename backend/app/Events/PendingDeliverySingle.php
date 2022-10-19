<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PendingDeliverySingle implements ShouldBroadcastNow
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public $driverId;
    public $deliveryId;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(int $deliveryId, string $driverId)
    {
        $this->deliveryId = $deliveryId;
        $this->driverId = $driverId;
    }

    public function broadcastOn()
    {
        return new Channel("driver.{$this->driverId}");
    }

    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'pending';
    }

    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith()
    {
        return ['delivery_id' => $this->deliveryId];
    }
}
