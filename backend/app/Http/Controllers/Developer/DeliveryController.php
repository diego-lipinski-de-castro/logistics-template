<?php

namespace App\Http\Controllers\Developer;

use Ajthinking\LaravelPostgis\Geometries\Point;
use App\Events\Developers\WaitingDelivery;
use App\Helper;
use App\Http\Controllers\Controller;
use App\Http\Resources\Developer\AuditResource;
use App\Jobs\DispatchDelivery;
use App\Models\City;
use App\Models\Delivery;
use App\Models\Driver;
use App\Models\Step;
use App\Models\User;
use App\Services\DeliveryService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class DeliveryController extends Controller
{
    public function index(Request $request): \Inertia\Response
    {
        $statuses = Delivery::STATUSES;

        $drivers = Driver::query()
            ->approved()
            ->orderBy('full_name')
            ->get(['id', 'full_name']);

        $users = User::orderBy('name')->get(['id', 'name']);

        $cities = City::enabled()->orderBy('name')->get(['id', 'name']);

        $filter = $request->query('filter');

        $stats = [
            [
                'name' => 'AGUARDANDO',
                'stat' => Delivery::ofStatus('WAITING')->count(),
                'color' => 'bg-blue-500 text-white',
            ],
            [
                'name' => 'PENDENTES',
                'stat' => Delivery::ofStatus('PENDING')->count(),
                'color' => 'bg-yellow-300 text-black',
            ],
            [
                'name' => 'EM ANDAMENTO',
                'stat' => Delivery::ofStatus(['ACCEPTED', 'COLLECTING', 'DELIVERING', 'RETURNING'])->count(),
                'color' => 'bg-speedapp-orange-500 text-white',
            ],
            [
                'name' => 'CONCLUÍDAS',
                'stat' => Delivery::whereDate('created_at', DB::raw('CURRENT_DATE'))->ofStatus('COMPLETED')->count(),
                'color' => 'bg-green-500 text-white',
            ],
            [
                'name' => 'CANCELADAS / NÃO ENTREGUES',
                'stat' => Delivery::whereDate('created_at', DB::raw('CURRENT_DATE'))->ofStatus(['CANCELED', 'NOT_DELIVERED'])->count(),
                'color' => 'bg-red-500 text-white',
            ],
        ];

        return Inertia::render('Developer/Deliveries/Index', [
            'deliveries' => QueryBuilder::for(Delivery::class)
                ->where(function ($query) use ($filter) {
                    $query
                        ->whereDate('created_at', DB::raw('CURRENT_DATE'))
                        ->when(! isset($filter['status']), function ($subquery) {
                            $subquery->orWhere(function ($subsubquery) {
                                $subsubquery->ofStatus(['WAITING', 'PENDING', 'ACCEPTED', 'COLLECTING', 'DELIVERING', 'RETURNING', 'CONFIRMED']);
                            });
                        });
                })
                ->with(['driver', 'user', 'user.city', 'steps'])
                ->defaultSort('-created_at')
                ->allowedSorts(['created_at', 'updated_at'])
                ->allowedFilters([
                    AllowedFilter::exact('cid'),
                    AllowedFilter::exact('status'),
                    AllowedFilter::exact('driver.id'),
                    AllowedFilter::exact('user.id'),
                    AllowedFilter::exact('user.city.id'),
                ])
                ->paginate((int) $request->query('per_page', '15'))
                ->appends(request()->query()),
            'statuses' => $statuses,
            'stats' => $stats,
            'drivers' => $drivers,
            'users' => $users,
            'cities' => $cities,
            'status' => session('status'),
        ]);
    }

    public function history(Request $request): \Inertia\Response
    {
        $statuses = [
            'COMPLETED' => 'Entregue',
            'CANCELED' => 'Cancelada',
            'NOT_DELIVERED' => 'Não entregue',
        ];

        $drivers = Driver::query()
            ->approved()
            ->orderBy('full_name')
            ->get(['id', 'full_name']);

        $users = User::orderBy('name')->get(['id', 'name']);

        $cities = City::enabled()->orderBy('name')->get(['id', 'name']);

        $date = $request->query('date', [
            'startDate' => null,
            'endDate' => null,
        ]);

        $filter = $request->query('filter');

        return Inertia::render('Developer/Deliveries/History', [
            'deliveries' => QueryBuilder::for(Delivery::class)
                ->where(function ($query) use ($date, $filter) {
                    $query
                        ->when((! blank($date['startDate']) && ! blank($date['endDate'])), function ($subquery) use ($date) {
                            $start = Carbon::createFromFormat('d/m/Y', $date['startDate']);
                            $end = Carbon::createFromFormat('d/m/Y', $date['endDate'])->addDay();

                            $subquery->whereBetween('created_at', [
                                $start->format('Y-m-d'),
                                $end->format('Y-m-d'),
                            ]);
                        })
                        ->when(! isset($filter['status']), function ($subquery) {
                            $subquery->ofStatus(['COMPLETED', 'CANCELED', 'NOT_DELIVERED']);
                        });
                })
                ->with(['driver', 'user', 'user.city', 'steps'])
                ->defaultSort('-created_at')
                ->allowedSorts(['created_at', 'updated_at'])
                ->allowedFilters([
                    AllowedFilter::exact('cid'),
                    AllowedFilter::exact('status'),
                    AllowedFilter::exact('driver.id'),
                    AllowedFilter::exact('user.id'),
                    AllowedFilter::exact('user.city.id'),
                ])
                ->paginate((int) $request->query('per_page', '15'))
                ->appends(request()->query()),
            'statuses' => $statuses,
            'drivers' => $drivers,
            'users' => $users,
            'cities' => $cities,
            'status' => session('status'),
        ]);
    }

    public function create(): \Inertia\Response
    {
        $users = User::query()
            ->has('address')
            ->with('address')
            ->orderBy('name')
            ->get();

        $users->each->append('drivers_ids');

        $drivers = Driver::query()
            ->with(['city', 'metadata'])
            ->approved()
            ->orderBy('full_name')
            ->get();

        return Inertia::render('Developer/Deliveries/Create', [
            'users' => $users,
            'drivers' => $drivers,
            'result' => session('result'),
        ]);
    }

    /**
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|null
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user' => 'required|exists:users,id',
            'driver' => 'nullable|exists:drivers,id',
            
            'position' => 'array|size:2',

            'street_number' => 'nullable|integer',
            'street_name' => 'required|string',
            'district' => 'required|string',
            'city' => 'required|string',
            'state' => 'required|string',
            'info' => 'nullable|required_without:street_number|string|max:255',

            'customer_name' => 'required|string',
            'customer_phone' => 'required|string',

            'public_text' => 'nullable|string',
            'private_text' => 'nullable|string',

            'rad' => 'required|integer',
            'return' => 'required|boolean',
            'additional_info' => 'nullable|string',

            'line' => 'nullable',
            
            'distance' => 'nullable',
            'duration' => 'nullable',
        ]);

        $user = User::findOrFail($validated['user']);

        $position = $validated['position'];

        $radiuses = (new DeliveryService())->check(
            $user->id,
            [$position[0], $position[1]],
            true,
        );

        if (blank($radiuses)) {
            // TODO: return back with status
            return;
        }

        $delivery = Delivery::create([
            'status' => 'WAITING',

            'user_id' => $user->id,
            'driver_id' => $validated['driver'] ? $validated['driver'] : null,

            'rad' => $radiuses['rad'],
            'time' => $radiuses['time'],
            'paid' => $radiuses['paid'],
            'charged' => $radiuses['charged'],

            'return_fee_paid' => $validated['return'] ? $user->return_fee_paid : null,
            'return_fee_charged' => $validated['return'] ? $user->return_fee_charged : null,

            'receipt_confirmation' => $user->receipt_confirmation,

            'customer_name' => $validated['customer_name'],
            'customer_phone' => $validated['customer_phone'],

            'public_text' => $validated['public_text'],
            'private_text' => $validated['private_text'],
            
            'additional_info' => $validated['additional_info'],

            'charge_style' => $user->charge_style,
        ]);

        $firstStep = Step::create([
            'status' => 'PENDING',

            'delivery_id' => $delivery->id,

            'location' => new Point($user->address->position->getLat(), $user->address->position->getLng()),
            
            'street_number' => $user->address->street_number,
            'street_name' => $user->address->street_name,
            'district' => $user->address->district,
            'city' => $user->address->city,
            'state' => $user->address->state,

            'info' => $user->name,
        ]);

        Step::create([
            'status' => 'PENDING',

            'prev_id' => $firstStep->id,

            'delivery_id' => $delivery->id,

            'location' => new Point($position[0], $position[1]),
            
            'street_number' => $validated['street_number'] ? $validated['street_number'] : null,
            'street_name' => $validated['street_name'],
            'district' => $validated['district'],
            'city' => $validated['city'],
            'state' => $validated['state'],

            'info' => $validated['info'],

            'line' => isset($validated['line']) ? $validated['line'] : null,
            'distance' => isset($validated['distance']) ? $validated['distance'] : null,
            'duration' => isset($validated['duration']) ? $validated['duration'] : null,
        ]);

        DispatchDelivery::dispatch($delivery->id)
            ->onQueue('high')->delay(now()->addMinute());

        broadcast(new WaitingDelivery($delivery->id))->toOthers();

        return redirect(route('developer.deliveries'));
    }

    public function storeLastmile(Request $request): \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'user' => 'required|exists:users,id',
            'driver' => 'nullable|exists:drivers,id',

            'custom_address' => 'required|boolean',
            
            'position' => 'nullable|required_if:custom_address,true|array|size:2',
            'street_number' => 'nullable|required_if:custom_address,true|integer',
            'street_name' => 'nullable|required_if:custom_address,true|string',
            'district' => 'nullable|required_if:custom_address,true|string',
            'city' => 'nullable|required_if:custom_address,true|string',
            'state' => 'nullable|required_if:custom_address,true|string',
            'info' => 'nullable|string|max:255',

            'scheduled' => 'required|boolean',
            'scheduled_at' => 'nullable|required_if:scheduled,true|date|after:now',

            'formatted_paid' => 'required',

            'public_text' => 'nullable|string',
            'private_text' => 'nullable|string',

            'additional_info' => 'nullable|string',
        ]);

        $user = User::findOrFail($validated['user']);

        $delivery = Delivery::create([
            'status' => 'WAITING',

            'user_id' => $user->id,
            'driver_id' => optional($validated)['driver'],

            'paid' => Helper::extractNumbersFromString($validated['formatted_paid']),
            'charged' => Helper::extractNumbersFromString($user->charge_options['markup']),

            'public_text' => $validated['public_text'],
            'private_text' => $validated['private_text'],
            
            'additional_info' => $validated['additional_info'],

            'charge_style' => $user->charge_style,

            'scheduled_at' => $validated['scheduled'] ? $validated['scheduled_at'] : null,
        ]);

        $firstStep = Step::create([
            'status' => 'PENDING',

            'delivery_id' => $delivery->id,

            'location' => $validated['custom_address'] ? new Point($validated['position'][0], $validated['position'][1]) : new Point($user->address->position->getLat(), $user->address->position->getLng()),
            
            'street_number' => $validated['custom_address'] ? $validated['street_number']  :  $user->address->street_number,
            'street_name' => $validated['custom_address'] ? $validated['street_name']  :  $user->address->street_name,
            'district' => $validated['custom_address'] ? $validated['district']  :  $user->address->district,
            'city' => $validated['custom_address'] ? $validated['city']  :  $user->address->city,
            'state' => $validated['custom_address'] ? $validated['state']  :  $user->address->state,

            'info' => $validated['custom_address'] ? $validated['info'] : $user->name,
        ]);

        $firstStep->replicate()->fill([
            'prev_id' => $firstStep->id,
        ])->save();

        DispatchDelivery::dispatch($delivery->id)
            ->onQueue('high')->delay(now()->addMinute());

        broadcast(new WaitingDelivery($delivery->id))->toOthers();

        return redirect(route('developer.deliveries'));
    }

    public function show(Delivery $delivery): \Inertia\Response
    {
        $delivery->load([
            'user',
            'user.city',
            'driver' => function ($q) {
                $q->with('metadata');
            },
            'steps',
            'route',
            'shots' => function ($q) {
                $q->with('driver')->orderBy('created_at', 'desc');
            },
            'audits' => function ($q) {
                $q->orderBy('created_at');
            },
            'receipt',
        ]);

        $delivery->audits->transform(function ($item) {
            return AuditResource::make($item);
        });

        $drivers = Driver::query()
            ->approved()
            ->notBanned()
            ->with(['city', 'metadata'])
            ->orderBy('full_name')
            ->get();

        return Inertia::render('Developer/Deliveries/Show', [
            'delivery' => $delivery,
            'drivers' => $drivers,
            'status' => session('status'),
        ]);
    }

    public function edit(Delivery $delivery): \Inertia\Response
    {
        $delivery->load('user', 'driver', 'driver.city', 'driver.metadata', 'steps');

        $statuses = Delivery::STATUSES;

        $drivers = Driver::query()
            ->with(['city', 'metadata'])
            ->approved()
            ->orderBy('full_name')
            ->get();

        $view = $delivery->lastmile ? 'Developer/Deliveries/EditLastmile' : 'Developer/Deliveries/Edit';

        return Inertia::render($view, [
            'statuses' => $statuses,
            'delivery' => $delivery,
            'drivers' => $drivers,
        ]);
    }

    /**
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Delivery $delivery, string $step, Request $request)
    {
        switch ($step) {
            case "status":
                $this->updateStatus($delivery, $request);

                break;
            case "driver":
                $this->updateDriver($delivery, $request);

                break;
            case "address":

                if ($delivery->lastmile) {
                    $this->updateLastmileAddress($delivery, $request);

                    break;
                }

                $this->updateAddress($delivery, $request);

                break;
            case "info":
                $this->updateInfo($delivery, $request);

                break;
            default:
                break;
        }
        
        return redirect(route('developer.deliveries.show', $delivery->id));
    }

    private function updateStatus(Delivery $delivery, Request $request): void
    {
        $statuses = implode(',', array_keys(Delivery::STATUSES));

        $validated = $request->validate([
            'status' => "required|in:$statuses",
        ]);

        // WAITING,PENDING,ACCEPTED,COLLECTING,DELIVERING,COMPLETED,CANCELED,RETURNING,CONFIRMED,NOT_DELIVERED
        switch ($validated['status']) {
            case "COMPLETED":
                    $now = now();

                    $delivery->update([
                        'status' => 'COMPLETED',
                        'delivered_at' => $now,
                        'elapsed_time' => $now->diff($delivery->pending_at)->format('%H:%I:%S'),
                    ]);

                    if ($delivery->driver()->exists()) {
                        $driver = Driver::with(['metadata'])->find($delivery->driver_id);

                        // TODO: check if user has other deliveries to be done
                        $driver->metadata->update([
                            'status' => 'ONLINE',
                        ]);

                        // TODO: create route or not?
                        // CreateRoute::dispatchAfterResponse($delivery->id);
                    }

                break;
            case "NOT_DELIVERED":
                $now = now();

                $delivery->update([
                    'status' => 'NOT_DELIVERED',
                    'elapsed_time' => $now->diff($delivery->pending_at)->format('%H:%I:%S'),
                ]);

                // TODO: Check if delivery has driver and free them
                break;
            case "CANCELED":
                $delivery->update([
                    'status' => 'CANCELED',
                ]);

                // TODO: Check if delivery has driver and free them
                break;
            default:
                $delivery->update([
                    'status' => $validated['status'],
                ]);

                break;
        }

        $request->session()->flash('status', 'O status da entrega foi atualizado!');
    }

    /**
     * @return void
     */
    private function updateDriver(Delivery $delivery, Request $request)
    {
        $validated = $request->validate([
            'driver' => 'nullable|exists:drivers,id',
            'redispatch' => 'required|boolean',
        ]);

        $delivery->update([
            'driver_id' => $validated['driver'],
        ]);

        if (! $validated['redispatch']) {
            return;
        }

        DispatchDelivery::dispatchSync($delivery->id, true);

        $request->session()->flash('status', 'Entrega redisparada!');
    }

    private function updateAddress(Delivery $delivery, Request $request): void
    {
        $validated = $request->validate([
            'street_number' => 'nullable|integer',
            'district' => 'required|string',

            'info' => 'nullable|required_without:street_number|string|max:255',

            'paid' => 'required',
            'charged' => 'required',

            'return' => 'required|boolean',

            'return_fee_paid' => 'required_if:return,true',
            'return_fee_charged' => 'required_if:return,true',
        ]);

        $delivery->update([
            'paid' => Helper::extractNumbersFromString($validated['paid']),
            'charged' => Helper::extractNumbersFromString($validated['charged']),

            'return_fee_paid' => $validated['return'] ? Helper::extractNumbersFromString($validated['return_fee_paid']) : null,
            'return_fee_charged' => $validated['return'] ? Helper::extractNumbersFromString($validated['return_fee_charged']) : null,
        ]);

        $delivery->steps[1]->update([
            'street_number' => $validated['street_number'] ?? null,
            'district' => $validated['district'],

            'info' => $validated['info'] ?? null,
        ]);

        $request->session()->flash('status', 'As informações da entrega foram atualizadas!');
    }

    private function updateLastmileAddress(Delivery $delivery, Request $request): void
    {
        $validated = $request->validate([
            'custom_address' => 'required|boolean',

            'position' => 'required_if:custom_address,true|array|size:2',
            'street_number' => 'required_if:custom_address,true|integer',
            'street_name' => 'required_if:custom_address,true|string',
            'district' => 'required_if:custom_address,true|string',
            'city' => 'required_if:custom_address,true|string',
            'state' => 'required_if:custom_address,true|string',
            'info' => 'nullable|string|max:255',

            'scheduled' => 'required|boolean',
            'scheduled_at' => 'nullable|required_if:scheduled,true|date|after:now',

            'paid' => 'required',
            'charged' => 'required',
        ]);

        $delivery->update([
            'paid' => Helper::extractNumbersFromString($validated['paid']),
            'charged' => Helper::extractNumbersFromString($validated['charged']),

            'scheduled_at' => $validated['scheduled'] ? $validated['scheduled_at'] : null,
        ]);

        $delivery->steps[0]->update([
            'location' => $validated['custom_address'] ? new Point($validated['position'][0], $validated['position'][1]) : new Point($delivery->user->address->position->getLat(), $delivery->user->address->position->getLng()),
        
            'street_number' => $validated['custom_address'] ? $validated['street_number']  :  $delivery->user->address->street_number,
            'street_name' => $validated['custom_address'] ? $validated['street_name']  :  $delivery->user->address->street_name,
            'district' => $validated['custom_address'] ? $validated['district']  :  $delivery->user->address->district,
            'city' => $validated['custom_address'] ? $validated['city']  :  $delivery->user->address->city,
            'state' => $validated['custom_address'] ? $validated['state']  :  $delivery->user->address->state,

            'info' => $validated['custom_address'] ? $validated['info'] : $delivery->user->name,
        ]);

        $delivery->steps[1]->update([
            'location' => $validated['custom_address'] ? new Point($validated['position'][0], $validated['position'][1]) : new Point($delivery->user->address->position->getLat(), $delivery->user->address->position->getLng()),
        
            'street_number' => $validated['custom_address'] ? $validated['street_number']  :  $delivery->user->address->street_number,
            'street_name' => $validated['custom_address'] ? $validated['street_name']  :  $delivery->user->address->street_name,
            'district' => $validated['custom_address'] ? $validated['district']  :  $delivery->user->address->district,
            'city' => $validated['custom_address'] ? $validated['city']  :  $delivery->user->address->city,
            'state' => $validated['custom_address'] ? $validated['state']  :  $delivery->user->address->state,

            'info' => $validated['custom_address'] ? $validated['info'] : $delivery->user->name,
        ]);

        $request->session()->flash('status', 'As informações da entrega foram atualizadas!');
    }

    private function updateInfo(Delivery $delivery, Request $request): void
    {
        $validated = $request->validate([
            'customer_name' => 'nullable|string',
            'customer_phone' => 'nullable|string',
            
            'public_text' => 'nullable|string',
            'private_text' => 'nullable|string',
            'additional_info' => 'nullable|string',
        ]);

        $delivery->update($validated);

        $request->session()->flash('status', 'As informações da entrega foram atualizadas!');
    }

    //
    public function check(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'position' => 'required',
            'user' => 'required|exists:users,id',
        ]);

        $position = $validated['position'];

        $result = (new DeliveryService())->check(
            $validated['user'],
            [$position[0], $position[1]],
        );
        
        $request->session()->flash('result', $result);

        return back();
    }

    //
    public function redispatch(Delivery $delivery, Request $request): \Illuminate\Http\RedirectResponse
    {
        DispatchDelivery::dispatchSync($delivery->id, true);

        $request->session()->flash('status', 'Entrega redisparada!');

        return back();
    }

    /**
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function receipt(Delivery $delivery)
    {
        if (Storage::missing($delivery->receipt->picture)) {
            abort(404);
        }

        return response(Storage::get($delivery->receipt->picture), 200);
    }
}
