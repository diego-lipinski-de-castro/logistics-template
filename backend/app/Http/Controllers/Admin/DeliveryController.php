<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\DispatchDelivery;
use App\Models\Delivery;
use App\Models\Driver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Spatie\QueryBuilder\QueryBuilder;

class DeliveryController extends Controller
{
    public function index(Request $request): \Inertia\Response
    {
        return Inertia::render('Admin/Deliveries/Index', [
            'deliveries' => QueryBuilder::for(Delivery::class)
                ->ofCities(auth()->user()->cities_ids)
                ->where(function ($query) {
                    $query
                        ->whereDate('created_at', DB::raw('CURRENT_DATE'))
                        ->orWhere(function ($subquery) {
                            $subquery->ofStatus(['WAITING', 'PENDING', 'ACCEPTED', 'COLLECTING', 'DELIVERING', 'RETURNING', 'CONFIRMED']);
                        });
                })
                ->with(['driver', 'user', 'user.city', 'steps'])
                ->defaultSort('-created_at')
                ->allowedFilters(['cid'])
                ->paginate()
                ->appends(request()->query()),
            'status' => session('status'),
        ]);
    }

    public function history(Request $request): \Inertia\Response
    {
        return Inertia::render('Admin/Deliveries/History', [
            'deliveries' => QueryBuilder::for(Delivery::class)
                ->ofCities(auth()->user()->cities_ids)
                ->ofStatus(['COMPLETED', 'CANCELED', 'NOT_DELIVERED'])
                ->with(['driver', 'user', 'user.city', 'steps'])
                ->defaultSort('-created_at')
                ->allowedFilters(['cid'])
                ->paginate()
                ->appends(request()->query()),
            'status' => session('status'),
        ]);
    }

    public function show(Delivery $delivery): \Inertia\Response
    {
        $delivery->load([
            'user',
            'user.city',
            'driver' => function ($q) {
                $q->with('metadata');
            },
            'steps',
            'route',
            'shots' => function ($q) {
                $q->with('driver');
            },
        ]);

        $drivers = Driver::query()
            ->ofCities(auth()->user()->cities_ids)
            ->approved()
            ->notBanned()
            ->with(['city', 'metadata'])
            ->orderBy('full_name')
            ->get();

        return Inertia::render('Admin/Deliveries/Show', [
            'delivery' => $delivery,
            'drivers' => $drivers,
            'status' => session('status'),
        ]);
    }

    //
    public function redispatch(Delivery $delivery, Request $request): \Illuminate\Http\RedirectResponse
    {
        DispatchDelivery::dispatchSync($delivery->id, true);

        $request->session()->flash('status', 'Entrega redisparada!');

        return back();
    }

    public function updateDriver(Delivery $delivery, Request $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'driver' => 'nullable|exists:drivers,id',
            'redispatch' => 'required|boolean',
        ]);

        $delivery->update([
            'driver_id' => $validated['driver'],
        ]);

        if (! $validated['redispatch']) {
            return back();
        }

        DispatchDelivery::dispatchSync($delivery->id, true);

        $request->session()->flash('status', 'Entrega redisparada!');

        return back();
    }
}
