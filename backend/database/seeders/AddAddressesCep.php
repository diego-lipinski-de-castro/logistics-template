<?php

namespace Database\Seeders;

use App\Helper;
use App\Models\Address;
use Http;
use Illuminate\Database\Seeder;

class AddAddressesCep extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $addresses = Address::all();

        $addresses->each(function ($address) {
            $lat = $address->position->getLat();
            $lng = $address->position->getLng();

            $response = Http::get("https://maps.google.com/maps/api/geocode/json?address={$lat},{$lng}&sensor=false&key=");

            $data = json_decode($response->getBody()->getContents());

            if ($data->status == 'OK') {
                $components = collect($data->results[0]->address_components);

                foreach ($components as $cs) {
                    if (in_array('postal_code', $cs->types)) {
                        $address->update([
                            'cep' => Helper::extractNumbersFromString($cs->long_name),
                        ]);
                    }
                }
            } else {
                dump("{$address->id} STATUS: " . $data->status);
            }
        });
    }
}
