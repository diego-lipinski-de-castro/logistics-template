<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use Inertia\Inertia;

class MapController extends Controller
{
    public function index(): \Inertia\Response
    {
        $drivers = Driver::query()
            ->ofCities(auth()->user()->cities_ids)
            ->approved()
            ->notBanned()
            ->with(['metadata'])
            ->orderBy('full_name')
            ->get();

        $bounds = auth()->user()->cities->pluck('center');

        $bounds->transform(function ($point) {
            return [$point->getLat(), $point->getLng()];
        });

        return Inertia::render('Admin/Map', [
            'drivers' => $drivers,
            'bounds' => $bounds,
            'status' => session('status'),
        ]);
    }
}
