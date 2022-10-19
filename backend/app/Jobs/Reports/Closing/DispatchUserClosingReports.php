<?php

namespace App\Jobs\Reports\Closing;

use App\Models\Delivery;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Str;

class DispatchUserClosingReports implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // This job will be running everyday at 03:00
        
        // Example: the job is running day feb 26 at 03:00

        // DAY
        // Get every user that closed yesterday (feb 25)
        $dayUsers = User::query()
            ->where('report_period', 'DAY')
            ->where('report_period_option', now()->subDay()->day)
            ->get();

        foreach ($dayUsers as $user) {
            $filename = 'Relatório de fechamento '. $user->name .' entre ' . now()->subMonth()->format('d-m-Y') . ' e ' . now()->subDay()->format('d-m-Y') . ' exportado às ' . now()->format('d-m-Y H:i:s') . '.xls';

            // Get every user delivery between jan 26 and feb 25
            GenerateUserClosingReport::dispatch($user->id, [
                now()->subMonth()->format('Y-m-d'),
                now()->format('Y-m-d'),
            ], $filename);
        }

        // WEEKLY
        // 26 is saturday
        // Get every user that closed yesterday
        $weekUsers = User::query()
            ->where('report_period', 'WEEKLY')
            ->where('report_period_option', Str::upper(now()->subDay()->format('l')))
            ->get();

        foreach ($weekUsers as $user) {
            $filename = 'Relatório de fechamento '. $user->name .' entre ' . now()->subWeek()->format('d-m-Y') . ' e ' . now()->subDay()->format('d-m-Y') . ' exportado às ' . now()->format('d-m-Y H:i:s') . '.xls';

            // Get every user delivery between
            // feb 25 (yesterday) and feb 19
            // (previous saturday, so we are ignoring previous friday, as we are closing at fridays)
            GenerateUserClosingReport::dispatch($user->id, [
                now()->subWeek()->format('Y-m-d'),
                now()->format('Y-m-d'),
            ], $filename);
        }
    }
}
