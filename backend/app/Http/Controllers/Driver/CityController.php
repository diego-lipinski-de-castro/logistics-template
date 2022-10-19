<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use App\Models\City;

class CityController extends Controller
{
    public function index()
    {
        return response()->json([
            'cities' => City::select(['id', 'name', 'created_at', 'updated_at'])
                ->orderBy('name')
                ->get(),
        ]);
    }
}
