<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\QueryBuilder\QueryBuilder;

class DriverController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Driver::class, 'driver');
    }

    public function index(): \Inertia\Response
    {
        return Inertia::render('Admin/Drivers/Index', [
            'drivers' => QueryBuilder::for(Driver::class)
                ->ofCities(auth()->user()->cities_ids)
                ->defaultSort('full_name')
                ->allowedFilters(['full_name'])
                ->paginate()
                ->appends(request()->query()),
            'status' => session('status'),
        ]);
    }

    public function show(Driver $driver): \Inertia\Response
    {
        $driver->load(['city', 'metadata']);
        
        return Inertia::render('Admin/Drivers/Show', [
            'driver' => $driver,
        ]);
    }

    public function updateStatus(Driver $driver, Request $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:ONLINE,OFFLINE',
        ]);

        $driver->metadata()->update([
            'status' => $validated['status'],
        ]);

        $request->session()->flash('status', 'O status do entregador foi atualizado.');

        return back();
    }
}
