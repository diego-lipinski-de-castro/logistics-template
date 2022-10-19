<?php

namespace App\Http\Controllers\Developer;

use App\Filters\NameOrEmailFilter;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\City;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class AdminController extends Controller
{
    public function index(): \Inertia\Response
    {
        return Inertia::render('Developer/Admins/Index', [
            'admins' => QueryBuilder::for(Admin::class)
                ->defaultSort('name')
                ->allowedFilters([
                    AllowedFilter::custom('name_email', new NameOrEmailFilter),
                ])
                ->paginate()
                ->appends(request()->query()),
                'status' => session('status'),
        ]);
    }

    public function create(): \Inertia\Response
    {
        $cities = City::enabled()->get();

        return Inertia::render('Developer/Admins/Create', [
            'cities' => $cities,
        ]);
    }

    /**
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:admins,email',
            'password' => 'required|string|min:6',
        ]);

        Admin::create(array_merge($validated, [
            'password' => bcrypt($validated['password']),
        ]));

        $request->session()->flash('status', 'O admin foi adicionado.');

        return redirect(route('developer.admins.index'));
    }

    public function show(Admin $admin): \Inertia\Response
    {
        $admin->load(['cities']);
        
        return Inertia::render('Developer/Admins/Show', [
            'admin' => $admin,
        ]);
    }

    public function edit(Admin $admin): \Inertia\Response
    {
        $admin->load(['cities']);

        $cities = City::enabled()->with(['state'])->get();

        return Inertia::render('Developer/Admins/Edit', [
            'admin' => $admin,
            'cities' => $cities,
            'status' => session('status'),
        ]);
    }

    public function update(Admin $admin, Request $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:admins,email,' . $admin->id,
        ]);

        $admin->update($validated);

        $request->session()->flash('status', 'O admin foi atualizado.');

        return back();
    }

    public function destroy(Admin $admin, Request $request): \Illuminate\Http\RedirectResponse
    {
        $admin->delete();

        $request->session()->flash('status', 'O admin foi removido.');

        return back();
    }

    public function updatePassword(Admin $admin, Request $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'password' => 'required|string|min:6',
        ]);

        $admin->forceFill([
            'password' => bcrypt($validated['password']),
        ])->save();

        $request->session()->flash('status', 'A senha do admin foi aualizada.');

        return back();
    }

    public function updateCities(Admin $admin, Request $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'cities' => 'present|array|min:0',
        ]);

        $admin->cities()->sync($validated['cities']);

        $request->session()->flash('status', 'As cidades do admin foram atualizadas.');

        return back();
    }
}
