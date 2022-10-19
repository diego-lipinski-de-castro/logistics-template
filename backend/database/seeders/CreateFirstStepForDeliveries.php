<?php

namespace Database\Seeders;

use Ajthinking\LaravelPostgis\Geometries\Point;
use App\Models\Delivery;
use App\Models\Step;
use Illuminate\Database\Seeder;

class CreateFirstStepForDeliveries extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $deliveries = Delivery::with([
            'user',
            'user.address',
            'steps',
        ])->get();

        $deliveries->each(function ($delivery) {
            $step = $delivery->steps->first();

            $newStep = Step::create([
                'status' => 'PENDING',
    
                'delivery_id' => $delivery->id,
    
                'location' => new Point($delivery->user->address->position->getLat(), $delivery->user->address->position->getLng()),
                
                'street_number' => $delivery->user->address->street_number,
                'street_name' => $delivery->user->address->street_name,
                'district' => $delivery->user->address->district,
                'city' => $delivery->user->address->city,
                'state' => $delivery->user->address->state,

                'info' => $delivery->user->name,
            ]);

            $step->update([
                'prev_id' => $newStep->id,
            ]);
        });
    }
}
