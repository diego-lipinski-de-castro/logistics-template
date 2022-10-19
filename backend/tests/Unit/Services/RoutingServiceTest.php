<?php

namespace Tests\Unit\Services;

use App\Services\RoutingService;
use Tests\TestCase;

class RoutingServiceTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_get_route()
    {
        $result = (new RoutingService())->getRoute(
            '-26.482156,-49.07224',
            '-26.4897612,-49.0599408'
        );

        $this->assertIsObject($result);

        $this->assertObjectHasAttribute('ok', $result);
        $this->assertObjectHasAttribute('data', $result);

        if (! $result->ok) {
            $this->assertNull($result->data);
            
            return;
        }

        $this->assertNotNull($result->data);

        $this->assertObjectHasAttribute('legs', $result->data);
        $this->assertIsArray($result->data->legs);
        $this->assertNotEmpty($result->data);

        $this->assertObjectHasAttribute('distance', $result->data->legs[0]);
        $this->assertObjectHasAttribute('value', $result->data->legs[0]->distance);
        $this->assertObjectHasAttribute('text', $result->data->legs[0]->distance);
        $this->assertIsInt($result->data->legs[0]->distance->value);
        $this->assertIsString($result->data->legs[0]->distance->text);

        $this->assertObjectHasAttribute('duration', $result->data->legs[0]);
        $this->assertObjectHasAttribute('value', $result->data->legs[0]->duration);
        $this->assertObjectHasAttribute('text', $result->data->legs[0]->duration);
        $this->assertIsInt($result->data->legs[0]->duration->value);
        $this->assertIsString($result->data->legs[0]->duration->text);

        $this->assertObjectHasAttribute('overview_polyline', $result->data);
        $this->assertObjectHasAttribute('points', $result->data->overview_polyline);
        $this->assertIsString($result->data->overview_polyline->points);

        $this->assertObjectHasAttribute('warnings', $result->data);
        $this->assertIsArray($result->data->warnings);
    }
}
