<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Inertia\Inertia;
use Spatie\QueryBuilder\QueryBuilder;

class UserController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(User::class, 'user');
    }

    public function index(): \Inertia\Response
    {
        return Inertia::render('Admin/Users/Index', [
            'users' => QueryBuilder::for(User::class)
                ->ofCities(auth()->user()->cities_ids)
                ->defaultSort('name')
                ->allowedFilters(['name'])
                ->paginate()
                ->appends(request()->query()),
            'status' => session('status'),
        ]);
    }

    public function show(User $user): \Inertia\Response
    {
        $user->load(['cities', 'address']);
        
        return Inertia::render('Admin/Users/Show', [
            'user' => $user,
        ]);
    }
}
