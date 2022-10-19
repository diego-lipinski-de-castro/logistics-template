<?php

namespace App\Http\Controllers\Developer;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use Inertia\Inertia;

class MapController extends Controller
{
    public function index(): \Inertia\Response
    {
        $drivers = Driver::query()
            ->approved()
            ->notBanned()
            ->with(['metadata'])
            ->orderBy('full_name')
            ->get();

        return Inertia::render('Developer/Map', [
            'drivers' => $drivers,
            'status' => session('status'),
        ]);
    }
}
