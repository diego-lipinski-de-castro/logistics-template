<?php

namespace App\Http\Controllers\Developer;

use Ajthinking\LaravelPostgis\Geometries\LineString;
use Ajthinking\LaravelPostgis\Geometries\Point;
use Ajthinking\LaravelPostgis\Geometries\Polygon;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreChargeOptionsRequest;
use App\Http\Requests\StoreRadiusesRequest;
use App\Http\Requests\UpdateAddressRequest;
use App\Http\Requests\UpdateUserOptionsRequest;
use App\Models\City;
use App\Models\Driver;
use App\Models\Report;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class UserController extends Controller
{
    public function index(): \Inertia\Response
    {
        $cities = City::enabled()->get(['id', 'name']);

        return Inertia::render('Developer/Users/Index', [
            'users' => QueryBuilder::for(User::class)
                ->with('city')
                ->defaultSort('-created_at')
                ->allowedSorts(['name', 'created_at', 'updated_at'])
                ->allowedFilters(['name', 'email', AllowedFilter::exact('city.id')])
                ->paginate()
                ->appends(request()->query()),
                'cities' => $cities,
                'status' => session('status'),
        ]);
    }

    public function create(): \Inertia\Response
    {
        $cities = City::enabled()->get();

        return Inertia::render('Developer/Users/Create', [
            'cities' => $cities,
        ]);
    }

    /**
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'city_id' => 'required|exists:cities,id',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create(array_merge($validated, [
            'password' => bcrypt($validated['password']),
            'status' => 'APPROVED',
        ]));

        $user->cities()->attach($validated['city_id']);

        return redirect(route('developer.users.edit', $user->id));
    }

    public function show(User $user): \Inertia\Response
    {
        $user->load(['cities', 'address']);
        
        return Inertia::render('Developer/Users/Show', [
            'user' => $user,
        ]);
    }

    public function edit(User $user): \Inertia\Response
    {
        $cities = City::enabled()->with(['state'])->orderBy('name')->get();
        $drivers = Driver::approved()->orderBy('full_name')->get();

        $chargeStyleOptions = User::CHARGE_STYLES;
        $receiptConfirmationOptions = User::RECEIPT_CONFIRMATION;

        return Inertia::render('Developer/Users/Edit', [
            'user' => $user->fresh(['address', 'cities', 'drivers']),
            'cities' => $cities,
            'drivers' => $drivers,
            'chargeStyleOptions' => $chargeStyleOptions,
            'receiptConfirmationOptions' => $receiptConfirmationOptions,
            'status' => session('status'),
        ]);
    }

    public function update(User $user, Request $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'city_id' => 'required|exists:cities,id',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string',
            'cpf_cnpj' => 'nullable|string',
            'company_type' => 'nullable|in:MEI,LIMITED,INDIVIDUAL,ASSOCIATION',
            'info' => 'nullable|string',
        ]);

        $user->update($validated);

        $request->session()->flash('status', 'A loja foi atualizada.');

        return back();
    }

    public function destroy(User $user, Request $request): \Illuminate\Http\RedirectResponse
    {
        $user->delete();

        $request->session()->flash('status', 'A loja foi removida.');

        return back();
    }

    public function updatePassword(User $user, Request $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'password' => 'required|string|min:6',
        ]);

        $user->forceFill([
            'password' => bcrypt($validated['password']),
        ])->save();

        $request->session()->flash('status', 'A senha da loja foi atualizada.');

        return back();
    }

    public function updateOptions(User $user, UpdateUserOptionsRequest $request): \Illuminate\Http\RedirectResponse
    {
        $user->fill($request->validated());

        if ($user->isDirty('charge_style')) {
            $user->charge_options = null;
        }

        $user->save();

        $request->session()->flash('status', 'As opções da loja foram atualizadas.');

        return back();
    }

    public function updateCities(User $user, Request $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'cities' => 'present|array|min:0',
        ]);

        $user->cities()->sync($validated['cities']);

        $request->session()->flash('status', 'As cidades da loja foram atualizadas.');

        return back();
    }

    public function updateDrivers(User $user, Request $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'drivers' => 'present|array|min:0',
        ]);

        $user->drivers()->sync($validated['drivers']);

        $request->session()->flash('status', 'Os entregadores da loja foram atualizadas.');

        return back();
    }

    public function updateAddress(User $user, UpdateAddressRequest $request): \Illuminate\Http\RedirectResponse
    {
        $user->address()->exists()
            ? $user->address()->update($request->validated())
            : $user->address()->create($request->validated());

        $request->session()->flash('status', 'O endereço da loja foi atualizado.');

        return back();
    }

    public function updateArea(User $user, Request $request): \Illuminate\Http\RedirectResponse
    {
        $points = collect($request->coordinates[0])->map(function (array $point) {
            return new Point($point[1], $point[0]);
        })->toArray();

        $lineString = new LineString($points);

        $user->update([
            'area' => new Polygon([$lineString]),
        ]);

        $user->updateRadiuses();

        $request->session()->flash('status', 'A área de entrega da loja foi atualizada.');

        return back();
    }

    public function updateRadiuses(User $user, StoreRadiusesRequest $request): \Illuminate\Http\RedirectResponse
    {
        $user->update([
            'radiuses' => $request->validated()['radiuses'],
        ]);

        $request->session()->flash('status', 'As informações de entrega da loja foram atualizadas.');
        
        return back();
    }

    public function updateChargeOptions(User $user, StoreChargeOptionsRequest $request): \Illuminate\Http\RedirectResponse
    {
        $user->update([
            'charge_options' => $request->validated()['charge_options'],
        ]);

        $request->session()->flash('status', 'As informações de entrega da loja foram atualizadas.');
        
        return back();
    }

    public function updateReport(User $user, Request $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'report_period' => 'required|in:DAY,WEEKLY,MONTHLY',
            'report_period_option' => 'required_unless:report_period,MONTHLY',
        ]);

        $user->update($validated);

        $request->session()->flash('status', 'As configurações dos relatórios da loja foram atualizadas.');

        return back();
    }

    public function reports(User $user): \Inertia\Response
    {
        $reports = Report::query()
            ->ofUser($user->id)
            ->orderBy('created_at', 'desc')
            ->paginate();
        
        return Inertia::render('Developer/Users/ReportIndex', [
            'user' => $user,
            'reports' => $reports,
        ]);
    }

    public function downloadReport(User $user, Request $request)
    {
        $filename = $request->query('filename');

        if (Storage::cloud()->missing("users/{$user->id}/reports/$filename")) {
            abort(404);
        }

        return Storage::cloud()->download("users/{$user->id}/reports/$filename", $filename);
    }
}
