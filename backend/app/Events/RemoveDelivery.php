<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RemoveDelivery implements ShouldBroadcastNow
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public $cities;
    public $deliveryId;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(int $deliveryId, array $cities)
    {
        $this->deliveryId = $deliveryId;
        $this->cities = $cities;
    }

    public function broadcastOn()
    {
        $channels = [];

        foreach ($this->cities as $city) {
            array_push($channels, new Channel("deliveries.$city"));
        }

        return $channels;
    }

    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'remove';
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
