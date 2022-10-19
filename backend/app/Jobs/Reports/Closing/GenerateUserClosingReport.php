<?php

namespace App\Jobs\Reports\Closing;

use App\Helper;
use App\Models\Delivery;
use App\Models\User;
use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Storage;

class GenerateUserClosingReport implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    private $user;
    private $range;
    private $filename;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(int $userId, array $range, string $filename)
    {
        $this->user = User::find($userId);
        $this->range = $range;
        $this->filename = $filename;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (blank($this->user)) {
            return;
        }

        $data = Delivery::query()
            ->with(['driver', 'user', 'user.city', 'steps'])
            ->ofUser($this->user->id)
            ->ofStatuses(['COMPLETED', 'CANCELED'])
            ->whereBetween('created_at', $this->range)
            ->orderBy('created_at')
            ->get();

        Storage::disk('local')->makeDirectory("users/{$this->user->id}/reports");

        $path = storage_path("app/users/{$this->user->id}/reports/{$this->filename}");

        fastexcel($data)
            ->headerStyle(
                (new StyleBuilder())
                    ->setFontBold()
                    ->build()
            )
            ->export($path, function ($row) {
                $model = json_decode(json_encode($row));

                return [
                    'ID' => $model->cid ? "#{$model->cid}" : '-',

                    'STATUS' => $model->formatted_status ?? '-',
                    'ENTREGADOR' => $model->driver->full_name ?? '-',
                    'CIDADE' => $model->user->city->name ?? '-',
                    'ENDEREÇO' => $model->steps[1]->formatted_address ?? '-',
                    'CLIENTE' => $model->customer_name ?? '-',
                    'SOLICITADO ÀS' => $model->formatted_report_created_at,

                    'VALOR' => Helper::toDecimal($model->total_charged),
                ];
            });

        ExportReportToS3::dispatch($this->user->id, $this->filename);
    }
}
