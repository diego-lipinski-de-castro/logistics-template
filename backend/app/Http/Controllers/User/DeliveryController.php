<?php

namespace App\Http\Controllers\User;

use Ajthinking\LaravelPostgis\Geometries\Point;
use App\Events\Developers\WaitingDelivery;
use App\Helper;
use App\Http\Controllers\Controller;
use App\Jobs\DispatchDelivery;
use App\Models\Delivery;
use App\Models\Driver;
use App\Models\Step;
use App\Services\DeliveryService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class DeliveryController extends Controller
{
    public function index(Request $request): \Inertia\Response
    {
        $statuses = Delivery::STATUSES;

        $filter = $request->query('filter');

        return Inertia::render('Deliveries/Index', [
            'deliveries' => QueryBuilder::for(Delivery::class)
                ->ofUser(auth()->id())
                ->where(function ($query) use ($filter) {
                    $query
                        ->whereDate('created_at', DB::raw('CURRENT_DATE'))
                        ->when(! isset($filter['status']), function ($subquery) {
                            $subquery->orWhere(function ($subsubquery) {
                                $subsubquery->ofStatus(['WAITING', 'PENDING', 'ACCEPTED', 'COLLECTING', 'DELIVERING', 'RETURNING', 'CONFIRMED']);
                            });
                        });
                })
                ->with(['driver', 'steps'])
                ->defaultSort('-created_at')
                ->allowedSorts(['created_at', 'updated_at'])
                ->allowedFilters([
                    AllowedFilter::exact('cid'),
                    AllowedFilter::exact('status'),
                ])
                ->paginate()
                ->appends(request()->query()),
            'statuses' => $statuses,
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

        $date = $request->query('date', [
            'startDate' => null,
            'endDate' => null,
        ]);

        $filter = $request->query('filter');

        return Inertia::render('Deliveries/History', [
            'deliveries' => QueryBuilder::for(Delivery::class)
                ->ofUser(auth()->id())
                ->where(function ($query) use ($date, $filter) {
                    $query
                        ->when((! blank($date['startDate']) && ! blank($date['endDate'])), function ($subquery) use ($date) {
                            $start = Carbon::createFromFormat('d/m/Y', $date['startDate']);
                            $end = Carbon::createFromFormat('d/m/Y', $date['endDate']);

                            $subquery->whereBetween('created_at', [
                                $start->format('Y-m-d'),
                                $end->format('Y-m-d'),
                            ]);
                        })
                        ->when(! isset($filter['status']), function ($subquery) {
                            $subquery->ofStatus(['COMPLETED', 'CANCELED', 'NOT_DELIVERED']);
                        });
                })
                ->with(['driver', 'steps'])
                ->defaultSort('-created_at')
                ->allowedSorts(['created_at', 'updated_at'])
                ->allowedFilters([
                    AllowedFilter::exact('cid'),
                    AllowedFilter::exact('status'),
                ])
                ->paginate((int) $request->query('per_page', '15'))
                ->appends(request()->query()),
            'statuses' => $statuses,
            'status' => session('status'),
        ]);
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
        ]);

        return Inertia::render('Deliveries/Show', [
            'delivery' => $delivery,
            'status' => session('status'),
        ]);
    }

    /**
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Inertia\Response
     */
    public function create()
    {
        $user = auth()->user()->load(['address']);

        if (blank($user->address)) {
            return redirect(route('user.deliveries'));
        }

        if ($user->lastmile) {
            $drivers = Driver::query()
                ->with(['city', 'metadata'])
                ->approved()
                ->ofUser(auth()->id())
                ->orderBy('full_name')
                ->get();

            return Inertia::render('Deliveries/CreateLastmile', [
                'user' => $user,
                'drivers' => $drivers,
                'result' => session('result'),
            ]);
        }

        return Inertia::render('Deliveries/Create', [
            'user' => $user,
            'result' => session('result'),
        ]);
    }

    /**
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|null
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'position' => 'array|size:2',

            'street_number' => 'required|integer',
            'street_name' => 'required|string',
            'district' => 'required|string',
            'city' => 'required|string',
            'state' => 'required|string',
            'info' => 'nullable|string|max:255',

            'customer_name' => 'required',
            'customer_phone' => 'required',

            'public_text' => 'nullable|string',

            'rad' => 'required|integer',
            'return' => 'required|boolean',

            'line' => 'nullable',
            
            'distance' => 'nullable',
            'duration' => 'nullable',
        ]);

        $user = auth()->user()->load(['address']);

        $position = $validated['position'];

        $radiuses = (new DeliveryService())->check(
            $user->id,
            [$position[0], $position[1]],
            true,
        );

        if (blank($radiuses)) {
            // Return back with status
            return;
        }

        $delivery = Delivery::create([
            'status' => 'WAITING',

            'user_id' => $user->id,

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
            
            'street_number' => $validated['street_number'],
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

        broadcast(new WaitingDelivery($delivery->id));

        return redirect(route('user.deliveries'));
    }

    public function storeLastmile(Request $request): \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
    {
        $user = auth()->user()->load(['address']);

        $validated = $request->validate([
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
            // 'packages' => [
            //     'nullable',
            //     Rule::requiredIf($user->charge_style == 'PACKAGE'),
            // ],

            'public_text' => 'nullable|string',
        ]);

        $delivery = Delivery::create([
            'status' => 'WAITING',

            'user_id' => $user->id,
            'driver_id' => optional($validated)['driver'],

            'paid' => Helper::extractNumbersFromString($validated['formatted_paid']),
            'charged' => Helper::extractNumbersFromString($user->charge_options['markup']),

            'public_text' => $validated['public_text'],

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

        broadcast(new WaitingDelivery($delivery->id));

        return redirect(route('user.deliveries'));
    }

    public function check(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'position' => 'required',
        ]);

        $position = $validated['position'];

        $result = (new DeliveryService())->check(
            auth()->id(),
            [$position[0], $position[1]],
        );

        unset($result['paid']);
        
        $request->session()->flash('result', $result);

        return back();
    }
}
