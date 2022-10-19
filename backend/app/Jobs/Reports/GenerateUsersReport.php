<?php

namespace App\Jobs\Reports;

use App\Helper;
use App\Models\Delivery;
use App\Models\User;
use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Rap2hpoutre\FastExcel\SheetCollection;

class GenerateUsersReport implements ShouldQueue //, ShouldBeUnique
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    private $now;
    private $range;
    private $status;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $now, array $range = [], array $status = [])
    {
        $this->now = $now;
        $this->range = $range;
        $this->status = $status;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->range[0] == $this->range[1]) {
            $filename = 'Relatório de entregas das lojas exportado às ' . $this->now . '.xls';
        } else {
            $filename = 'Relatório de entregas das lojas entre ' . $this->range[0]->format('d-m-Y') . ' e ' . $this->range[1]->format('d-m-Y') . ' exportado às ' . $this->now . '.xls';
        }
        
        $sheets = new SheetCollection();

        $users = User::whereHas('deliveries')->get();

        foreach ($users as $user) {
            $data = Delivery::query()
                ->with(['driver', 'user', 'user.city', 'steps'])
                ->ofUser($user->id)
                ->ofStatus($this->status)
                ->when($this->range, function ($query) {
                    if ($this->range[0] == $this->range[1]) {
                        return $query->whereDate('created_at', $this->range[0]);
                    }
                    
                    return $query->whereBetween('created_at', [
                        $this->range[0]->format('Y-m-d'),
                        $this->range[1]->addDay()->format('Y-m-d'),
                    ]);
                })->orderBy('created_at')->get();

            $sheets->put($user->name ?? '-', $data);
        }

        fastexcel($sheets)
            ->headerStyle(
                (new StyleBuilder())
                    ->setFontBold()
                    ->build()
            )
            ->export(storage_path("app/reports/$filename"), function ($row) {
                $model = json_decode(json_encode($row));
                
                return [
                    'ID' => $model->cid ? "#{$model->cid}" : '-',

                    'STATUS' => $model->formatted_status ?? '-',
                    'ENTREGADOR' => $model->driver->full_name ?? '-',
                    'LOJA' => $model->user->name ?? '-',
                    'CIDADE' => $model->user->city->name ?? '-',
                    'ENDEREÇO' => $model->steps[1]->formatted_address ?? '-',
                    'CLIENTE' => $model->customer_name ?? '-',
                    'SOLICITADO ÀS' => $model->formatted_report_created_at,

                    'PAGO' => Helper::toDecimal($model->total_paid),
                    'COBRADO' => Helper::toDecimal($model->total_charged),
                ];
            });

        ExportReportToS3::dispatch($filename);
    }
}
