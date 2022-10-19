<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use App\Models\State;

class StateController extends Controller
{
    public function index()
    {
        return response()->json([
            'states' => State::query()
                ->with([
                    'cities' => function ($query) {
                        $query->select(['id', 'name', 'enabled', 'state_id', 'created_at', 'updated_at'])->orderBy('name');
                    },
                ])
                ->orderBy('name')
                ->get(),
        ]);
    }
}
