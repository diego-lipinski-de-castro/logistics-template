<?php

namespace Database\Factories;

use App\Models\Driver;
use App\Models\DriverMetadata;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DriverMetadata>
 */
class DriverMetadataFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = DriverMetadata::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'driver_id' => Driver::factory(),

            'cloud' => false,

            'status' => $this->faker->randomElement(['ONLINE', 'OFFLINE', 'BUSY']),

            'location' => null,

            'mode' => $this->faker->randomElement(['BIKE', 'E_BIKE', 'MOTO', 'CAR', 'VAN', 'TRUCK']),
            'bag' => $this->faker->randomElement(['BAG_45', 'BAG_55', 'BAG_89', 'BAG', 'CAR', 'OPEN_VAN', 'CLOSED_VAN', 'TRUCK']),
        ];
    }
}
