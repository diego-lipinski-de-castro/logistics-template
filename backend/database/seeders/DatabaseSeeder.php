<?php

namespace Database\Seeders;

use Ajthinking\LaravelPostgis\Geometries\LineString;
use Ajthinking\LaravelPostgis\Geometries\Point;
use Ajthinking\LaravelPostgis\Geometries\Polygon;
use App\Models\Delivery;
use App\Models\Step;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Admin::create([
            'name' => 'Admin',
            'email' => 'test@test.com',
            'password' => bcrypt('test'),
        ]);

        \App\Models\Developer::create([
            'name' => 'Developer',
            'email' => 'test@test.com',
            'password' => bcrypt('test'),
        ]);

        $state = \App\Models\State::create([
            'name' => 'Santa Catarina',
        ]);

        $city = \App\Models\City::create([
            'name' => 'Jaraguá do Sul',
            'state_id' => $state->id,
        ]);

        $city2 = \App\Models\City::create([
            'name' => 'Jaraguá do Sul',
            'state_id' => $state->id,
        ]);

        \App\Models\City::create([
            'name' => 'Joinville',
            'state_id' => $state->id,
        ]);

        \App\Models\Driver::create([
            'first_name' => 'Driver',
            'last_name' => '01',
            'phone' => '+5547991532336',
            'city_id' => $city->id,
            'status' => 'APPROVED',
            'registered' => true,
        ]);

        \App\Models\Driver::create([
            'first_name' => 'Driver',
            'last_name' => '02',
            'phone' => '+5548991532336',
            'city_id' => $city->id,
        ]);

        \App\Models\Driver::create([
            'first_name' => 'Driver',
            'last_name' => '03',
            'phone' => '+5549991532336',
            'city_id' => $city2->id,
        ]);

        $user = \App\Models\User::create([
            'name' => 'User',
            'email' => 'test@test.com',
            'password' => bcrypt('test'),
            'city_id' => $city->id,
            'area' => new Polygon([
                new LineString([
                    new Point(-26.476965, -49.080401),
                    new Point(-26.475335, -49.051333),
                    new Point(-26.488724, -49.050232),
                    new Point(-26.501401, -49.062264),
                    new Point(-26.501118, -49.09021),
                    new Point(-26.476965, -49.080401),
                ]),
            ]),

            'radiuses' => json_decode('[{"rad": 1, "paid": 100, "time": 1, "charged": 200}, {"rad": 2, "paid": 200, "time": 2, "charged": 300}, {"rad": 3, "paid": 300, "time": 3, "charged": 400}]'),
        ]);

        $user->address()->create([
            'position' => new Point(-26.482156, -49.07224),
            'street_number' => '1106',
            'street_name' => 'Rua Bernardo Dornbush',
            'district' => 'Vila Lalau',
            'city' => 'Jaraguá do Sul',
            'state' => 'Santa Catarina',
        ]);

        $user->cities()->save($city);

        $delivery = Delivery::create([
            'user_id' => $user->id,
            'driver_id' => null,
        
            'rad' => 2,
            'time' => 5,
            'paid' => 500,
            'charged' => 700,
            
            'return_fee_paid' => null,
            'return_fee_charged' => null,
        ]);

        $delivery2 = Delivery::create([
            'user_id' => $user->id,
            'driver_id' => null,
        
            'rad' => 2,
            'time' => 5,
            'paid' => 500,
            'charged' => 700,
            
            'return_fee_paid' => 150,
            'return_fee_charged' => 150,
        ]);

        $firstStep = Step::create([
            'status' => 'PENDING',

            'delivery_id' => $delivery->id,

            'location' => new Point($user->address->position->getLat(), $user->address->position->getLng()),
            
            'street_number' => $user->address->street_number,
            'street_name' => $user->address->street_name,
            'district' => $user->address->district,
            'city' => $user->address->city,
            'state' => $user->address->state,
        ]);

        Step::create([
            'status' => 'PENDING',

            'prev_id' => $firstStep->id,

            'delivery_id' => $delivery->id,

            'location' => new Point(-26.4905121, -49.0613127),
            
            'street_number' => '82',
            'street_name' => 'Rua Luis Alves',
            'district' => 'Ilha da Figueira',
            'city' => 'Jaragua do Sul',
            'state' => 'SC',
        ]);

        $firstStep = Step::create([
            'status' => 'PENDING',

            'delivery_id' => $delivery2->id,

            'location' => new Point($user->address->position->getLat(), $user->address->position->getLng()),
            
            'street_number' => $user->address->street_number,
            'street_name' => $user->address->street_name,
            'district' => $user->address->district,
            'city' => $user->address->city,
            'state' => $user->address->state,
        ]);

        Step::create([
            'status' => 'PENDING',

            'prev_id' => $firstStep->id,

            'delivery_id' => $delivery2->id,

            'location' => new Point(-26.4905121, -49.0613127),
            
            'street_number' => '82',
            'street_name' => 'Rua Luis Alves',
            'district' => 'Ilha da Figueira',
            'city' => 'Jaragua do Sul',
            'state' => 'SC',
        ]);

        $user2 = \App\Models\User::create([
            'name' => 'User 2',
            'email' => 'test2@test.com',
            'password' => bcrypt('test'),
            'city_id' => $city->id,
            'area' => new Polygon([
                new LineString([
                    new Point(-26.476965, -49.080401),
                    new Point(-26.475335, -49.051333),
                    new Point(-26.488724, -49.050232),
                    new Point(-26.501401, -49.062264),
                    new Point(-26.501118, -49.09021),
                    new Point(-26.476965, -49.080401),
                ]),
            ]),

            'radiuses' => json_decode('[{"rad": 1, "paid": 100, "time": 1, "charged": 200}, {"rad": 2, "paid": 200, "time": 2, "charged": 300}, {"rad": 3, "paid": 300, "time": 3, "charged": 400}]'),
        ]);

        $user2->address()->create([
            'position' => new Point(-26.482156, -49.07224),
            'street_number' => '1106',
            'street_name' => 'Rua Bernardo Dornbush',
            'district' => 'Vila Lalau',
            'city' => 'Jaraguá do Sul',
            'state' => 'Santa Catarina',
        ]);

        $user2->cities()->save($city);
    }
}
