<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreChargeOptionsRequest;
use Inertia\Inertia;

class AreaController extends Controller
{
    public function index(): \Inertia\Response
    {
        $user = auth()->user()->load(['address']);

        return Inertia::render('User/Area', [
            'user' => $user,
        ]);
    }

    public function updateChargeOptions(StoreChargeOptionsRequest $request): \Illuminate\Http\RedirectResponse
    {
        $user = auth()->user();
        
        $user->update([
            'charge_options' => $request->validated()['charge_options'],
        ]);

        $request->session()->flash('status', 'As informações de entrega foram atualizadas.');
        
        return back();
    }
}
