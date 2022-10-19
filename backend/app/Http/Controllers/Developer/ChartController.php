<?php

namespace App\Http\Controllers\Developer;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Delivery;
use App\Models\Driver;
use App\Models\User;
use App\Services\ChartService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ChartController extends Controller
{
    public function index(Request $request): \Inertia\Response
    {
        $cities = City::query()
            ->select(['id', 'name', 'enabled'])
            ->enabled()
            ->orderBy('name')
            ->get();
        
        $users = User::query()
            ->select(['id', 'name'])
            ->orderBy('name')
            ->get();
        
        $drivers = Driver::query()
            ->select(['id', 'full_name'])
            ->approved()
            ->orderBy('full_name')
            ->get();
        
        $statuses = Delivery::STATUSES;

        // $model = $request->query('model');
        // $id = $request->query('id');

        $averageDeliveriesByDay = ChartService::averageDeliveriesByDay();
        $averageDeliveriesByHour = ChartService::averageDeliveriesByHour();
        $averageDeliveriesByDayByHour = ChartService::averageDeliveriesByDayByHour();

        return Inertia::render('Developer/Charts', [
            'status' => session('status'),
            
            'cities' => $cities,
            'users' => $users,
            'drivers' => $drivers,
            'statuses' => $statuses,

            'averageDeliveriesByDay' => $averageDeliveriesByDay,
            'averageDeliveriesByHour' => $averageDeliveriesByHour,
            'averageDeliveriesByDayByHour' => $averageDeliveriesByDayByHour,
        ]);
    }
}
