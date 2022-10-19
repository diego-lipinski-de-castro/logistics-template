<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\State;
use GuzzleHttp\Client;
use Illuminate\Database\Seeder;

class CitiesFromJson extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $response = (new Client())->get('https://gist.githubusercontent.com/letanure/3012978/raw/78474bd9db11e87de65a9d3c9fc4452458dc8a68/estados-cidades.json');

        $data = json_decode($response->getBody()->getContents());

        foreach ($data->estados as $estado) {
            $state = State::firstOrCreate([
                'name' => "{$estado->sigla} - {$estado->nome}",
            ]);

            foreach ($estado->cidades as $cidade) {
                City::firstOrCreate([
                    'state_id' => $state->id,
                    'name' => $cidade,
                ]);
            }
        }
    }
}
