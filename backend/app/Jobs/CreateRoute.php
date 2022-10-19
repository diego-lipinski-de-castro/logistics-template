<?php

namespace App\Jobs;

use Ajthinking\LaravelPostgis\Geometries\LineString;
use Ajthinking\LaravelPostgis\Geometries\Point;
use App\Models\Delivery;
use App\Models\Route;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateRoute implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public $tries = 2;
    public $timeout = 120;
    public $failOnTimeout = true;
    public $backoff = 300;

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

        if ($this->delivery->route()->exists()) {
            return;
        }

        if (! $this->delivery->driver()->exists()) {
            return;
        }

        $startTime = $this->delivery->accepted_at;
        $endTime = $this->delivery->delivered_at;

        if (blank($startTime) || blank($endTime)) {
            return;
        }

        $audits = $this->delivery
            ->driver
            ->metadata
            ->audits()
            ->where('event', 'updated')
            ->where('created_at', '>=', $startTime)
            ->where('created_at', '<=', $endTime)
            // ->whereJsonContains('old_values', 'location')
            // ->whereJsonContains('new_values', 'location')
            // ->whereNotNull('new_values->location')
            ->get();

        $points = collect();

        $audits->each(function ($audit) use ($points) {
            if (isset($audit->new_values['location'])) {
                $coords = $audit->new_values['location']['coordinates'];

                $points->push(new Point($coords[0], $coords[1]));
            }
        });

        if ($points->count() < 2) {
            return;
        }

        $lineString = new LineString($points->toArray());

        Route::create([
            'delivery_id' => $this->delivery->id,
            'route' => $lineString,
        ]);
    }

    /**
    * Get the tags that should be assigned to the job.
    *
    * @return array
    */
    public function tags()
    {
        return ['create-route', 'delivery:' . $this->delivery->id];
    }
}
