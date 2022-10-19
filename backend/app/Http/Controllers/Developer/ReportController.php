<?php

namespace App\Http\Controllers\Developer;

use App\Http\Controllers\Controller;
use App\Jobs\Reports\GenerateCitiesReport;
use App\Jobs\Reports\GenerateCityReport;
use App\Jobs\Reports\GenerateDriverReport;
use App\Jobs\Reports\GenerateDriversReport;
use App\Jobs\Reports\GenerateUserReport;
use App\Jobs\Reports\GenerateUsersReport;
use App\Models\City;
use App\Models\Delivery;
use App\Models\Driver;
use App\Models\User;
use Bus;
use Carbon\Carbon;
use Illuminate\Bus\Batch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Throwable;

class ReportController extends Controller
{
    public function index(Request $request): \Inertia\Response
    {
        $cities = City::query()
            ->select(['id', 'name', 'enabled'])
            ->enabled()
            ->orderBy('name')
            ->get();
        
        $users = User::query()
            ->select(['id', 'name'])
            ->orderBy('name')
            ->get();
        
        $drivers = Driver::query()
            ->select(['id', 'full_name'])
            ->approved()
            ->orderBy('full_name')
            ->get();

        $statuses = Delivery::STATUSES;

        return Inertia::render('Developer/Report', [
            'cities' => $cities,
            'users' => $users,
            'drivers' => $drivers,
            'statuses' => $statuses,
            'batch' => session('batch'),
            'status' => session('status'),
        ]);
    }

    public function download(Request $request)
    {
        $filename = $request->query('filename');

        if (Storage::cloud()->missing("reports/$filename")) {
            abort(404);
        }

        return Storage::cloud()->download("reports/$filename", $filename);
    }

    public function generate(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'date' => 'required|array|size:2',
            'date.0' => 'nullable|date_format:d/m/Y|before_or_equal:date.1',
            'date.1' => 'nullable|date_format:d/m/Y|after_or_equal:date.0',
            'filter' => 'required|string|in:cities,users,drivers',
            'option' => 'nullable',
            'status' => 'required|array|min:1|in:WAITING,PENDING,ACCEPTED,COLLECTING,DELIVERING,COMPLETED,CANCELED,RETURNING,CONFIRMED,NOT_DELIVERED',
            'individual' => 'required|boolean',
        ]);

        $now = Carbon::now()->format('d-m-Y H:i:s');

        $range = [];

        if (! blank($validated['date'])) {
            $start = Carbon::createFromFormat('d/m/Y', $validated['date'][0]);
            $end = Carbon::createFromFormat('d/m/Y', $validated['date'][1]);

            $range = [$start, $end];
        }

        switch ($validated['filter']) {
            case 'cities':

                if (! blank($validated['option'])) {
                    GenerateCityReport::dispatch($now, $range, $validated['status'], $validated['option']);
                } else {
                    if (! $validated['individual']) {
                        GenerateCitiesReport::dispatch($now, $range, $validated['status']);
                    } else {
                        $cities = City::whereHas('deliveries')->get();

                        $jobs = collect([]);

                        $cities->each(function ($city) use ($now, $range, $validated, $jobs) {
                            $jobs->push(new GenerateCityReport($now, $range, $validated['status'], $city->id));
                        });

                        $batch = Bus::batch($jobs->toArray())
                            ->then(function (Batch $batch) {
                                Log::debug('All jobs completed successfully');
                            })->catch(function (Batch $batch, Throwable $e) {
                                Log::debug('First batch job failure detected');
                            })->finally(function (Batch $batch) {
                                Log::debug('The batch has finished executing');
                            })
                                ->allowFailures()
                                ->name('Export individual cities report')
                                ->dispatch();

                        $request->session()->flash('batch', [
                            'id' => $batch->id,
                            'name' => $batch->name,
                            'totalJobs' => $batch->totalJobs,
                            'pendingJobs' => $batch->pendingJobs,
                            'failedJobs' => $batch->failedJobs,
                            'processedJobs' => $batch->processedJobs(),
                            'progress' => $batch->progress(),
                            'finished' => $batch->finished(),
                            'cancel' => $batch->cancel(),
                            'cancelled' => $batch->cancelled(),
                        ]);
                    }
                }

                break;
            case 'users':

                if (! blank($validated['option'])) {
                    GenerateUserReport::dispatch($now, $range, $validated['status'], $validated['option']);
                } else {
                    if (! $validated['individual']) {
                        GenerateUsersReport::dispatch($now, $range, $validated['status']);
                    } else {
                        $users = User::whereHas('deliveries')->get();

                        $jobs = collect([]);

                        $users->each(function ($user) use ($now, $range, $validated, $jobs) {
                            $jobs->push(new GenerateUserReport($now, $range, $validated['status'], $user->id));
                        });

                        $batch = Bus::batch($jobs->toArray())
                            ->then(function (Batch $batch) {
                                Log::debug('All jobs completed successfully');
                            })->catch(function (Batch $batch, Throwable $e) {
                                Log::debug('First batch job failure detected');
                            })->finally(function (Batch $batch) {
                                Log::debug('The batch has finished executing');
                            })
                                ->allowFailures()
                                ->name('Export individual users report')
                                ->dispatch();

                        $request->session()->flash('batch', [
                            'id' => $batch->id,
                            'name' => $batch->name,
                            'totalJobs' => $batch->totalJobs,
                            'pendingJobs' => $batch->pendingJobs,
                            'failedJobs' => $batch->failedJobs,
                            'processedJobs' => $batch->processedJobs(),
                            'progress' => $batch->progress(),
                            'finished' => $batch->finished(),
                            'cancel' => $batch->cancel(),
                            'cancelled' => $batch->cancelled(),
                        ]);
                    }
                }

                break;
            case 'drivers':

                if (! blank($validated['option'])) {
                    GenerateDriverReport::dispatch($now, $range, $validated['status'], $validated['option']);
                } else {
                    if (! $validated['individual']) {
                        GenerateDriversReport::dispatch($now, $range, $validated['status']);
                    } else {
                        $drivers = Driver::whereHas('deliveries')->get();

                        $jobs = collect([]);

                        $drivers->each(function ($driver) use ($now, $range, $validated, $jobs) {
                            $jobs->push(new GenerateDriverReport($now, $range, $validated['status'], $driver->id));
                        });

                        $batch = Bus::batch($jobs->toArray())
                            ->then(function (Batch $batch) {
                                Log::debug('All jobs completed successfully');
                            })->catch(function (Batch $batch, Throwable $e) {
                                Log::debug('First batch job failure detected');
                            })->finally(function (Batch $batch) {
                                Log::debug('The batch has finished executing');
                            })
                                ->allowFailures()
                                ->name('Export individual drivers report')
                                ->dispatch();

                        $request->session()->flash('batch', [
                            'id' => $batch->id,
                            'name' => $batch->name,
                            'totalJobs' => $batch->totalJobs,
                            'pendingJobs' => $batch->pendingJobs,
                            'failedJobs' => $batch->failedJobs,
                            'processedJobs' => $batch->processedJobs(),
                            'progress' => $batch->progress(),
                            'finished' => $batch->finished(),
                            'cancel' => $batch->cancel(),
                            'cancelled' => $batch->cancelled(),
                        ]);
                    }
                }

                break;
        }


        $request->session()->flash('status', [
            'Sucesso!',
            'Os relatórios estão sendo gerados.',
            'Esse processo pode demorar alguns minutos.',
        ]);

        return back();
    }
}
