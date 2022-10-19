<?php

namespace App\Jobs;

use App\Models\Delivery;
use App\Notifications\WarnZeroDrivers;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class FindDriver implements ShouldQueue
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

        if ($this->delivery->driver()->exists()) {
            return;
        }

        if (! $this->force && $this->delivery->status !== 'PENDING') {
            return;
        }

        $cities = implode(',', $this->delivery->user->cities_ids);

        $drivers = function () {
            if (! $this->delivery->lastmile) {
                return '';
            }

            if (empty($this->delivery->user->drivers_ids)) {
                return '';
            }

            $ids = implode(',', $this->delivery->user->drivers_ids);

            return "AND drivers.id IN ($ids)";
        };

        $position = $this->delivery->steps[0]->location->toPair();

        $results = DB::select(<<<EOT
            SELECT
                drivers.id,
                st_distancesphere (
                    st_geomfromtext('POINT($position)', 4326),
                    driver_metadata. "location"::geometry
                ) AS distance,
                driver_metadata.cloud
            FROM
                drivers
            INNER JOIN
                driver_metadata
            ON
                drivers.id = driver_metadata. "driver_id"
            WHERE
                drivers.city_id IN ($cities)
            {$drivers()}
            AND
                drivers.status = 'APPROVED'
            AND
                driver_metadata. "location" IS NOT NULL
            AND
                driver_metadata. "location" IS NOT NULL
            AND NOT
                driver_metadata. "status" = 'OFFLINE'
            ORDER BY
                cloud ASC, array_position(array['ONLINE', 'BUSY']::varchar[], driver_metadata. "status"), distance ASC;
        EOT);

        $results = collect($results);

        if ($results->isEmpty()) {
            Notification::route('slack', config('services.slack.deliveries'))->notify(new WarnZeroDrivers($this->delivery));

            return;
        }

        $results->each(function ($driver, $key) {
            NotifyDriver::dispatch($this->delivery->id, $driver->id)
                ->delay(now()->addSeconds(30 * $key));
        });

        NotifyDrivers::dispatch($this->delivery->id)
            ->delay(now()->addSeconds(30 * $results->count()));

        CheckDeliveryStatus::dispatch($this->delivery->id)
            ->delay(now()->addSeconds((30 * $results->count()) + 60));
    }
}
