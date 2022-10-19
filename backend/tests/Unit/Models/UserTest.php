<?php

namespace Tests\Unit\Models;

use App\Models\Address;
use App\Models\City;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_has_city()
    {
        $user = User::factory()->create();

        $this->assertInstanceOf(City::class, $user->city);
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_has_cities()
    {
        $cities = City::factory()->count(5)->create();

        $user = User::factory()->create();

        $user->cities()->attach(
            $cities->pluck('id')->toArray()
        );

        $this->assertInstanceOf(Collection::class, $user->cities);

        $this->assertCount(5, $user->cities);

        $this->assertTrue($user->cities->contains($cities[0]));

        $this->assertEquals($cities->pluck('id')->toArray(), $user->cities_ids);
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_has_address()
    {
        $user = User::factory()->create();

        Address::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->assertInstanceOf(Address::class, $user->address);
    }
}
