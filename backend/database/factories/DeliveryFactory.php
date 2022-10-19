<?php

namespace Database\Factories;

use App\Models\Delivery;
use App\Models\Driver;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Delivery>
 */
class DeliveryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Delivery::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'status' => $this->faker->randomElement(['WAITING', 'PENDING', 'ACCEPTED', 'COLLECTING', 'DELIVERING', 'CONFIRMED', 'COMPLETED', 'RETURNING', 'CANCELED', 'NOT_DELIVERED']),

            'user_id' => User::factory(),
            'driver_id' => Driver::factory(),

            'rad' => 1,
            'time' => 20,
            'paid' => 10,
            'charged' => 10,

            'return_fee' => null,

            'receipt_required' => false,

            'private_text' => $this->faker->paragraph(1),
            'public_text' => $this->faker->paragraph(1),

            'customer_name' => $this->faker->name,
            'customer_phone' => $this->faker->phoneNumber,

            'pending_at' => null,
            'accepted_at' => null,
            'delivered_at' => null,
            'elapsed_time' => null,

            'visible' => true,
        ];
    }
}
