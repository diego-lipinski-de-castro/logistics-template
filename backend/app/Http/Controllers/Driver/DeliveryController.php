<?php

namespace App\Http\Controllers\Driver;

use App\Events\RemoveDelivery;
use App\Http\Controllers\Controller;
use App\Http\Resources\Driver\DeliveryResource;
use App\Jobs\CreateRoute;
use App\Models\Delivery;
use App\Models\Driver;
use App\Models\Receipt;
use App\Models\Shot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DeliveryController extends Controller
{
    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $deliveries = Delivery::query()
            ->with(['user', 'steps'])
            ->ofDriver(auth()->id())
            // ->notRefused(auth()->id())
            ->ofStatus(['PENDING', 'ACCEPTED', 'COLLECTING', 'DELIVERING', 'CONFIRMED', 'RETURNING'])
            ->get();

        return DeliveryResource::collection($deliveries);
    }

    public function today(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $deliveries = Delivery::query()
            ->with(['user', 'steps'])
            ->ofDriver(auth()->id())
            ->whereDate('created_at', DB::raw('CURRENT_DATE'))
            ->ofStatus(['COMPLETED', 'CANCELED', 'NOT_DELIVERED'])
            ->paginate();

        return DeliveryResource::collection($deliveries);
    }

    public function history(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $deliveries = Delivery::query()
            ->with(['user', 'steps'])
            ->ofDriver(auth()->id())
            ->ofStatus(['COMPLETED', 'CANCELED', 'NOT_DELIVERED'])
            ->orderByDesc('created_at')
            ->paginate();

        return DeliveryResource::collection($deliveries);
    }

    public function show($id): DeliveryResource
    {
        $delivery = Delivery::with(['user', 'steps'])
            ->ofDriver(auth()->id())
            ->find($id);

        if (blank($delivery)) {
            abort(404);
        }

        return new DeliveryResource($delivery);
    }

    public function accept($id)
    {
        $delivery = Delivery::with(['user', 'steps'])
            ->find($id);

        if (blank($delivery)) {
            return response()->json([
                'message' => 'Entrega não encontrada.',
            ], 422);
        }

        if ($delivery->status != 'PENDING') {
            return response()->json([
                'message' => 'Entrega já foi aceita.',
            ], 422);
        }

        if ($delivery->driver()->exists() && $delivery->driver_id !== auth()->id()) {
            return response()->json([
                'message' => 'Entrega já foi aceita.',
            ], 422);
        }

        if ($delivery->lastmile && ! in_array(auth()->id(), $delivery->user->drivers_ids)) {
            return response()->json([
                'message' => 'Você não pode aceitar entregas dessa loja.',
            ], 422);
        }

        $driver = Driver::with(['metadata'])->find(auth()->id());

        if ($driver->banned) {
            return response()->json([
                'message' => 'Você não pode aceitar entregas.',
            ], 422);
        }

        if ($driver->metadata->status === 'OFFLINE') {
            return response()->json([
                'message' => 'Fique ONLINE para aceitar entregas.',
            ], 422);
        }

        // CHECK USER OPTIONS
        // if ($driver->metadata->status === 'BUSY') {
        //     return response()->json([
        //         'message' => 'Você não pode acietar entregas nesse momento.',
        //     ], 422);
        // }

        $delivery->update([
            'status' => 'ACCEPTED',
            'accepted_at' => now(),
            'driver_id' => $driver->id,
        ]);

        $driver->metadata->update([
            'status' => 'BUSY',
        ]);

        $delivery->fresh();

        event(new RemoveDelivery($delivery->id, $delivery->user->cities_ids));

        Shot::create([
            'delivery_id' => $delivery->id,
            'driver_id' => $driver->id,
            'action' => 'ACCEPTED',
            'created_at' => now(),
        ]);

        return new DeliveryResource($delivery);
    }

    public function collect($id)
    {
        $delivery = Delivery::with(['user', 'steps'])->find($id);

        if ($delivery->driver_id !== auth()->id()) {
            return response()->json([
                'message' => 'Você não pode alterar essa entrega.',
            ], 422);
        }

        $delivery->update([
            'status' => 'COLLECTING',
        ]);

        $delivery->fresh();

        return new DeliveryResource($delivery);
    }

    public function deliver($id)
    {
        $delivery = Delivery::with(['user', 'steps'])->find($id);

        if ($delivery->driver_id !== auth()->id()) {
            return response()->json([
                'message' => 'Você não pode alterar essa entrega.',
            ], 422);
        }

        $delivery->update([
            'status' => 'DELIVERING',
        ]);

        $delivery->fresh();

        return new DeliveryResource($delivery);
    }

    public function confirm($id, Request $request)
    {
        $delivery = Delivery::with(['user', 'steps'])->find($id);

        if ($delivery->receipt_confirmation != 'DISABLED') {
            if (! $this->_getReceipt($delivery, $request)) {
                return response()->json([
                    'message' => 'Oops',
                ], 422);
            }
        }

        if ($delivery->driver_id !== auth()->id()) {
            return response()->json([
                'message' => 'Você não pode alterar essa entrega.',
            ], 422);
        }

        $now = now();

        $delivery->update([
            'status' => is_null($delivery->return_fee_charged) ? 'COMPLETED' : 'RETURNING',
            'delivered_at' => $now,
            'elapsed_time' => $now->diff($delivery->pending_at)->format('%H:%I:%S'),
        ]);

        $delivery->fresh();

        CreateRoute::dispatch($delivery->id);

        $driver = Driver::with(['metadata'])->find(auth()->id());

        // TODO: check if user has other deliveries to be done
        $driver->metadata->update([
            'status' => 'ONLINE',
        ]);

        return new DeliveryResource($delivery);
    }

    public function complete($id)
    {
        $delivery = Delivery::with(['user', 'steps'])->find($id);

        if ($delivery->driver_id !== auth()->id()) {
            return response()->json([
                'message' => 'Você não pode alterar essa entrega.',
            ], 422);
        }

        $delivery->update([
            'status' => 'COMPLETED',
        ]);

        $delivery->fresh();

        return new DeliveryResource($delivery);
    }

    public function receive($id)
    {
        $delivery = Delivery::with(['user', 'steps'])->findOrFail($id);

        $driver = Driver::find(auth()->id());

        Shot::create([
            'delivery_id' => $delivery->id,
            'driver_id' => $driver->id,
            'action' => 'RECEIVED',
            'created_at' => now(),
        ]);

        return response()->json([
            'delivery' => $delivery,
        ]);
    }

    public function refuse($id)
    {
        $delivery = Delivery::with(['user', 'steps'])->findOrFail($id);

        $driver = Driver::find(auth()->id());

        Shot::create([
            'delivery_id' => $delivery->id,
            'driver_id' => $driver->id,
            'action' => 'REFUSED',
            'created_at' => now(),
        ]);

        if ($delivery->driver()->exists()) {
            $delivery->update([
                'driver_id' => null,
            ]);
        }

        return response()->noContent();
    }

    /**
     * @return true
     */
    private function _getReceipt(Delivery $delivery, Request $request): bool
    {
        if ($delivery->receipt_confirmation == 'OPTIONAL') {
            $validated = $request->validate([
                'customer_name' => 'nullable|string|min:2|max:255',
                'customer_document' => 'nullable|min:1|max:255',
                'picture' => 'nullable|file',
            ]);
        }

        if ($delivery->receipt_confirmation == 'REQUIRED_PARTIAL') {
            $validated = $request->validate([
                'customer_name' => 'required|string|min:2|max:255',
                'customer_document' => 'required|min:1|max:255',
                'picture' => 'nullable|file',
            ]);
        }

        if ($delivery->receipt_confirmation == 'REQUIRED') {
            $validated = $request->validate([
                'customer_name' => 'required|string|min:2|max:255',
                'customer_document' => 'required|min:1|max:255',
                'picture' => 'required|file',
            ]);
        }
        
        if (isset($validated['picture'])) {
            $file = $validated['picture'];

            $name = now()->timestamp;
            $ext = pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);

            $path = $file->storeAs("receipts", "{$delivery->id}-{$name}.{$ext}");
        }

        Receipt::create(array_merge($validated, [
            'delivery_id' => $delivery->id,
            'picture' => isset($path) ? $path : null,
        ]));

        return true;
    }
}
