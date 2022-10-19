<?php

namespace Database\Factories;

use App\Models\City;
use App\Models\Driver;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Driver>
 */
class DriverFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Driver::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $firstName = $this->faker->firstName;
        $lastName = $this->faker->lastName;

        return [
            'city_id' => City::factory(),

            'first_name' => $firstName,
            'last_name' => $lastName,

            'full_name' => "$firstName $lastName",

            'email' => $this->faker->email,
            'phone' => $this->faker->phoneNumber,

            'birthday' => null,
            'cpf' => null,

            'cnh' => null,
            'vehicle_name' => null,
            'vehicle_plate' => null,

            'pix' => null,

            'registered' => true,

            'status' => 'APPROVED',
        ];
    }
}
