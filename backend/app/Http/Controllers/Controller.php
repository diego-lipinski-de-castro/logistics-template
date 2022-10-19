<?php

namespace App\Http\Controllers;

use App\Models\State;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;

    public function userForm(): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
    {
        $states = State::query()
            ->select(['id', 'name'])
            ->with([
                'cities' => function ($q) {
                    $q->select(['id', 'state_id', 'name'])->orderBy('name');
                },
            ])
            ->orderBy('name')
            ->get();

        return view('user-form', [
            'states' => $states,
        ]);
    }

    public function userFormSubmit(Request $request): \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'city_id' => 'required|exists:cities,id',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'phone' => 'nullable',
        ]);

        $user = User::create($validated);

        $user->cities()->attach($validated['city_id']);

        return redirect(route('userSubmitSuccess'));
    }
}
