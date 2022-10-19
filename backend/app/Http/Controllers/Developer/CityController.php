<?php

namespace App\Http\Controllers\Developer;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\State;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\QueryBuilder\QueryBuilder;

class CityController extends Controller
{
    public function index(Request $request): \Inertia\Response
    {
        return Inertia::render('Developer/Cities/Index', [
            'cities' => QueryBuilder::for(City::class)
                ->with(['state'])
                ->withCount(['users', 'admins'])
                ->defaultSort('name')
                ->allowedFilters(['name'])
                ->paginate()
                ->appends(request()->query()),
        ]);
    }

    public function create(Request $request): \Inertia\Response
    {
        $states = State::all();

        return Inertia::render('Developer/Cities/Create', [
            'states' => $states,
        ]);
    }

    /**
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'state_id' => 'required|exists:states,id',
            'name' => 'required|string|min:2|max:255',
            'enabled' => 'required|boolean',
        ]);

        City::create($validated);

        return redirect(route('developer.cities.index'));
    }

    public function show(City $city): \Inertia\Response
    {
        return Inertia::render('Developer/Cities/Show', [
            'city' => $city->load(['state', 'users', 'admins']),
        ]);
    }

    public function edit(City $city): \Inertia\Response
    {
        $states = State::all();

        return Inertia::render('Developer/Cities/Edit', [
            'city' => $city,
            'states' => $states,
        ]);
    }

    /**
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(City $city, Request $request)
    {
        $validated = $request->validate([
            'state_id' => 'required|exists:states,id',
            'name' => 'required|string|min:2|max:255',
            'enabled' => 'required|boolean',
        ]);

        $city->update($validated);

        return redirect(route('developer.cities.index'));
    }

    public function destroy(City $city): \Illuminate\Http\RedirectResponse
    {
        $city->delete();

        return back(303);
    }
}
