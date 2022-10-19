<?php

namespace Tests\Unit\Models;

use App\Models\City;
use App\Models\Driver;
use App\Models\DriverMetadata;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DriverTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_has_city()
    {
        $driver = Driver::factory()->create();

        $this->assertInstanceOf(City::class, $driver->city);
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_has_metadata()
    {
        $driver = Driver::factory()->create();

        $this->assertInstanceOf(DriverMetadata::class, $driver->metadata);
    }
}
