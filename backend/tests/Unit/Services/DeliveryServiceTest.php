<?php

namespace Tests\Unit\Services;

use Ajthinking\LaravelPostgis\Geometries\LineString;
use Ajthinking\LaravelPostgis\Geometries\Point;
use Ajthinking\LaravelPostgis\Geometries\Polygon;
use App\Models\City;
use App\Models\State;
use App\Models\User;
use App\Services\DeliveryService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeliveryServiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_check_invalid_user()
    {
        $result = (new DeliveryService())->check(
            1,
            [],
            false
        );

        $this->assertNull($result);
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_check_invalid_destination()
    {
        $user = $this->createUser();

        $result = (new DeliveryService())->check(
            $user->id,
            [],
            false
        );

        $this->assertNull($result);

        $result = (new DeliveryService())->check(
            $user->id,
            [1,2,3],
            false
        );

        $this->assertNull($result);
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_check_outside_area_destination()
    {
        $user = $this->createUser();

        $destination = [-26.4796672,-49.0873319];

        $result = (new DeliveryService())->check(
            $user->id,
            $destination,
            false
        );

        $this->assertNull($result);
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_check_line()
    {
        $user = $this->createUser();

        $destination = [-26.4897612,-49.0599408];

        $result = (new DeliveryService())->check(
            $user->id,
            $destination,
            false
        );

        $this->assertNotNull($result);

        $this->assertSame([
            'rad' => 2,
            'time' => 2,
            'charged' => 300,
            'formatted_charged' => 'R$ 3,00',
        ], $result);
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_check_line_return_paid()
    {
        $user = $this->createUser();

        $destination = [-26.4897612,-49.0599408];

        $result = (new DeliveryService())->check(
            $user->id,
            $destination,
            true
        );

        $this->assertNotNull($result);

        $this->assertSame([
            'rad' => 2,
            'paid' => 200,
            'time' => 2,
            'charged' => 300,
            'formatted_paid' => 'R$ 2,00',
            'formatted_charged' => 'R$ 3,00',
        ], $result);
    }

    private function createUser(string $chargeStyle = 'LINE')
    {
        $state = State::create([
            'name' => 'Santa Catarina',
        ]);

        $city = City::create([
            'name' => 'Jaraguá do Sul',
            'state_id' => $state->id,
        ]);

        $user = User::create([
            'city_id' => $city->id,
            'name' => 'User',
            'email' => 'user@test.com',
            'password' => bcrypt('test'),

            'charge_style' => $chargeStyle,

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

        $user->cities()->attach([$city->id]);

        $user->address()->create([
            'position' => new Point(-26.482156, -49.07224),
            'street_number' => '1106',
            'street_name' => 'Rua Bernardo Dornbush',
            'district' => 'Vila Lalau',
            'city' => 'Jaraguá do Sul',
            'state' => 'Santa Catarina',
        ]);

        return $user;
    }
}
