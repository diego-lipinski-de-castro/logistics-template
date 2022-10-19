<?php

namespace Database\Seeders;

use App\Models\Driver;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class FixDriverFullName extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $drivers = Driver::all();

        $drivers->each(function ($driver) {
            if (blank($driver->full_name) && ! blank($driver->first_name)) {
                $driver->update([
                    'full_name' => Str::of("{$driver->first_name} {$driver->last_name}")->trim(),
                ]);
            }
        });
    }
}
