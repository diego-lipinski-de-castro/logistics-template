<?php

namespace App\Http\Controllers\Developer;

use App\Http\Controllers\Controller;
use App\Models\State;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\QueryBuilder\QueryBuilder;

class StateController extends Controller
{
    public function index(Request $request): \Inertia\Response
    {
        return Inertia::render('Developer/States/Index', [
            'states' => QueryBuilder::for(State::class)
                ->withCount(['cities'])
                ->defaultSort('name')
                ->allowedFilters(['name'])
                ->paginate()
                ->appends(request()->query()),
        ]);
    }

    public function create(): \Inertia\Response
    {
        return Inertia::render('Developer/States/Create');
    }

    /**
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
        ]);

        State::create($validated);

        return redirect(route('developer.states.index'));
    }

    public function show(State $state): \Inertia\Response
    {
        return Inertia::render('Developer/States/Show', [
            'state' => $state->load(['cities']),
        ]);
    }

    public function edit(State $state): \Inertia\Response
    {
        return Inertia::render('Developer/States/Edit', [
            'state' => $state,
        ]);
    }

    /**
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(State $state, Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
        ]);

        $state->update($validated);

        return redirect(route('developer.states.index'));
    }

    public function destroy(State $state): \Illuminate\Http\RedirectResponse
    {
        $state->delete();

        return back(303);
    }
}
