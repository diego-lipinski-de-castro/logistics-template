<?php

namespace Tests\Unit\Services;

use App\Jobs\FindDriver;
use App\Models\Delivery;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Queue;
use Tests\TestCase;

class FindDriverTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_find_driver()
    {
        // Queue::fake();

        // $validated = $request->validate([
        //     'user' => 'required|exists:users,id',
        //     'driver' => 'nullable|exists:drivers,id',
            
        //     'position' => 'array|size:2',

        //     'street_number' => 'nullable|integer',
        //     'street_name' => 'required|string',
        //     'district' => 'required|string',
        //     'city' => 'required|string',
        //     'state' => 'required|string',
        //     'info' => 'nullable|required_without:street_number|string|max:255',

        //     'customer_name' => 'required|string',
        //     'customer_phone' => 'required|string',

        //     'public_text' => 'nullable|string',
        //     'private_text' => 'nullable|string',

        //     'rad' => 'required|integer',
        //     'return' => 'required|boolean',
        //     'additional_info' => 'nullable|string',

        //     'line' => 'nullable',
            
        //     'distance' => 'nullable',
        //     'duration' => 'nullable',
        // ]);

        // $user = User::create([

        // ]);

        // $position = $validated['position'];

        // $radiuses = (new DeliveryService())->check(
        //     $user->id,
        //     [$position[0], $position[1]],
        //     true,
        // );

        // if (blank($radiuses)) {
        //     // TODO: return back with status
        //     return;
        // }

        // $delivery = Delivery::create([
        //     'status' => 'WAITING',

        //     'user_id' => $user->id,
        //     'driver_id' => $validated['driver'] ? $validated['driver'] : null,

        //     'rad' => $radiuses['rad'],
        //     'time' => $radiuses['time'],
        //     'paid' => $radiuses['paid'],
        //     'charged' => $radiuses['charged'],

        //     'return_fee_paid' => $validated['return'] ? $user->return_fee_paid : null,
        //     'return_fee_charged' => $validated['return'] ? $user->return_fee_charged : null,

        //     'receipt_confirmation' => $user->receipt_confirmation,

        //     'customer_name' => $validated['customer_name'],
        //     'customer_phone' => $validated['customer_phone'],

        //     'public_text' => $validated['public_text'],
        //     'private_text' => $validated['private_text'],
            
        //     'additional_info' => $validated['additional_info'],

        //     'charge_style' => $user->charge_style,
        // ]);

        // Step::create([
        //     'status' => 'PENDING',

        //     'delivery_id' => $delivery->id,

        //     'location' => new Point($position[0], $position[1]),
            
        //     'street_number' => $validated['street_number'] ? $validated['street_number'] : null,
        //     'street_name' => $validated['street_name'],
        //     'district' => $validated['district'],
        //     'city' => $validated['city'],
        //     'state' => $validated['state'],

        //     'info' => $validated['info'],

        //     'line' => isset($validated['line']) ? $validated['line'] : null,
        //     'distance' => isset($validated['distance']) ? $validated['distance'] : null,
        //     'duration' => isset($validated['duration']) ? $validated['duration'] : null,
        // ]);

        // DispatchDelivery::dispatch($delivery->id)
        //     ->onQueue('high')->delay(now()->addMinute());

        // broadcast(new WaitingDelivery($delivery->id))->toOthers();

        // return redirect(route('developer.deliveries'));

        // $result = (new FindDriver());

        // dispatch($result);

        // dd($result);

        $this->assertTrue(true);
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_find_driver_forced()
    {
        // Queue::fake();

        // $result = (new FindDriver());

        // dispatch($result);

        // dd($result);

        $this->assertTrue(true);
    }
}
