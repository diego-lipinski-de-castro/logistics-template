<?php

namespace Database\Factories;

use Ajthinking\LaravelPostgis\Geometries\Point;
use App\Models\Address;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Address>
 */
class AddressFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Address::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'position' => new Point(-26.482156, -49.07224),
            'street_number' => '1106',
            'street_name' => 'Rua Bernardo Dornbush',
            'district' => 'Vila Lalau',
            'city' => 'Jaraguá do Sul',
            'state' => 'Santa Catarina',
        ];
    }
}
