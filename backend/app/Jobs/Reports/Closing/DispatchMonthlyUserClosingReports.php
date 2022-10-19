<?php

namespace App\Jobs\Reports\Closing;

use App\Models\Delivery;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DispatchMonthlyUserClosingReports implements ShouldQueue
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
        // This job will be running every first day of the month at 03:00
        // Example: the job is running day march 1 at 03:00

        $monthlyUsers = User::query()
            ->where('report_period', 'MONTHLY')
            ->get();

        foreach ($monthlyUsers as $user) {
            $filename = 'Relatório de fechamento '. $user->name .' entre ' . now()->subMonth()->format('d-m-Y') . ' e ' . now()->subDay()->format('d-m-Y') . ' exportado às ' . now()->format('d-m-Y H:i:s') . '.xls';

            // Get every user delivery between feb 1 and feb 28/29
            GenerateUserClosingReport::dispatch($user->id, [
                now()->subMonth()->format('Y-m-d'),
                now()->format('Y-m-d'),
            ], $filename);
        }
    }
}
