<?php

namespace App\Http\Controllers\Developer;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDriverRequest;
use App\Http\Requests\UpdateDriverRequest;
use App\Models\City;
use App\Models\Document;
use App\Models\Driver;
use App\Models\DriverMetadata;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class DriverController extends Controller
{
    public function index(): \Inertia\Response
    {
        $cities = City::enabled()->get(['id', 'name']);
        $statuses = Driver::STATUSES;

        return Inertia::render('Developer/Drivers/Index', [
            'drivers' => QueryBuilder::for(Driver::class)
                ->with(['city', 'metadata'])
                ->defaultSort('-created_at')
                ->allowedSorts(['full_name', 'registered', 'created_at', 'updated_at'])
                ->allowedFilters(['full_name', 'phone', AllowedFilter::exact('city.id'), AllowedFilter::exact('status'), AllowedFilter::exact('registered')])
                ->paginate()
                ->appends(request()->query()),
            'cities' => $cities,
            'statuses' => $statuses,
            'status' => session('status'),
        ]);
    }

    public function create(): \Inertia\Response
    {
        $cities = City::enabled()->get();

        return Inertia::render('Developer/Drivers/Create', [
            'cities' => $cities,
        ]);
    }

    /**
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(StoreDriverRequest $request)
    {
        Driver::create(array_merge($request->validated(), [
            'status' => 'APPROVED',
            'registered' => true,
        ]));

        $request->session()->flash('status', 'O entregador foi adicionado.');

        return redirect(route('developer.drivers.index'));
    }

    public function show(Driver $driver): \Inertia\Response
    {
        $driver->load(['city', 'metadata']);
        
        return Inertia::render('Developer/Drivers/Show', [
            'driver' => $driver,
        ]);
    }

    public function edit(Driver $driver): \Inertia\Response
    {
        $driver->load(['city', 'metadata', 'documents']);

        $cities = City::enabled()->with(['state'])->get();

        return Inertia::render('Developer/Drivers/Edit', [
            'driver' => $driver,
            'cities' => $cities,

            'modes' => DriverMetadata::MODES,
            'bags' => DriverMetadata::BAGS,
            'driverStatuses' => DriverMetadata::STATUSES,

            'status' => session('status'),
        ]);
    }

    public function update(Driver $driver, UpdateDriverRequest $request): \Illuminate\Http\RedirectResponse
    {
        $driver->update($request->validated());

        $request->session()->flash('status', 'O entregador foi atualizado.');

        return back();
    }

    public function destroy(Driver $driver, Request $request): \Illuminate\Http\RedirectResponse
    {
        $driver->delete();

        $request->session()->flash('status', 'O entregador foi removido.');

        return back();
    }

    /**
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function ban(Driver $driver, Request $request)
    {
        $driver->update([
            'banned_at' => now(),
        ]);

        $request->session()->flash('status', 'O entregador foi banido.');

        return redirect(route('developer.drivers.index'));
    }

    /**
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function unban(Driver $driver, Request $request)
    {
        $driver->update([
            'banned_at' => null,
        ]);

        $request->session()->flash('status', 'O entregador foi desbanido.');

        return redirect(route('developer.drivers.index'));
    }

    public function approve(Driver $driver, Request $request): \Illuminate\Http\RedirectResponse
    {
        $driver->update([
            'status' => 'APPROVED',
        ]);

        return back();
    }

    public function reject(Driver $driver, Request $request): \Illuminate\Http\RedirectResponse
    {
        $driver->update([
            'status' => 'REJECTED',
        ]);

        return back();
    }

    public function unreject(Driver $driver, Request $request): \Illuminate\Http\RedirectResponse
    {
        $driver->update([
            'status' => 'PENDING',
        ]);

        return back();
    }

    public function updateBank(Driver $driver, Request $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'pix' => 'nullable',
        ]);

        $driver->update($validated);

        $request->session()->flash('status', 'Os dados bancários do entregador foram atualizados.');

        return back();
    }

    public function updateTransport(Driver $driver, Request $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'cnh' => '',
            'vehicle_name' => '',
            'vehicle_plate' => '',

            'metadata.mode' => '',
            'metadata.bag' => '',
        ]);

        $driver->update(Arr::except($validated, 'metadata'));

        $driver->metadata()->update($validated['metadata']);

        $request->session()->flash('status', 'Os dados de transporte do entregador foram atualizados.');

        return back();
    }

    public function updateCloud(Driver $driver, Request $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'cloud' => 'required|boolean',
        ]);

        $driver->metadata()->update($validated);

        $request->session()->flash('status', 'Os dados de nuvem do entregador foram atualizados.');

        return back();
    }

    public function updateStatus(Driver $driver, Request $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:ONLINE,OFFLINE',
        ]);

        $driver->metadata()->update([
            'status' => $validated['status'],
        ]);

        $request->session()->flash('status', 'O status do entregador foi atualizado.');

        return back();
    }

    public function storeDocuments(Driver $driver, Request $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'documents' => 'required|array',
            'documents.*.folder' => 'required|string',
            'documents.*.file' => 'file',
        ]);

        foreach ($validated['documents'] as $document) {
            $doc = (object) $document;

            $path = $doc->file->store("drivers/{$driver->id}/{$doc->folder}");

            $filename = $doc->file->getClientOriginalName();

            Document::create([
                'driver_id' => $driver->id,

                'folder' => $doc->folder,

                'name' => pathinfo($filename, PATHINFO_FILENAME),
                'type' => pathinfo($filename, PATHINFO_EXTENSION),

                'path' => $path,
                'url' => Storage::url($path),
            ]);
        }

        $request->session()->flash('status', 'Os documentos do entregador foram atualizados.');

        return back();
    }

    public function deleteDocument(Driver $driver, Document $document, Request $request): \Illuminate\Http\RedirectResponse
    {
        $document->delete();

        $request->session()->flash('status', 'O documento do entregador foi apagado.');

        return back();
    }

    /**
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function showDocument(Driver $driver, Document $document)
    {
        if (Storage::missing($document->path)) {
            abort(404);
        }

        return response(Storage::get($document->path), 200, [
            'Content-Type' => $document->mimey,
        ]);
    }
}
