<?php

namespace Database\Seeders;

use App\Models\Delivery;
use Illuminate\Database\Seeder;

class UpdateReturnFees extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $deliveries = Delivery::all();

        $deliveries->each(function ($delivery) {
            $delivery->user->update([
                'return_fee_paid' => $delivery->user->return_fee_charged,
            ]);

            $delivery->update([
                'return_fee_paid' => $delivery->user->return_fee_paid,
                'return_fee_charged' => $delivery->user->return_fee_charged,
            ]);
        });
    }
}
